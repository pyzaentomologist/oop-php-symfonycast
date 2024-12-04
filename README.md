# Object Oriented Programming

## Nowy projekt OO Battleships of Space

Jako serwer posłuży php web server.

> php -S localhost:8005

## Klasa i Obiekt

- utworzenie play.php
- utworzenie klasy Ship
- nadanie właściwości name oraz wywołanie obiektu:

```
<?php

class Ship
{
  public $name;
}

$myShip = new Ship();
$myShip->name = "Jedi";

echo $myShip->name;
```

## Metody w klasach

Wywołanie publicznej funkcji w klasie:

```
public function sayHello()
{
  echo "Hello";
}
```

> echo $myShip->getName();

## Metody które działają

Chodziło o przekazanie argumentu do metody

```
public function getNameAndSpecs($useShortFormat)
{
  if ($useShortFormat) {      
    return sprintf(
      '%s:%s/%s/%s',
      $this->name,
      $this->weaponPower,
      $this->jediFactor,
      $this->strength,
    );
  } else {
    return sprintf(
    '%s: w:%s, j:%s, s:%s',
    $this->name,
    $this->weaponPower,
    $this->jediFactor,
    $this->strength,
    );
  }
}
echo $myShip->getNameAndSpecs(true);
```

## Wiele obiektów

Przeniesienie obsługi wyswietlenia informacji do zwykłej funkcji:

```
function printShipSummary($someShip)
{
  echo $someShip->getNameAndSpecs(true);
  echo "<hr/>";
}

printShipSummary($myShip);
```

Utwotrzenie instancji drugiego obiektu:

```
$otherShip = new Ship();
$otherShip->name = "Imperial Shuttle";
$myShip->weaponPower = 5;
$myShip->strength = 15;
```

## Wpływ obiektów na siebie

Zadeklarowanie funkcji porównującej właściwości obiektów:

```
public function doesGivenShipHaveMoreStrength($givenShip)
{
  return $givenShip->strength > $this->strength;
}
```

Użycie w warunku:

```
if ($myShip->doesGivenShipHaveMoreStrength($otherShip)) {
  echo $otherShip->name.' has more strength';
} else {
  echo $myShip->name.' has more strength';
}
```

## Typowanie parametrów funkcji

```
/** 
 * @param Ship $someShip 
*/
function printShipSummary($someShip)
{
  echo $someShip->getNameAndSpecs(true);
  echo "<hr/>";
}
```

## Użycie obiektu

- Utworzenie Ship.php z klasą Ship
- import klasy do modułów ``` require_once __DIR__.'/lib/Ship.php'```
- do właściwości klas dostajemy się przez metody ```<option value="<?php echo $key; ?>"><?php echo $ship->getNameAndSpecs(); ?></option>```

## Dostęp przez private

- **private** nie może być dostępny poza klasą
- **public** jest dostępny wszędzie
- **protected** 

Modyfikacje włąściwości powinny odbywać się przez settery, a pobieranie powinno odbywać się przez getter

Setter można zabezpieczyć:

```
public function setStrength($strength)
{
  if (!is_numeric($strength)) {
    throw new Exception('Invalid strength passed '.$strength);
  }
  $this->strength = $strength;
}
```

## Podpowiedzi dotyczące typów

Dodanie typów

> function battle(Ship $ship1, $ship1Quantity, Ship $ship2, $ship2Quantity)

## Konstruktor

Standaryzacja dodawania właściwosci

```
public function __construct()
{
  $this->underRapair = mt_rand(1,100) < 30;
}
```

Dodanie wymaganego parametru do utworzenia nowego obiektu:

```
public function __construct($name)
{
  $this->name = $name;
  $this->underRapair = mt_rand(1,100) < 30;
}
```
