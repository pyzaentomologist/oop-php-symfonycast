<?php

namespace Model;

use Traversable;

class ShipCollection implements \ArrayAccess, \IteratorAggregate
{
  /**
   * @var AbstractShip[]
  */
  private $ships;

  public function __construct(array $ships)
  {
    $this->ships = $ships;
  }

  public function offsetExists(mixed $offset): bool
  {
    return array_key_exists($offset, $this->ships);
  }

  public function offsetGet(mixed $offset): mixed
  {
    return $this->ships[$offset];
  }

  public function offsetSet(mixed $offset, mixed $value): void
  {
    $this->ships[$offset] = $value;
  }

  public function offsetUnset(mixed $offset): void
  {
    unset($this->$offset);
  }
  
  public function getIterator(): Traversable
  {
    return new \ArrayIterator($this->ships);
  }
  
  public function removeAllBrokenShips()
  {
    foreach ($this->ships as $key => $ship) {
      if (!$ship->isFunctional()) {
        unset($this->ships[$key]);
      }
    }
  }

}