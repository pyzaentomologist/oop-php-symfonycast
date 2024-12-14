# Object Oriented Programming

## Object Oriented Programming (Course 1)

### Nowy projekt OO Battleships of Space

Jako serwer posłuży php web server.

> php -S localhost:8005

### Klasa i Obiekt

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

### Metody w klasach

Wywołanie publicznej funkcji w klasie:

```
public function sayHello()
{
  echo "Hello";
}
```

> echo $myShip->getName();

### Metody które działają

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

### Wiele obiektów

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

### Wpływ obiektów na siebie

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

### Typowanie parametrów funkcji

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

### Użycie obiektu

- Utworzenie Ship.php z klasą Ship
- import klasy do modułów ``` require_once __DIR__.'/lib/Ship.php'```
- do właściwości klas dostajemy się przez metody ```<option value="<?php echo $key; ?>"><?php echo $ship->getNameAndSpecs(); ?></option>```

### Dostęp przez private

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

### Podpowiedzi dotyczące typów

Dodanie typów

> function battle(Ship $ship1, $ship1Quantity, Ship $ship2, $ship2Quantity)

### Konstruktor

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

## OOP (Course 2): Services, Dependency Injection and Containers

### Klasa jako usługa

Zadeklarowałem użycie w require_once:

```require_once __DIR__.'/lib/BattleManager.php';```

Mogę użyć klasy gdy:

- Mam do przechowania dane jak wzorzez statku
- Gdy mam jakąś funkcję np. BattleManager

### Zastąpienie funkcji klasami

### Udoskonalenie wyniku bitwy przy użyciu klas

Utworzenie klasy BattleResult do obsługi wyniku walk, zmiana typu danych w BattleManagerze

### Opcjonalne typowanie i metody semantyczne

Dodanie null do typów metod obsługujących walkę:

```
/**
 * @return Ship|null
 */
public function getWinningShip()
{
  return $this->winningShip;
}
/**
 * @return Ship|null
 */
public function getLosingShip()
{
  return $this->losingShip;
}
```

oraz dodanie metody sprawdzajacej czy jest remis:

```
/**
 * @return boolean
 */
public function isThereAWinner()
{
  return $this->getWinningShip() !== null;
}
```

### Przekazywanie obiektów jako referencji

Użycie setterów i getterów do wyświetlania informacji o pozostałej mocy statków po walce

### Fetchowanie obiektów z bazy

Przy pomocy chatGPT dodałem docker-compose i DOckerfile do osługi prostej bazy mysql.
Połączenie z bazą odbywało się za pomocą PDO. Połączenie z bazą jest w pliku init_db.php - wstrzykuje początkowe wartości do nowej bazy.

Połaczenie z bazą:

```
private function queryForShips()
{
  $pdo = new PDO("mysql:host=mysql;dbname=oo_battle", 'root');
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $statement = $pdo->prepare('SELECT * FROM ship');
  $statement->execute();
  $shipsArray = $statement->fetchAll(PDO::FETCH_ASSOC);

  return $shipsArray;
}
```

### Obsługa ID obiektu

> $ship->setId($shipData['id']);

```
/**
 * @return integer
 */
public function getId()
{
  return $this->id;
}
/**
 * @param integer $id
 */
public function setId($id)
{
  $this->id = $id;
}
```

Obsługa zapytań o pojedynczy statek, po jego Id:

```
private function createShipFromData(array $shipData)
{
    $ship = new Ship($shipData['name']);
    $ship->setId($shipData['id']);
    $ship->setWeaponPower($shipData['weapon_power']);
    $ship->setJediFactor($shipData['jedi_factor']);
    $ship->setStrength($shipData['strength']);
    return $ship;
}
private function queryForShips()
{
  $pdo = new PDO("mysql:host=mysql;dbname=oo_battle", 'root');
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $statement = $pdo->prepare('SELECT * FROM ship');
  $statement->execute();
  $shipsArray = $statement->fetchAll(PDO::FETCH_ASSOC);
  return $shipsArray;
}
```

Zmiana rozpoznawania po wstrzykniętej nazwie na shipId:

```
$ship1Name = isset($_POST['ship1_name']) ? $_POST['ship1_name'] : null;
$ship1Quantity = isset($_POST['ship1_quantity']) ? $_POST['ship1_quantity'] : 1;
$ship2Name = isset($_POST['ship2_name']) ? $_POST['ship2_name'] : null;
$ship2Quantity = isset($_POST['ship2_quantity']) ? $_POST['ship2_quantity'] : 1;

if (!$ship1Name || !$ship2Name) {
    header('Location: /index.php?error=missing_data');
    die;
}

if (!isset($ships[$ship1Name]) || !isset($ships[$ship2Name])) {
    header('Location: /index.php?error=bad_ships');
    die;
}

if ($ship1Quantity <= 0 || $ship2Quantity <= 0) {
    header('Location: /index.php?error=bad_quantities');
    die;
}

$ship1 = $ships[$ship1Name];
$ship2 = $ships[$ship2Name];
```

Po poprawie:

```
$ship1Id = isset($_POST['ship1_id']) ? $_POST['ship1_id'] : null;
$ship1Quantity = isset($_POST['ship1_quantity']) ? $_POST['ship1_quantity'] : 1;
$ship2Id = isset($_POST['ship2_id']) ? $_POST['ship2_id'] : null;
$ship2Quantity = isset($_POST['ship2_quantity']) ? $_POST['ship2_quantity'] : 1;

if (!$ship1Id || !$ship2Id) {
    header('Location: /index.php?error=missing_data');
    die;
}

$ship1 = $shipLoader->findOneById($ship1Id);
$ship2 = $shipLoader->findOneById($ship2Id);

if (!$ship1 || !$ship2) {
    header('Location: /index.php?error=bad_ships');
    die;
}

if ($ship1Quantity <= 0 || $ship2Quantity <= 0) {
    header('Location: /index.php?error=bad_quantities');
    die;
}
```

