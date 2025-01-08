<?php

namespace App\Model;

class DebuggableBigFootSightingScore extends BigFootSightingScore
{
  private float $calculationTime;

    public function __construct(int $score, float $calculationTime)
    {
        parent::__construct($score);

        $this->calculationTime = $calculationTime;
    }

    public function getCalculationTime(): float
    {
      return $this->calculationTime;
    }
}