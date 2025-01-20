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

## OOP (course 4): Static methods, Namespaces, Exceptions & Traits

### Piękno stałych w klasach

Stałe są potrzebne gdy:

- dane są używane wielokrotnie
- dane są używan epoza klasą
- jeśli ktoś zrobi literówkę to wynik będzie drastycznie inny?
- wszystkie możliwe wartości powinny być w jednym miejscu

### Metody statyczne

Statyczne metody są po to, żeby nie dało się zmienić tej właściwości, ani w klasie rodzica (np. abstrakcyjnej), ani w klasach potomnych.
Odwołanie do właściwości statycznych odbywają się przez słowo kluczowe self self::TYPE_NO_JEDI

### Metody statyczne czy niestatyczne?

Przykład użycia właściwości niestatycznej:

```
$battleManager = $container->getBattleManager();
$battleTypes = $battleManager->getAllBattleTypesWithDescriptions();

<?php foreach ($battleTypes as $battleType => $typeText): ?>
<option value="<?php echo $battleType ?>"><?php echo $typeText; ?></option>
<?php endforeach; ?>
```

Przykład użycia właściwości statycznej:

```
$battleTypes = BattleManager::getAllBattleTypesWithDescriptions();

<?php foreach ($battleTypes as $battleType => $typeText): ?>
<option value="<?php echo $battleType ?>"><?php echo $typeText; ?></option>
<?php endforeach; ?>
```

### Namespace

Namespace jest po to, żeby wykluczyć kolizje nazw klas z różnych bibliotek.
Deklaruje się je przez słowo kluczowe namespace, a używa sie przez use.

### Autoloading Awesomeness

Autoloadery przeważnie przyśpieszają aplikację ponieważ korzystamy tylko z używanych referencji w żądaniu.
Przypadek w którym autoloader może spowolnić aplikację to brak namespaceów. Autoloader może mieć zbyt dużo plików do przeszukania, a namespace znacząco przyśpiesza pracę.

Natywne tworzaenie autoloadera w bootstrap.php:

```
spl_autoload_register(function($className) {
  $path = __DIR__.'/lib/'.str_replace('\\', '/', $className).'.php';

  if (file_exists($path)) {
    require $path;
  }
});
```

### Komenda "use"

Nie jest potrzebny dla klas będących w tym samym namespace.

backslash służy jako separator namespaceów.

### Namespaces i podstawowe klasy PHP

Skrótem dla klas ogólnych PHP jak np. PDO jest zamieszczenie przed nazwą backslash \

### Composer autoloading

Wspólne nazewnictwo klas i plików zawierających klasy to standard PSR-0 - Część standardów PHP FIG.

PSR-4 odpowiada za standaryzacje autoloadera.

### Obsługa wyjątków

Wyjątki rzucamy za pomocą
> throw new Exception

Exception jest wewnątrzną klasą php więc wystarczy ją zadeklarować przez \Exception.

### Różne klasy Exception

Przykładem jest tu PDOException która jest rozszerzeniem klasy Exception.

### Magiczne metody: ```__toString(), __get(), __set()```

Metoda __toString() odpowiada za zwracanie jako string wybranej właściwości obiektu.

```
public function __toString()
{
  return $this->getName();
}
```

Metoda __get() służy do pobrania właściwości z obiektu

```
public function __get($propertyName)
{
  return $this->$propertyName;
}
```

Medoda __set() działa jak get, ale łamią zasady OOP i nie warto ich stosować.

### ArrayAccess: Obsługa obiektu jako tablicy

W obiekcie trzeba zaimplementować dziedziczenie po \ArratAccess oraz metody tablicowe:
offsetExists(), offsetGet(), offsetSet(), offsetUnset().

### IteratorAggregate: Pętla na obiekcie

Implementację ShipsCollection rozszerzono o IteratorAggregate

> class ShipCollection implements \ArrayAccess, \IteratorAggregate

Dzięki temu można nadać iteratory obiektom i wykonywać operacje na właściwościach obiektu jak na tablicy:

```
public function removeAllBrokenShips()
{
  foreach ($this->ships as $key => $ship) {
    if (!$ship->isFunctional()) {
      unset($this->ships[$key]);
    }
  }
}
```

### Trait: Horyzontalna reużywalność

Trait tworzy sie przy pomocy słowa kluczowego trait zamiast class.
Definiuje się właściwości które mają być dziedziczone horyzontalnie, a ich użycie nastepuje przez użycie słowa kluczowego use + nazwa traitu:

> use SettableJediFactorTrait

Jeśli modeluje się ten sam rodzaj obiektór np. planety to lepiej używać dziedziczenia klas, a jeśli mamy te same metody dla mniej spokrenionych elementów jak np. planety i meteoryty to lepiej użyć trateów.

### Kompozycja obiektów

Dodanie klasy PDOShipStorage do klasy logującej:

```
$this->shipStorage = new PdoShipStorage($this->getPDO());

// use "composition": put the PdoShipStorage inside the LoggableShipStorage
$this->shipStorage = new LoggableShipStorage($this->shipStorage);
```

Dzięki kompozycji rozszerzamy możliwości klasy docelowej, przydatne podczas używania bibliotek zewnętrznych.

## (course 5) Write SOLID Code & Impress your Friends

### SOLID - wady. zalety i reaqlne wykorzystanie

- Single-responsibility principle
- Open-closed principle
- Liskov Substitution principle
- Interface Segregation principle
- Dependency Inversion principle

