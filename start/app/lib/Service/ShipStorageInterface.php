<?php

interface ShipStorageInterface
{
  /**
   * Returns an array of ship arrays, with keys id, name, weaponPower, defense.
   * 
   * @return array
   */
  public function fetchAllShipsData();

  /**
   * @param integer $id
   * @return array()
   */
  public function fetchSingleShipData($id);
}