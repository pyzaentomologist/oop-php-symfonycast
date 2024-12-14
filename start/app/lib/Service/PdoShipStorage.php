<?php

class PdoShipStorage implements ShipStorageInterface
{
  private $pdo;

  public function __construct(PDO $pdo)
  {
    $this->pdo = $pdo;
  }

  public function fetchAllShipsData()
  {
    
    $pdo = $this->pdo;
    $statement = $pdo->prepare('SELECT * FROM ship');
    $statement->execute();
    $shipsArray = $statement->fetchAll(PDO::FETCH_ASSOC);

    return $shipsArray;
  }

  public function fetchSingleShipData($id)
  {
    $pdo = $this->pdo;
    $statement = $pdo->prepare('SELECT * FROM ship WHERE id = :id');
    $statement->execute(array('id' => $id));
    $shipArray = $statement->fetch(PDO::FETCH_ASSOC);

    if (!$shipArray) {
      return null;
    }

    return $shipArray;
  }
}