Ważna dyskucja na temat wad SOLID: https://dannorth.net/cupid-the-back-story/

Dodałem kontener z php, zmodyfikowałem env oraz kontener z mysql i uruchomiłem projekt.

### Single-Responsibility Principle - czym jest

- Moduł powinienen mieć tylko jeden powód do zmiany - funkcja lub klasa powinny byćodpowiedzialne za 1 zadanie. **Zbieraj to co zmienia się z tego samego powodu i rozdzielaj to co zmienia się z innego powodu**

Dodano obsługę generowania kluczy oraz wysyłania maili z potwierdzeniem rejestracji do metody odpowiadającej za rejestrację. To złamanie zasady SRP

### SRP: Odpowiedzialności

Kod można podzielić na kilka czynności jakie wykonuje (tworzenie klucza, rejestracja użytkownika, tworzenie maila, wysłanie maila), lub na parę grup odpowiedzialności (biznesowo: tworzenie użytkownika i wysłanie maila). Przy SRP ważne jest, żeby nie przesadzić z podziałem odpowiedzialności.

### SRP: Refaktor

Odzielenie od UserManager::register() odpowiedzilności za generowanie linku z potwiedzeniem i zmiana nazwy register() na create().

### SRP: Podsumowanie

SRP łatwo zbyt skomplikować np. przez przekoszenie każdego urywka kodu do osobnej klasy. Oprócz SRP ważna jest kohazja, która mówi o tym, że logika która jest ze spobą powiazana (spójna) powinna być trzymana razem. Dobrym rozgraniczeniem jest to czy wg. mnie kod pasuje do siebie "mieści mi się w głowie".

### OCP: Open–Closed Principle

Moduł powinien być otwarty na rozszerzenia, ale zamknięty na modyfikacje (zmieniasz co może zrobić klasa, bez zmiany kodu).

Refaktoring serwisu SightingScorer.

### OCP: Autoconfiguration & tagged_iterator

SightingScorer nie jest skonfigurowany z klasami które obsługują konkretne liczniki. Problem można rozwiązać na dwa sposoby:

Ręcznie modyfikując services.yaml:

```
App\Service\SightingScorer:
        arguments:
            $scoringFactors:
                - '@App\Scoring\TitleFactor'
                - '@App\Scoring\DescriptionFactor'
                - '@App\Scoring\CoordinatesFactor'
```

Lub korzystając z tagged_iterator w src/Kernel:

```
protected function build(ContainerBuilder $container)
{
    parent::build($container);

    $container->registerForAutoconfiguration(ScoringFactorInterface::class)
        ->addTag('scoring.factor');
}
```

W services.yaml

```
App\Service\SightingScorer:
        arguments:
            $scoringFactors: !tagged_iterator scoring.factor
```

W klasie SightingScorer trzeba zmienić w konstruktorze typ parametru na iterable

### OCP: Podsumowanie

W OCP wykorzystano wzorzec wstrzykiwania tablicy lub tablicy iteracyjnej zamiast hardcodowania wszystkich metod w logice klasy.
Podobne wzorce to strategia oraz szblon.

OCP ma trzy główne wady:

- zakłada, że kod nie będzie się zmieniał ani rozwijał
- dodatkowa abstrakcja która zaciemnia kod - jak w przypadku kierowania ruchu przez services.yaml
- przestażała metoda z czasów gdy zmiany były bardzo kosztowne

Kod z OCP jest łatwiejszy do testowania, ale stosowanie tej zasady jest jak w przypadku całego SOLID "Jak Ci pasuje".

### LSP: Liskov Substitution Principle

Klasa powinna zachowywać się w sposób, jakiego oczekuje większość użytkowników: powinna zachowywać się zgodnie z zamierzeniami swojej klasy nadrzędnej lub interfejsu.

4 zasady:

- Po pierwsze: nie można zmienić typu chronionej właściwości.
- Po drugie: nie można zawęzić wskazówki dotyczącej typu argumentu. Na przykład, jeśli klasa nadrzędna używa wskazówki dotyczącej typu obiektu, nie można zawęzić tego w podklasie, wymagając czegoś bardziej szczegółowego, na przykład obiektu DateTime.
- Po trzecie: co jest zarówno podobne, jak i przeciwne do poprzedniej reguły, nie można rozszerzyć typu zwrotu. Jeśli klasa nadrzędna twierdzi, że metoda zwraca obiekt DateTime, nie można tego zmienić w podklasie, aby nagle zwrócić coś szerszego, jak dowolny obiekt.
- Po czwarte: powinieneś przestrzegać reguł swojej klasy nadrzędnej – lub interfejsu – dotyczących tego, czy pod pewnymi warunkami powinieneś zgłosić wyjątek.

### LSP: Niespodziewane wyjątki

Jeśli interfejs nie przewiduje np. rzucania błędami w interfejsie (przez dodanie komentarza), to nie powinno być tego rzacania błędem w klasach dziedziczących po tym interfejsie:

```
interface ScoringFactorInterface
{
  /**
   * Return the score the should be added to the overall score
   * 
   * This method should not throw an exception for any normal reason.
   */
  public function score(BigFootSighting $sighting): int;
}
```

Zamiast rzucenia błędem zwracam 0, zgodnie z założeniem:

```
class PhotoFactor implements ScoringFactorInterface
{
  public function score(BigFootSighting $sighting): int
  {
    if (count($sighting->getImages()) === 0) {
      return 0;
    }
    $score = 0;
    foreach ($sighting->getImages() as $image) {
      $score += rand(1, 100); // todo analyze image
    }
    return $score;
  }
}
```

