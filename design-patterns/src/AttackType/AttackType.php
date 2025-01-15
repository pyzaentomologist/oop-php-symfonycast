<?php

namespace App\AttackType;

interface AttackType
{
  public function performAttack(int $baseDemage): int;
}