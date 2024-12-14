<?php

class Ship extends AbstractShip
{
  private $jediFactor = 0;

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

  public function getJediFactor()
  {
    return $this->jediFactor;
  }

  public function setJediFactor($jediFactor)
  {
    $this->jediFactor = $jediFactor;
  }
}