### LSP: Zastępowanie klas

Zastąpienie np. w celu rozszerzenia klasy w całym projekcie:

Dodanie klasy do services.yaml:

```
App\Service\SightingScorer:
  //tu dodano
  class: App\Service\DebuggableSightingScorer
  arguments:
    $scoringFactors: !tagged_iterator scoring.factor
```

Utworzenie nowej klasy:

```
<?php
namespace App\Service;
use App\Entity\BigFootSighting;
use App\Model\BigFootSightingScore;
class DebuggableSightingScorer extends SightingScorer
{
    public function score(BigFootSighting $sighting): BigFootSightingScore
    {
        return parent::score($sighting);
    }
}
```

### LSP: Jakie zmiany *są* dopuszczalne?

Jako agryment mogę zmienić typ: BigFootSighting $sighting -> object $sighting, rozrzerzając go, odwrotnie co do wartości którą mogę wzrócić. Jeśli ma być zwracany obiekt, to mogę go zawęzić do BigFootSighting, ale nie mogę już rozszerzyć BigFootSighting do typu object.

### LSP: Podsumowanie i aliasy

Potrzebne by.ło utworzenie aliasu przekierowującego SightingScorer na DebuggableSightingScorer

```
App\Service\DebuggableSightingScorer: '@App\Service\SightingScorer'
```

### Interface Segregation Principle

Buduj małe, wyspecjalizowane klasy zamiast wielkich, wielofunkcyjnych klas.

### ISP: Refaktor i podsumowanie

Po wydzieleniu osobnego interface trzeba go zarejestrować w services.yaml i Kernel.php, żeby był iterowalny.

Trzy powody dla których ISP jest ważny:

- Nazewnictwo - podział na mniejsze kawałki kodu pozwala na bardziej opisowe nazwy klas.
- Jest czujnikiem łamania zasady SRP - Jeśli da się zauważyć, że często korzysta się tylko z jednej lub dwóch metod publicznych danej klasy, a pozostałe nie są wykorzystywane, to jest sygnał czy ISP nie zostało złamane.
- Zależności są lżejsze

### Dependency Inversion Principle

Klasy powinny zależeć od interfejsów, a nie od konkretnych klas.

Interfejsy te powinny być projektowane przez klasę, która ich używa, a nie przez klasy, które je implementują.

Czyli wysokopoziomowa logika powinna być oddzielona od niskopoziomowej. Np. kod który stosuje regex powinien być osobno od kodu który liczy ile razy dany regex został wywołany.

### DIP - refaktor

Komenda do śledzenia automatycznych powiązań:

./bin/console debug:autowiring Comment --all

Komenda pokazuje powiązania klas i interfaceów które nawiązują do "Comment"

### DIP - podsumowanie

Dzięki DIP klasa która wykorzystuje inną klasę dziedziczącą po określonym interfejsie nie jest zależna od klasy, a od interfejsu który może być dziedziczony przez dowolną klasę.

Ta zasada może być uciążliwa gdy dziedziczy się po jednym interfejsie na każdą klasę.

Dobre praktyki:

- udostępnianie kodu
- implementacja przez wiele klas

Złe praktyki:

- interfejs nie jest implementowany przez wiele klas, a tylko przez jedną - tego należy unikać

Zasady:

- SRP: Pisz klasy tak, żeby były spójne i "mieściły się w Twojej głowie"
- OCP: Projektuj klasy tak, żeby zmiana ich zachowania nie wymagała zmiany kodu
- LSP: Jeśli klasa rozszerza klasę bazową lub implementuje interfejs, niech zachowuje się tak jak została zaprojektowana
- ISP: Jeśli klasa ma wielkie interfejsy (wiele metod) i jest często używana część z nich to powinna zostać podzielona na mniejsze części
- DIP: Preferuj interfejsy wskazujące typy dla klas wysokiego poziomu, któa w rzeczywistości będzie z niego korzystać, a nie dla klas niskiego poziomu która go zaimplementuje. Ale najlepiej yużywać tego tylko gdy interfejs jest wykorzystywany przez wiele klas wyższego poziomu, inaczej mogą bezpośrednio korzystać z klas niskiego poziomu.

## (course 6) Design Patterns for Fun and Proficiency

### Wzorce projektowe i ich typy

Typy wzorców:

- Wzorce tworzące (creational patterns) pomagające tworzyć instancje obiektów:
  - Factory Pattern
  - Builder Pattern
  - Singleton Pattern
- Wzorce strukturalnie (structural patterns) pomagające organizować zależności pomiedzy obiektami np. dziedziczenie:
  - Decorator Pattern
- Wzorce zachowania (behavioral patterns) pomagające w rozwiązaniu problemów w komunikacji pomiędzy powiązanymi obiektami. Chodzi o podział na mniejsze, wyspecjalizowane klasy zamiast jednej wielkiej:
  - Stategy Pattern
  - Observer Pattern

### Wzorzec strategia

Wzorzec strategii pozwala na przepisanie części klasy z zewnątrz.

Czyli w klasie serwisu mogę w konstruktorze użyć interfejsu. Później w odpowiedniej metodzie wchodzę do obiektu z serwisu i wywołuję jego metodę.
Mogę mieć kilka klas implementujących interfejs, a serwis nie wie z której klasy będę korzystał, to zostaje rostrzygnięte gdzieś w kodzie podczas wywołania nowej instancji klasy seriwu i podanie do niego odpowiedniej klasy implementującej interfejs.

