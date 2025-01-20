<?php

namespace App\ActionCommand;

use App\Character\Character;
use App\FightResultSet;
use App\GameApplication;

class AttackCommand implements ActionCommandInterface
{
    private int $damageDealt;
    private int $stamina;

    public function __construct(
        private readonly Character $player,
        private readonly Character $opponent,
        private readonly FightResultSet $fightResultSet,
    ) {
    }

    public function execute(): void
    {
        $this->stamina = $this->player->getStamina();
        $damage = $this->player->attack();
        if ($damage === 0) {
            GameApplication::$printer->printFor($this->player)->exhaustedMessage();
            $this->fightResultSet->of($this->player)->addExhaustedTurn();
            return;
        }
        $damageDealt = $this->opponent->receiveAttack($damage);
        $this->damageDealt = $damageDealt;
        $this->fightResultSet->of($this->player)->addDamageDealt($damageDealt);
        $this->fightResultSet->of($this->opponent)->addDamageReceived($damageDealt);
        GameApplication::$printer->printFor($this->player)->attackMessage($damageDealt);
        GameApplication::$printer->writeln('');
        usleep(300000);
    }
    public function undo(): void
    {
        $this->opponent->setHealth($this->opponent->getCurrentHealth() + $this->damageDealt);
        $this->player->setStamina($this->stamina);
        $this->fightResultSet->of($this->player)->removeDamageDealt($this->damageDealt);
        $this->fightResultSet->of($this->opponent)->removeDamageReceived($this->damageDealt);
    }
}