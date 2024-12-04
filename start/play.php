<?php

require_once __DIR__.'/lib/Ship.php';

/** 
 * @param Ship $someShip 
*/

function printShipSummary($someShip)
{
  echo $someShip->getNameAndSpecs(true);
  echo "<hr/>";
}

$myShip = new Ship("Jedi");
$myShip->setWeaponPower(10);
$myShip->setStrength(5);

$otherShip = new Ship("Imperial Shuttle");
$otherShip->setWeaponPower(5);
$otherShip->setStrength(50);
// echo $myShip->getName();
// echo $myShip->weaponPower;
printShipSummary($myShip);
printShipSummary($otherShip);

if ($myShip->doesGivenShipHaveMoreStrength($otherShip)) {
  echo $otherShip->getName().' has more strength';
} else {
  echo $myShip->getName().' has more strength';
}