### Wzorzec strategia cz.2: Korzyści

Wzorzec strategii współgra z zasadami solid: SRP i OCP.

- W przypadku SRP umożliwia hermetyzację algorytmów w (tj. oddzielenie kodu na) oddzielne klasy. Zatem każda klasa może następnie skupić się na „jednej” odpowiedzialności. Następnie wzorzec strategii pomaga tym klasom łączyć się i współpracować.
- W przypadku OCP wzorzec strategii daje nam możliwość zmiany zachowania klasy bez konieczności modyfikowania jej kodu.

### Wzorzec "budowniczy/konstruktor"

Twórczy wzorzec projektowy, który umożliwia budowanie i konfigurowanie złożonych obiektów krok po kroku.
Wzór umożliwia tworzenie różnych typów i reprezentacji obiektu przy użyciu tego samego kodu konstrukcyjnego.

### Usprawnienia dzięki builderowi

Chodzi o to, żeby rozszerzyć możliwości klasy i nie dodawać właściwości bezpośrednio do kontruktora tylko za pomocą setterów.

```
'fighter' => new Character(90, 12, new ShieldType(), new TwoHandedSwordType()),
```

```
'fighter' => $this->createCharacterBuilder()
  ->setMaxHealth(90)
  ->setBaseDamage(12)
  ->setAttackType('sword')
  ->setArmorType('shield')
  ->buildCharacter(),
```

Dzięki temu logika przypisywania ataków lub uzbrojenia została wydzielona do osobnej metody w specjalnej klasie. Klasa GameApplication zrobiła się czystsza.

### Builder w Symfony oraz z wzorcem fabryki (Factory)

Factory jest klasą która służy do tworzenia innych klas.

W kontruktorze GameApplication dodaję Factory i metodę z Factory, która korzysta z buildera:

```

class GameApplication
{
    public function __construct(private CharacterBuilderFactory $characterBuilderFactory)
    {
    }

    private function createCharacterBuilder(): CharacterBuilder
    {
        return $this->characterBuilderFactory->createBuilder();
    }
}
```

Dzięki temu, że Factory jest wtrzykiwane w konstruktorzer GameApplication, Symfony wie, że CharacterBuilderFactory nie wymaga loggera w zależnościach od rodzica, tylko od service containera.
Zadaniem Factory jest wstrzykiwanie Loggera do Buildera:

```
class CharacterBuilderFactory
{
  public function __construct(private LoggerInterface $logger)
  {
  }
  
  public function createBuilder(): CharacterBuilder
  {
    return new CharacterBuilder($this->logger);
  }
}
```

Logger jest wymagany przez builder:

```
class CharacterBuilder
{
  public function __construct(private LoggerInterface $logger)
  {
  }
  public function buildCharacter(): Character
  {
      $this->logger->info('Creating a character!', [
          'maxHealth' => $this->maxHealth,
          'baseDamage' => $this->baseDamage,
      ]);

      $attackTypes = array_map(fn(string $attackType) => $this->createAttackType($attackType), $this->attackTypes);

      if (count($attackTypes) === 1) {
          $attackType = $attackTypes[0];
      } else {
          $attackType = new MultiAttackType($attackTypes);
      }
  
      return new Character(
          $this->maxHealth,
          $this->baseDamage,
          $this->createArmorType(),
          $attackType,
      );
  }
}
```

Factory jest samodzielnym wzorcem, który technicznie nie jest sprzężony z builderem, ale został wykorzystany, żeby pomóc rozwiązać problem z utworzeniem wielu instancji buildera i przekazać serwis (logger) do buildera.

### Wzorzec obserwatora

Wzorzec obserwatora umożliwia powiadamianie grupy obiektów przez obiekt centralny, gdy coś się wydarzy.

Wzorzec korzysta z dwóch rodzajów klas:

- Subject
- Observer

### Klasa obserwatora

Podczas starty walki rejestruje obserwatora w GameCommand i przekazuje go do GameApplication:

```
$xpObserver = new XpEarnedObserver(
    new XpCalculator()
);

$this->game->subscribe($xpObserver);
```

Metoda subsctribe dodaje obserwatora do tablicy obserwatorów i w finishFightResult() wykonuje operacje na obserwatorze notify():

```
private function finishFightResult(FightResult $fightResult, Character $winner, Character $loser): FightResult
{
    $fightResult->setWinner($winner);
    $fightResult->setLoser($loser);

    $this->notify($fightResult);

    return $fightResult;
}
private function notify(FightResult $fightResult): void
{
    foreach ($this->observers as $observer) {
        $observer->onFightFinished($fightResult);
    }
}
```

Metoda notify() wykonuje zadanie na obserwatorze, które jest zadeklarowane w klasie obserwatora XpEarnedObserver.

### Obserwator w Symfony + Korzyści

Rejestrację obserwatora można przeprowadzić na kilka sposobów:

- w kodzie wybranej metody
- w services.yaml
- w Kernel.php

Najprostsza jest rejestracja w metodzie klasy, ale obserwotor jest na stałe przytwierdzony do klasy:

```
protected function execute(InputInterface $input, OutputInterface $output): int
{
  $xpObserver = new XpEarnedObserver(
      new XpCalculator()
  );
  $this->game->subscribe($xpObserver);
}
```

