<?php

namespace App\ArmorType;

class LeatherArmorType implements ArmorType
{
  public function getArmorReduction(int $damage): float
  {
    return floor($damage * 0.25);
  }
}