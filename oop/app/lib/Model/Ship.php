<?php

namespace Model;

use Model\AbstractShip;

class Ship extends AbstractShip
{
  use SettableJediFactorTrait;

  private $underRapair;

  public function __construct($name)
  {
    parent::__construct($name);
    $this->underRapair = mt_rand(1,100) < 30;
  }

  public function isFunctional()
  {
    return !$this->underRapair;
  }
}