<?php

namespace App\Scoring;

use App\Entity\BigFootSighting;

class CoordinatesFactor implements ScoringFactorInterface
{  
    public function score(BigFootSighting $sighting): int
    {
        $score = 0;
        $lat = (float)$sighting->getLatitude();
        $lng = (float)$sighting->getLongitude();

        // California edge to edge coordinates
        if ($lat >= 32.5121 && $lat <= 42.0126
            && $lng >= -114.1315 && $lng <= -124.6509
        ) {
            $score += 30;
        }

        return $score;
    }
}