Obserwatora można również zarejestrować w services.yaml, ale każdy nowy obserwator musiałby być ręcznie dodawany do tego pliku:

```
App\GameApplication:
  calls:
    - subscribe: ['@App\Observer\XpEarnedObserver']
```

Ostatnim sposobem jest automatyczna rejesracja w Kernel.php:

```
protected function build(ContainerBuilder $container)
{
  $container->registerForAutoconfiguration(GameObserverInterface::class)
    ->addTag('game.observer');
}
```

Dodanie automatycznej obsługi subscribe dla obserwatorów:

```
public function process(ContainerBuilder $container)
{
  $definition = $container->findDefinition(GameApplication::class);
  $taggedObservers = $container->findTaggedServiceIds('game.observer');
  foreach ($taggedObservers as $id => $tags) {
    $definition->addMethodCall('subscribe', [new Reference($id)]);
  }
}
```

### Publish-Subscriber (PubSub)

Wariant wzorca obserwator. Pomiędzy obiektem Subject a Observers występuje Event Dispatcher.

### Klasa PubSub Event i Subscribers w Symfony

EventListener nie potrzebuje być rozszerzanym przez jakieś klasy bazowe, ani nie potrzebuje dziedziczyć interfesju, ale klasa subscribera potrzebuje.
Gdy utworzy się klasę subscribera: *OutputFightStartingSubscriber* to implementuje *EventSubscriberInterface*.

```
class OutputFightStartingSubscriber implements EventSubscriberInterface
{
  public function onFightStart(FightStartingEvent $event)
  {
    $io = new SymfonyStyle(new ArrayInput([]), new ConsoleOutput());
    $io->note('Fight is starting against ' . $event->ai->getNickname());
  }
  
  public static function getSubscribedEvents(): array
  {
    return [
        FightStartingEvent::class => 'onFightStart',
    ];
  }
}
```

Metoda getSubscribedEvents mówi co ma się wydarzyć jeśli dojdzie do Eventu *FightStartingEvent*. W tym wypadku zostanie wywołana metoda *onFightStart*.

### Wzorzec dekorator

Wzorzec dekoratora przypomina atak typu man-in-the-middle. Zastępujesz klasę niestandardową implementacją, uruchamiasz kod, a następnie wywołujesz prawdziwą metodę.

```
$xpCalculator = new XpCalculator();
$xpCalculator = new OutputtingXpCalculator($xpCalculator);
$this->game->subscribe(new XpEarnedObserver($xpCalculator));
```

Tu użyto zasady OCP oraz współpracy z wzorcem Obserwatora (Wspólny Interface, klasa XpCalculator jest rozszerzana przez klasę OutputtingXpCalculator).

Do użycia wzorca Dekoratora jest potrzebny Interface

### Dekorator z Symfony Container

W celu automatyzacji dodawania dekoratora do kontenera symfony, trzeba dodać alias do services.yaml:

```
App\Service\XpCalculatorInterface: '@App\Service\OutputtingXpCalculator'
```

Teraz autowire działa także dla OutputtingXpCalculator, bo w jego konstruktorze jest przekazany Interface. Aby nie było pętli zastępowania samego siebie samym sioebie trzeba dodać wyjątek w services.yaml:

```
App\Service\OutputtingXpCalculator:
  arguments:
    $innerCalculator: '@App\Service\XpCalculator'
```

### Dekorator: Nadpisywanie wbudowanych usług oraz AsDecorator

Nadpisanie EventDispatchera który korzysta z interfejsu.

```
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;

#[AsDecorator('event_dispatcher')]
class DebugEventDispatcherDecorator implements EventDispatcherInterface
```

AsDecorator pozwala na przekazanie nowego, rozszerzonego dispatchera i korzystanie z oryginalnego event_dispatcher.

AsDecorator można użyć z outputtingXpCalculator dodając w services.yaml:

```
App\Service\XpCalculatorInterface: '@App\Service\XpCalculator'
```

Przez to, że usunięto starą konfigurację dekoratora, teraz można dodać dekorator przez AsDecorator:

```
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;

#[AsDecorator(XpCalculatorInterface::class)]
class OutputtingXpCalculator implements XpCalculatorInterface
```

## (course 7) Design Patterns 2

### Wstęp

Kurs zaprezentuje 5 wzorców projektowych.

3 wzorce behawioralne:

- Command
- Chain of Responsibility
- State

Te wzorce pomogą wydzielać klasy które mogą wchodzić w interakcje z innymi klasami.

2 wrzorce twórczy:

- Factory
- NullObject

### Command Pattern

Wzorzec poleceń hermetyzuje zadanie w obiekcie, oddzielając od siebie to, co robi, jak to robi i kiedy zostanie wykonane. Ułatwia także cofanie działań, ponieważ może przechowywać historię zmian.

Na wzorzec składają się 3 główne metody

- interfejs poleceń - Command Interface to metoda execute();
- komenda - Concrete commands to metody implementujące interfejs poleceń i przechowujące logikę zadania
- obiekt wywołujący - invoker object przechowuje odwołanie do polecenia i wykonuje funkcję execute();

Dodatkowe metody to:

- Odbiorca
- Klient

Te metody nie są bardzo potrzebne.

Najprostrzym przykłądem jest zmiana switch-case na obsługę klasową:

