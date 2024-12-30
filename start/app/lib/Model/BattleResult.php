<?php

namespace Model;

use Model\AbstractShip;

class BattleResult implements \ArrayAccess
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

  public function offsetExists(mixed $offset): bool
  {
    return property_exists($this, $offset);
  }

  public function offsetGet(mixed $offset): mixed
  {
    return $this->$offset;
  }

  public function offsetSet(mixed $offset, mixed $value): void
  {
    $this->$offset = $value;
  }

  public function offsetUnset(mixed $offset): void
  {
    unset($this->$offset);
  }
}