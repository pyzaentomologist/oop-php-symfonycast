<?php

class BattleResult
{
  private $usedJediPowers;
  private $winningShip;
  private $losingShip;

  public function __construct($usedJediPowers, AbstractShip $winningShip, AbstractShip $losingShip) 
  {
    $this->usedJediPowers = $usedJediPowers;
    $this->winningShip = $winningShip;
    $this->losingShip = $losingShip;
  }

  /**
   * @return boolean
   */
  public function wereJediPowersUsed()
  {
    return $this->usedJediPowers;
  }

  /**
   * @return AbstractShip|null
   */
  public function getWinningShip()
  {
    return $this->winningShip;
  }

  /**
   * @return AbstractShip|null
   */
  public function getLosingShip()
  {
    return $this->losingShip;
  }
  /**
   * @return boolean
   */
  public function isThereAWinner()
  {
    return $this->getWinningShip() !== null;
  }
}