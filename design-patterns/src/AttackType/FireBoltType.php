<?php

namespace App\AttackType;

use App\Dice;

class FireBoltType implements AttackType
{
  public function performAttack(int $baseDemage): int
  {
    return Dice::roll(10) + Dice::roll(10) + Dice::roll(10);
  }
}