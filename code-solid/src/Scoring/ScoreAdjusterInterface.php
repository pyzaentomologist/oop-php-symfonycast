<?php

namespace App\Scoring;

use App\Entity\BigFootSighting;

interface ScoreAdjusterInterface
{
  public function adjustScore(int $finalScore, BigFootSighting $sighting): int;
}