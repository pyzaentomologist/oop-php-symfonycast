<?php

namespace App\DifficultyState;

use App\GameDifficultyContext;
use App\Character\Character;
use App\Dice;
use App\FightResult;
use App\GameApplication;

class HardState implements DifficultyStateInterface
{
  public function victory(GameDifficultyContext $difficultyContext, Character $player, FightResult $fightResult): void
  {
    // This is like D&D style, where rolling 1 means critical failure and 20 big success
    switch (Dice::roll(20)) {
      case 1:
        $difficultyContext->enemyLevelBonus = $player->getLevel() + 5;
        break;
      case 20:
        $player->setXpBonus(100);
        break;
      default:
        // restore bonus settings
        $difficultyContext->enemyLevelBonus = $player->getLevel() + 1;
        $player->setXpBonus(50);
        break;
    }
  }
  public function defeat(GameDifficultyContext $difficultyContext, Character $player, FightResult $fightResult): void
  {
    if ($fightResult->getLoseStreak() >= 2) {
      $difficultyContext->enemyHealthBonus = 5;
      $difficultyContext->enemyAttackBonus = 5;
      $player->setXpBonus(25);
      $difficultyContext->difficultyState = new MediumState();

      GameApplication::$printer->info('Game difficulty level decreased to Medium!');
    }
  }
}