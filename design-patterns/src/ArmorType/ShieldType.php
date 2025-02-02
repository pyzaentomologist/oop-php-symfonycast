<?php

namespace App\ArmorType;

use App\Dice;

class ShieldType implements ArmorType
{
  public function getArmorReduction(int $damage): float
  {
    $chanceToBlock = Dice::roll(100);
        return $chanceToBlock > 80 ? $damage : 0;
  }
}