```
public function pressButton(string $button)
{
  switch ($button) {
    case 'turnOn':
      $this->powerOnDevice($this->tv->getAddress())
      $this->tv->initializeSettings();
      $this->tv->turnOnScreen();
      $this->tv->openDefaultChannel();
      break;
    case 'turnOff':
      $this->tv->closeApps();
      $this->tv->turnOffScreen();
      $this->powerOffDevice($this->tv->getAddress())
      break;
    case 'mute':
      $this->tv->toggleMute();
      break;
    ...
  }
}
```

Każdy przycisk to osobna klasa.
Implementacja metody pressButton która wywołuje metodę execute na prekazanym przycisku:

```
/**
 * @param array<string, ButtonCommandInterface> $commands
 */
public function __construct(private array $commands)
{
}

public function pressButton(string $button)
{
  if (!isset($this->commands[$button])) {
    throw new NotSupportedButtonException($button);
  }
  
  $this->commands[$button]->execute($this->tv);
}
```

Obsługa za pomocą kodu:

```
$remote = new Remote([
  'turnOn' => new TurnOnCommand(),
  'turnOff' => new TurnOffCommand(),
  'mute' => new MuteCommand(),
]);

$remote->pressButton('mute'); // executes MuteCommand logic
```

Wzorzec Command hermetyzuje żądanie jako obiekt, który oddziela metodę wywołującą od metody wywoływanej. Umożliwia także kolejkowanie i cofanie operacji poprzez przechowywanie poleceń i ich stanu.

### Dodawanie akcji do gry

Rekomendowane jest przekazywanie argumentów do konstruktora, a nie do metody execute(), by oddzielić instancję od wykonania metody.

Przekazując argumenty konstruktorowi, możesz w pewnym momencie utworzyć instancję poleceń i wykonać je później, nie martwiąc się o ich argumenty.

Nie jest to praktyczna zasada, ponieważ w zależności od aplikacji możesz nie mieć wszystkich argumentów podczas tworzenia instancji.

### Implementacja większej liczby akcji

Dodanie obsługi dla większej liczby klas:

```
$actionChoice = GameApplication::$printer->choice('Your turn', [
  'Attack',
  'Heal',
  'Surrender',
]);
$playerAction = match ($actionChoice) {
  'Attack' => new AttackCommand($player, $ai, $fightResultSet),
  'Heal' => new HealCommand($player),
  'Surrender' => new SurrenderCommand($player),
};
$playerAction->execute();
```

### Cofanie komend z akcją

Wartości do cofnięcia przechowuje się w zmiennych klasy. 
Podczas implementowania funkcji cofania kluczowe jest przechowywanie jakiegoś stanu lub danych, ponieważ system musi wiedzieć, jakie rzeczy należy cofnąć.

### Command Pattern w realnym wykorzystaniu

Zalety:

- Hermetyzuje metody w obiektach
- pozwala na logiczne rozdzielenie "czegoś" od "jak" i "kiedy",
- obsługuje cofanie
- wspiera SRP i OCP

Wady:

- Zwiększona złożoność kodu
- każde polecenie to nowa klasa

### Chain of Responsibility Pattern

Łańcuch odpowiedzialności to sposób na ustawienie sekwencji metod do wykonania, przy czym każda metoda może zdecydować o wykonaniu kolejnej w łańcuchu lub całkowitym zatrzymaniu sekwencji.

Łańcuch odpowiedzialności dzieli sie na trzy części:

- Handler interface:
  - Zawiera dwie metody (setNext() i handle())
- Concrete handlers
  - implementuje handler interface
- Client
  - dodaje łańcuch, zatwierdza kolejność działania i wywołuje pierwszy handler

Łańcuch odpowiedzialności służy do tworzenia sekwencji procedur obsługi, z których każda może albo przetworzyć żądanie, przekazać je do następnej procedury obsługi, albo zatrzymać sekwencję.

### Wywołanie łańcucha odpowiedzialności

W konstruktorze utworzono kolejność sprawdzanych warunków:

```
public function __construct(private readonly CharacterBuilder $characterBuilder)
{
  $this->difficultyContext = new GameDifficultyContext();

  $casinoHandler = new CasinoHandler();
  $levelHandler = new LevelHandler();
  $onFireHandler = new OnFireHandler();

  $casinoHandler->setNext($levelHandler);
  $levelHandler->setNext($onFireHandler);

  $this->xpBonusHandler = $casinoHandler;
}
```

### Konfiguracja CoR w Symfony

Konfiguracja odbywa sie za pomocą atrybutu autoconfigure w klasach któe mają być ogniwami łańcucha (oprócz ostatniego ogniwa):

> #[Autoconfigure()]

```
#[Autoconfigure(
  calls: [['setNext' => ['@'.OnFireHandler::class]]]
)]
```

Wywołanie łańcucha odbywa sie przez atrybut autowire w kontruktorze:

> #[Autowire(service: CasinoHandler::class)]

```
public function __construct(
  #[Autowire(service: CasinoHandler::class)]
  private readonly XpBonusHandlerInterface $xpBonusHandler,
  private readonly CharacterBuilder $characterBuilder
) {
  $this->difficultyContext = new GameDifficultyContext();
}
```

#### Null Object Pattern

Dodatkowym wzorcem jest zwracanie wartości jak najbardzie zbliżonej do nulla dla stringa to będzie pusty string, dla int to będzie 0 itd.

Tworzy sie klasę która zwróci np. 0 jeśli jakaś akcja nie przyniesie zysku dla gracza w postaci doświadczenia.

