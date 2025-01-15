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
