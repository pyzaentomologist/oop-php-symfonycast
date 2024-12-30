<?php

namespace Model;

use Model\AbstractShip;

class RebelShip extends AbstractShip
{
  public function getFavouriteJedi()
  {
    $coolJedis = array('Yoda', 'Ben Kenobi');
    $key = array_rand($coolJedis);
    return $coolJedis[$key];
  }
  
  public function isFunctional()
  {
    return true;
  }
  
  public function getNameAndSpecs($useShortFormat = false)
  {
    $val = parent::getNameAndSpecs($useShortFormat);
    $val .= ' (Rebel)';
    
    return $val;
  }

  public function getJediFactor()
  {
    return rand(10, 30);
  }
}