Atrybut #[Autoconfigure] PHP w Symfony pomaga w automatycznej konfiguracji usług. Może definiować wywołania metod, znaczniki i inne elementy bez ręcznej konfiguracji w kontenerze usług.

Wzorzec Null Object jest doskonałym narzędziem do usuwania kontroli zerowych. Zamiast sprawdzać, czy właściwość jest ustawiona, możesz początkowo ustawić ją na fikcyjną implementację interfejsu i wywoływać na niej metody, tak jakby był to prawdziwy obiekt.

### Kuzyn wzorca Chain of Responsibility - wzorzec *Middleware*

Krótko mówiąc, wszystkie Middleware zostaną wykonane, a ich użycie to świetny sposób na zwiększenie elastyczności aplikacji.
Mogą być użyte np. do nadania postaci losowej broni, zwiększenia jej poziomu, zwiększenia jej zdrowia.

Zalety CoR:

- umożliwia dynamiczne dodawanie lub usuwanie handlerów poprzez zmianę członków lub kolejności łańcucha.
- wykorzystuje również zasadę pojedynczej odpowiedzialności, która pozwala nam oddzielić klasy, które działają, od klas, które z nich korzystają.
- wykorzystuje zasadę Otwarty/Zamknięty. Dzięki temu możemy wprowadzać nowe moduły obsługi do aplikacji bez dotykania istniejącego kodu.

Wady:

- ciężki kod do debugowania
- rządania mogą być nieprzetworzone, jeśli nie będzie obiektów do obsługi
- jeśli łańcuch jest zbyt długi, będzie to miało negatywny pływ na wydajność

Middleware wykonuje wszystkie procedury obsługi, podczas gdy CoR może się zatrzymać, gdy procedura obsługi przetworzy żądanie i nie wywoła następnego.

### The State Pattern

Stan to sposób zorganizowania kodu w taki sposób, aby obiekt mógł zmienić swoje zachowanie, gdy zmieni się jego stan wewnętrzny. Pomaga reprezentować różne stany jako osobne klasy i pozwala obiektowi płynnie przełączać się między tymi stanami.

Wzorzec składa się z 3 elementów:

- klasa kontekstowa reprezentująca obiekt, którego zachowanie zmienia się w zależności od jego stanu wewnętrznego, i zawiera odwołanie do obiektu bieżącego stanu.
- wspólny interfejs dla wszystkich konkretnych klas stanów. Deklaruje metody reprezentujące akcje, które można podjąć w każdym stanie.
- konkretne stany, gdzie każda klasa reprezentuje stan kontekstu.

Zmiana kodu z:

```
public function publishPost(Article $article) {
  if ($article->getStatus() === 'draft') {
    $article->setStatus('moderation');
    $this->notifyModerator();
  } elseif ($article->getStatus() === 'moderation') {
    if ($this->getCurrentUser()->isAdmin()) {
      $article->setStatus('published');
    } 
    $this->sendTweet($article);
  } elseif ($article->getStatus() === 'published') {
    throw new AlreadyPublishedException();
  }
}
```

na obsługę dla poszczególnych klas:

```
class DraftState implements StateInterface {
  public function publish(Article $article) {
    $article->setStatus('moderation');
    $this->notifyModerator();
  }
}
```

### Radzenie sobie z trudnościami związanymi ze wzorcem State

Co jeśli miałyby jakieś zależności?
Co, jeśli ich stworzenie było drogie?

Jeśli stany są proste do utworzenia to wzorzec można zaimplementować niskim kosztem.
Jeśli stany potrzebują rozbudowanej logiki, udziału zewnętrznych zależności to lepiej użyć atrybutu AutowireLocation.
Jeśli za każdym razem potrzebujesz świeżego obiektu stanu, użyj fabryki, aby go utworzyć i wstrzyknij go do GameDifficultyContext.

### State w realnym świecie

Symfony ma wbudowany komponent Symfony Workflow, obsługiwany przez plik yaml.
Wzorce stanu i strategii są ze sobą powiązane, ale główną różnicą jest cel jaki za nimi stoi. Porównując do działania do auta stan monitoruje czy pedały są wciśnięte, czy silnik działa, a strategia monitoruje czy jedziemy na silniku spalinowym, czy elektrycznym.

Główna różnica strukturalna polega na tym, że obiekty stanu mogą mieć odniesienia do innych stanów. Umożliwia to obiektowi przejście między stanami. Natomiast Strategia skupia się na wyborze konkretnego algorytmu lub zachowania, bez zmiany stanu systemu.

Zalety stanu:

- wzorzec Stan pozwala obiektom na zmianę zachowania, gdy zmienia się ich stan wewnętrzny, a nawet może ukryć stan, w jakim znajduje się obiekt.
- jest to także świetny sposób na uniknięcie używania dużych bloków if-else.
- wykorzystuje SRP i OCP

Wady:

- w prostych przypadkach może to być przesada
- może wprowadzić znaczną liczbę klas.

### Factory Pattern

Fabryka jest wzorcem twórczym (creational pattern). Zamiast bezpośrednio tworzyć instancje obiektów za pomocą new, używamy fabryki, która decyduje, który obiekt utworzyć na podstawie danych wejściowych.

Wzór Factory składa się z pięciu części:

