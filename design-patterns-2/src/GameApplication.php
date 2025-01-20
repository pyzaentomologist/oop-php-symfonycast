<?php

namespace App;

use App\ActionCommand\AttackCommand;
use App\ActionCommand\HealCommand;
use App\ActionCommand\SurrenderCommand;
use App\Builder\CharacterBuilder;
use App\ChainHandler\CasinoHandler;
use App\ChainHandler\XpBonusHandlerInterface;
use App\Character\Character;
use App\Factory\UltimateAttackTypeFactory;
use App\Observer\GameObserverInterface;
use App\Printer\MessagePrinter;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class GameApplication
{
    public static MessagePrinter $printer;

    private GameDifficultyContext $difficultyContext;

    /** @var GameObserverInterface[] */
    private array $observers = [];

    public function __construct(
        #[Autowire(service: CasinoHandler::class)]
        private readonly XpBonusHandlerInterface $xpBonusHandler,
        private readonly CharacterBuilder $characterBuilder
    )
    {
        $this->difficultyContext = new GameDifficultyContext();
    }

    public function activateCheatCode(string $cheatCode): void
    {
        switch ($cheatCode) {
            // Famous Konami Code
            case 'up-up-down-down-left-right-left-right-b-a-start':
                $this->characterBuilder->setAttackTypeFactory(new UltimateAttackTypeFactory());
                GameApplication::$printer->info('Cheat code activated!!');
                break;
            default:
                GameApplication::$printer->info('Invalid cheat code - better luck next time!');
                break;
        }
    }

    public function play(Character $player, Character $ai, FightResultSet $fightResultSet): void
    {
        while (true) {
            $fightResultSet->addRound();
            GameApplication::$printer->writeln([
                '------------------------------',
                sprintf('ROUND %d', $fightResultSet->getRounds()
            ), '']);
            // Player's turn
            $actionChoice = GameApplication::$printer->choice('Your turn', [
                'Attack',
                'Heal',
                'Surrender',
            ]);
            $playerAction = match ($actionChoice) {
                'Attack' => new AttackCommand($player, $ai, $fightResultSet),
                'Heal' => new HealCommand($player),
                'Surrender' => new SurrenderCommand($player),
            };
            $playerAction->execute();
            if ($this->didPlayerDie($ai)) {
                $this->endBattle($fightResultSet, $player, $ai);
                return;
            }
            // AI's turn
            $aiAttackCommand = new AttackCommand($ai, $player, $fightResultSet);
            $aiAttackCommand->execute();
            if ($this->didPlayerDie($player)) {
                $undoChoice = GameApplication::$printer->confirm('You died! Do you want to undo your last turn?');
                if (!$undoChoice) {
                    $this->endBattle($fightResultSet, $ai, $player);
                    return;
                }
                // These have to be undone in the order they were executed
                $aiAttackCommand->undo();
                $playerAction->undo();
            }
            $this->printCurrentHealth($player, $ai);
            usleep(300000);
        }
    }

    public function victory(Character $player, FightResult $fightResult): void
    {
        $this->difficultyContext->victory($player, $fightResult);
    }

    public function defeat(Character $player, FightResult $fightResult): void
    {
        $this->difficultyContext->defeat($player, $fightResult);
    }

    private function endBattle(FightResultSet $fightResultSet, Character $winner, Character $loser): void
    {
        GameApplication::$printer->printFor($winner)->victoryMessage($loser);
        
        $fightResultSet->setWinner($winner);
        $fightResultSet->setLoser($loser);
        $fightResultSet->of($winner)->addVictory();
        $fightResultSet->of($loser)->addDefeat();
        $xpBonus = $this->xpBonusHandler->handle($winner, $fightResultSet->of($winner));
        $winner->addXp($xpBonus);
        $this->notify($fightResultSet);
        $winner->rest();
        $loser->rest();
    }

    private function didPlayerDie(Character $player): bool
    {
        return $player->getCurrentHealth() <= 0;
    }

    public function createAiCharacter(): Character
    {
        $characters = $this->getCharactersList();
        $aiCharacterString = $characters[array_rand($characters)];
        $aiCharacter = $this->createCharacter(
            $aiCharacterString,
            $this->difficultyContext->enemyAttackBonus,
            $this->difficultyContext->enemyHealthBonus,
            1 + $this->difficultyContext->enemyLevelBonus
        );
        $aiCharacter->setNickname($aiCharacterString);
        return $aiCharacter;
    }

    public function createCharacter(string $character, int $extraBaseDamage = 0, int $extraHealth = 0, int $level = 1): Character
    {
        return match (strtolower($character)) {
            'fighter' => $this->characterBuilder
                ->setMaxHealth(60 + $extraHealth)
                ->setBaseDamage(12 + $extraBaseDamage)
                ->setAttackType('sword')
                ->setArmorType('shield')
                ->setLevel($level)
                ->buildCharacter(),
            'archer' => $this->characterBuilder
                ->setMaxHealth(50 + $extraHealth)
                ->setBaseDamage(10 + $extraBaseDamage)
                ->setAttackType('bow')
                ->setArmorType('leather_armor')
                ->setLevel($level)
                ->buildCharacter(),
            'mage' => $this->characterBuilder
                ->setMaxHealth(40 + $extraHealth)
                ->setBaseDamage(8 + $extraBaseDamage)
                ->setAttackType('fire_bolt')
                ->setArmorType('ice_block')
                ->setLevel($level)
                ->buildCharacter(),
            'mage_archer' => $this->characterBuilder
                ->setMaxHealth(50 + $extraHealth)
                ->setBaseDamage(9 + $extraBaseDamage)
                ->setAttackType('fire_bolt', 'bow')
                ->setArmorType('shield')
                ->setLevel($level)
                ->buildCharacter(),
            default => throw new \RuntimeException('Undefined Character')
        };
    }

    public function getCharactersList(): array
    {
        return [
            'fighter',
            'mage',
            'archer',
            'mage_archer',
        ];
    }

    private function printCurrentHealth(Character $player, Character $ai): void
    {
        GameApplication::$printer->writeln([sprintf(
            'Current Health: <comment>%d/%d</comment> %sAI Health: <comment>%d/%d</comment>',
            $player->getCurrentHealth(),
            $player->getMaxHealth(),
            PHP_EOL,
            $ai->getCurrentHealth(),
            $ai->getMaxHealth(),
        ), '']);
    }

    public function subscribe(GameObserverInterface $observer): void
    {
        if (!in_array($observer, $this->observers, true)) {
            $this->observers[] = $observer;
        }
    }

    public function unsubscribe(GameObserverInterface $observer): void
    {
        $key = array_search($observer, $this->observers, true);
        if ($key !== false) {
            unset($this->observers[$key]);
        }
    }

    private function notify(FightResultSet $fightResultSet): void
    {
        foreach ($this->observers as $observer) {
            $observer->onFightFinished($fightResultSet);
        }
    }
}