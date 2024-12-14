<?php

abstract class AbstractShip
{
  private $name;
  private $id;
  private $weaponPower = 0;
  private $strength = 0;
  private $team = '';

  abstract public function getJediFactor();
  
  abstract public function isFunctional();

  public function __construct($name)
  {
    $this->name = $name;
  }

  public function sayHello()
  {
    echo "Hello";
  }

  public function getName()
  {
    return $this->name;
  }

  /**
   * @return integer
   */
  public function getId()
  {
    return $this->id;
  }

  public function getWeaponPower()
  {
    return $this->weaponPower;
  }

  public function getStrength()
  {
    return $this->strength;
  }

  public function getNameAndSpecs($useShortFormat = false)
  {
    if ($useShortFormat) {      
      return sprintf(
        '%s:%s/%s/%s',
        $this->name,
        $this->weaponPower,
        $this->getJediFactor(),
        $this->strength,
      );
    } else {
      return sprintf(
      '%s: w:%s, j:%s, s:%s',
      $this->name,
      $this->weaponPower,
      $this->getJediFactor(),
      $this->strength,
      );
    }
  }

  public function getType()
  {
    return $this->team;
  }

  public function doesGivenShipHaveMoreStrength($givenShip)
  {
    return $givenShip->strength > $this->strength;
  }

  public function setName($name)
  {
    $this->name = $name;
  }

  /**
   * @param integer $id
   */
  public function setId($id)
  {
    $this->id = $id;
  }

  public function setWeaponPower($weaponPower)
  {
    $this->weaponPower = $weaponPower;
  }

  public function setStrength($strength)
  {
    if (!is_numeric($strength)) {
      throw new Exception('Invalid strength passed '.$strength);
    }
    $this->strength = $strength;
  }
  
  public function setType($team)
  {
    $this->team = $team;
  }
}