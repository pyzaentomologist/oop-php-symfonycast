<?php

namespace App\Scoring;

use App\Entity\BigFootSighting;

class MaxScoreAdjuster implements ScoreAdjusterInterface
{
  public function adjustScore(int $finalScore, BigFootSighting $sighting): int
  {
    return min($finalScore, 100);
  }
}