### Utworzenia jednego połączenia z bazą dla właściwości

Wydzielenie funkcji do łączenia z PDO:

```
/**
 * @return PDO
 */
private function getPDO()
{
  // Korzystam z istniejącego już połaczenia, nie inicjuje nowych
  if ($this->pdo === null) {
    $pdo = new PDO("mysql:host=mysql;dbname=oo_battle", 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $this->pdo = $pdo;
  }
  return $this->pdo;
}
```

### Dobre praktyki: Centralizacja konfiguracji

Przekazywanie danych logowania do bazy przez argumenty (dependency injection), a przechowywanie w bootstrap.php (Czy nie lepiej .env?).
Dependency injection to wstrzykiwanie zależności do kontruktora lub przekazywanie wartości przez setter.

### Dobre praktyki: Centralizacja połaczenia z bazą

Zamiast przekazania konfiguracji połączenia do klasy Loadora przekazano do nowego obiektu PDO

```
$pdo = new PDO($configuration['db_dsn'],$configuration['db_user'],$configuration['db_pass']);

$shipLoader = new ShipLoader($pdo);
```

### Service container

Utworzenie kontenera dla obiektów serwisów

Kontener przechowuje uderzenia do PDO, przez niego idą wywołania PDO dzięki konfiguracji z bootstrap.php

### Kontener: Utworzenie pojedynczego obiektu PDO

Obsługa utwortzenia ShipLoadera i BattleManagera przez Container:

```
/**
 * @return ShipLoader
 */
public function getShipLoader()
{
  if ($this->shipLoader === null) {
    $this->shipLoader = new ShipLoader($this->getPDO());
  }
  return $this->shipLoader;
}

/**
 * @return BattleManager
 */
public function getBattleManager()
{
  if ($this->battleManager === null) {
    $this->battleManager = new BattleManager($this->getPDO());
  }
  return $this->battleManager;
}
```

### Kontener jako ratunek

Klasy które zarządzają kodem, ale nie przechowują danych (PDO, BattleManager, ShipLoader) powinny być przechowywane w scentralizowanym miejscu (Service).
Model odpowiada za przechowywanie danych.

## OOP (Course 3): Inheritance, Abstract Classes, Interfaces and other amazing things

### Rozszerzanie

Rozszerzanie klasy przez dodanie słowa kluczowego extends i nazwy klasy:

```
class RebelShip extends Ship
```

### Nadpisywanie

RebelShip może nadpisać właściwości które dziedziczy po klasie Ship np. isFunctional zawsze moze być true.

### Widoczność właściwości protected

Protected umożliwia dostęp do właściwości klasy głównej, po której dziedziczą inne klasy. Jest połaczeniem public i privete. 
Publiczna dla dzieci, prywatna dla kodu poza nimi.
Lepszym rozwiązaniem niż używanie protected jest korzystanie z getterów i setterów.

### Wywoływanie metod rodzica

Wywołujemy przez użycie **parent::** zamiast **$this**

```
public function getNameAndSpecs($useShortFormat = false)
{
  $val = parent::getNameAndSpecs($useShortFormat);
  $val .= ' (Rebel)';
  
  return $val;
}
```

Podczas nadpisywania metody rodzica, metoda rodzica nigdy nie jest wykonywana. Wykonywana jest tylko metoda potomka, która bazuje na kodzie metody rodzica.

### Tworzenie abstrakcyjnego okrętu (abstract)

Klasa abstrakcyjna w tym kontekście ma pomóc w utworzeniu wzorca klasy, który będzie dziedziczony przez inne klasy.

### Klasa abstrakcyjna

Wstrzykiwanie abstrakcyjnych metod - takich któe muszą znaleźć się w klasach potomnych:
```
abstract public function getJediFactor();
  
abstract public function isFunctional();
```

Metody abstrakcyjne muszą znaleźć się w abstrakcyjnej klasie

```
abstract class AbstractShip
```

### Zniszczony statek

Utworzenie nowej klasy która musi mieć metody zdefiniowane jako abstrakcyjne w klasie abstrakcyjnej po której dziedziczy.

### Wydzielenie odpowiedzialności w klasie

Przeniesienie odpytywanie PDO do osobnej klasy, przez co ShipLoader może przyjmować dane zarówno z bazy jak np. z pliku json.
Kod stał się reużywalny i przejrzysty.

### AbstractShipStorage

Utworzenie klasy AbstractShipStorage w celu nadania wzoru klasom potomnym, które maja za zadanie pobierać dane, czy to z pliku, czy z bazy, jednak lepsze do tego celu będą Interfejsy.

### Interface

Zamiast słowa kluczowego extends używa sie implements. Wszystkie metody interfejsów są abstrakcyjne, nie zawierają żadnej logiki, tylko wartości abstrakcyjne.
Dziedziczenie po wielu interfejsach jest po przecinku: 

```
class PsoShipStorage implements ShipStorageInterface, ArrayAccess
```

Jeśli klasa ma dziedziczyć po klasie abstrakcyjnej (zawsze max po jednej) i po interfejscach to skłądnia wygląda następująco:

```
class RebelShip extends AbstractShip implements ShipInterface, WeaponShipInterface
{
}
```