- interfejs produktów, które chcemy stworzyć. Gdybyśmy na przykład chcieli stworzyć broń dla naszych postaci, mielibyśmy interfejs broni, a produktami byłaby broń.
- konkretne produkty implementujące interfejs. W tym przykładzie mielibyśmy klasy takie jak Sword, Axe, Bow i tak dalej.
- interfejs fabryczny. Jest to opcjonalne, ale bardzo przydatne, gdy trzeba utworzyć rodziny produktów.
- konkretna fabryka, która wdraża interfejs fabryczny, jeśli taki posiadamy. Ta klasa wie wszystko o tworzeniu produktów.
- klient, który wykorzystuje fabrykę do tworzenia obiektów produktowych. Ta klasa wie tylko, jak używać produktów, ale nie wie, w jaki sposób są one tworzone ani jakiego konkretnego produktu używa.

Istnieje więcej niż jeden wariant wzorca Fabryka. Najprostszym wariantem jest fabryka z wieloma metodami make() – po jednej dla każdego możliwego produktu. Ta fabryka wyglądałaby tak:

```
class WeaponFactory
{
  public function makeSword(): WeaponInterface
  {
    return new Sword(Dice::rollRange(4, 8), 12);
  }

  public function makeAxe(int $bonusDamage = 0): WeaponInterface
  {
    return new Axe($bonusDamage + Dice::rollRange(6, 12), 8);
  }
}
```

Innym podejściem jest użycie metody pojedynczego tworzenia. To otrzyma argument i określi, który obiekt musi utworzyć. Jest to przydatne, gdy aplikacja jest bardziej dynamiczna. Wartość $type może pochodzić z danych wejściowych użytkownika, żądania lub czegoś innego.
Jednakże istnieje kilka wad tego podejścia, takich jak utrata bezpieczeństwa typu, ponieważ jako typ można wysłać dowolny ciąg znaków. Na szczęście można to rozwiązać za pomocą dobrego zestawu testów lub przekształcając ciąg znaków w *enum*. Trudno jest również mieć różne argumenty konstruktora dla każdego typu.

Ostatnim wariantem, o którym będziemy mówić, jest „Fabryka Abstrakcyjna”. W tym podejściu mamy wiele fabryk wdrażających ten sam interfejs, a każda betonowa fabryka tworzy rodzinę obiektów. W naszym przykładzie broni postaci moglibyśmy pogrupować broń na podstawie materiału, z którego jest wykonana, np. żelaza lub stali, a każda fabryka produkowałaby broń tylko z tego materiału.

Wzorzec Fabryka jest wzorcem twórczym, który obejmuje proces tworzenia obiektów. Ukrywa szczegóły tworzenia przed klientem i zapewnia scentralizowany sposób tworzenia instancji obiektów.

Fabryka z wieloma metodami make jest łatwiejsza w użyciu i pozwala na posiadanie różnych argumentów konstruktora w każdym wariancie. Jednak dużym minusem jest to, że klient musi wiedzieć, jaki przedmiot jest wymagany do wykonania pracy:

```
class WeaponFactory
{
  public function makeSword(): WeaponInterface
  {
    return new Sword(Dice::rollRange(4, 8), 12);
  }

  public function makeAxe(int $bonusDamage = 0): WeaponInterface
  {
    return new Axe($bonusDamage + Dice::rollRange(6, 12), 8);
  }
}
```

### The Abstract Factory Pattern

Dzięki atrybutowa #[AsAlias()] można podać do symfony informację którą fabrykę chcemy wstrzyknąć jako domyślną.
W CharacterBuilder w konstruktorze podaję jako argument klasę na bazie interfejsu:

```
class CharacterBuilder
{
  private int $maxHealth;
  private int $baseDamage;
  private array $attackTypes;
  private string $armorType;
  private int $level;

  public function __construct(private AttackTypeFactoryInterface $attackTypeFactory)
  {
  }
}
```

Symfony nie wie o którą klasę chodzi, a są dwie: UltimateAttackTypeFactory oraz AttackTypeFactory, dlatego mogę ją automatycznie zadeklarować w AsAlias():

Abstrakcyjny wzorzec fabryki umożliwia zarządzanie rodzinami powiązanych obiektów (AttackType). W tym przypadku gra ma dwie fabryki, AttackTypeFactory i UltimateAttackTypeFactory, które można zamienić w czasie wykonywania, aby zapewnić różne typy obiektów AttackType bez zauważania tego przez klienta.

```
#[AsAlias(AttackTypeFactoryInterface::class)]
class AttackTypeFactory implements AttackTypeFactoryInterface
```

### Factory Pattern w realnym swiecie

[Dokumentacja symfony - Factories](https://symfony.com/doc/current/service_container/factories.html "Dokumentacja symfony - Factories")

Zalety fabryki:

- to świetny sposób na oddzielenie kodu tworzącego obiekty od kodu, który je wykorzystuje.
- bardzo pomocne, gdy trzeba utworzyć różne rodziny obiektów.
- wykorzystuje zasady SRP i OCP.

Wady:

- może to jednak sprawić, że baza kodu stanie się bardziej złożona, zwłaszcza jeśli masz wiele fabryk.

Symfony pozwala na zdefiniowanie fabryk poprzez opcję fabryki w definicji usługi. Pierwszy argument to klasa fabryczna, która zostanie utworzona (lub użyta bezpośrednio, jeśli jest to fabryka statyczna) przez Symfony i wywoła metodę określoną w drugim argumencie. Oto przykład statycznej deklaracji fabryki:

```
# config/services.yaml
services:
  App\Ecommerce\PaymentProcessor:
    factory: ['App\Ecommerce\PaymentProcessorStaticFactory', 'createPaymentProcessor']
```
