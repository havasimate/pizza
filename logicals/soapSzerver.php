<?php

class Pizzak
{

  public function getPizzaNevek()
  {

    $eredmeny = array(
      "hibakod" => 0,
      "uzenet" => "",
      "pizzak" => array()
    );

    try {
      $dbh = new PDO(
        'mysql:host=127.0.0.1;dbname=pizzeria2',
        'pizzeria2',
        'x2Hkt839Dw',
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
      );
      $dbh->query('SET NAMES utf8 COLLATE utf8_hungarian_ci');

      $sql = "SELECT nev FROM `pizza`;";
      $sth = $dbh->prepare($sql);
      $sth->execute(array());
      $eredmeny['nev'] = $sth->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      $eredmeny["hibakod"] = 1;
      $eredmeny["uzenet"] = "Adatbázis hiba: " . $e->getMessage();
    }

    return $eredmeny;
  }

  public function getPizzaAr($pizzaNev)
  {
    $eredmeny = array("hibakod" => 0, "uzenet" => "", "ar" => 0);

    try {
      $dbh = new PDO(
        'mysql:host=127.0.0.1;dbname=pizzeria2',
        'pizzeria2',
        'x2Hkt839Dw',
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
      );
      $dbh->query('SET NAMES utf8 COLLATE utf8_hungarian_ci');

      $sql = "SELECT k.ar FROM `pizza` AS p JOIN kategoria AS k ON p.kategorianev = k.nev WHERE p.nev = :pizzaNev;";
      $sth = $dbh->prepare($sql);
      $sth->execute(array(':pizzaNev' => $pizzaNev));
      $result = $sth->fetch(PDO::FETCH_ASSOC);

      if ($result) {
        $eredmeny['ar'] = $result['ar'];
      } else {
        $eredmeny['hibakod'] = 1;
        $eredmeny['uzenet'] = "A pizza ára nem található az adatbázisban.";
      }
    } catch (PDOException $e) {
      $eredmeny["hibakod"] = 1;
      $eredmeny["uzenet"] = "Adatbázis hiba: " . $e->getMessage();
    }

    return $eredmeny;
  }

  public function getPizzaRendelesek($pizzaNev)
  {
    $eredmeny = array(
      "hibakod" => 0,
      "uzenet" => "",
      "rendeles" => array()
    );

    try {
      $dbh = new PDO(
        'mysql:host=127.0.0.1;dbname=pizzeria2',
        'pizzeria2',
        'x2Hkt839Dw',
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
      );
      $dbh->query('SET NAMES utf8 COLLATE utf8_hungarian_ci');

      $sql = "SELECT darab, felvetel, kiszallitas FROM `rendeles` WHERE rendeles.pizzanev = :pizzanev;";
      $sth = $dbh->prepare($sql);
      $sth->bindParam(':pizzanev', $pizzaNev, PDO::PARAM_STR);
      $sth->execute();
      $eredmeny['rendeles'] = $sth->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      $eredmeny["hibakod"] = 1;
      $eredmeny["uzenet"] = "Adatbázis hiba: " . $e->getMessage();
    }

    return $eredmeny;
  }


  public function getPizza()
  {
    $eredmeny = array(
      "hibakod" => 0,
      "uzenet" => "",
      "pizzak" => array()
    );

    try {
      $dbh = new PDO(
        'mysql:host=127.0.0.1;dbname=pizzeria2',
        'pizzeria2',
        'x2Hkt839Dw',
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
      );
      $dbh->query('SET NAMES utf8 COLLATE utf8_hungarian_ci');

      $sql = "SELECT nev, kategorianev FROM `pizza`;";
      $sth = $dbh->prepare($sql);
      $sth->execute(array());
      $eredmeny['nev'] = $sth->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      $eredmeny["hibakod"] = 1;
      $eredmeny["uzenet"] = "Adatbázis hiba: " . $e->getMessage();
    }

    return $eredmeny;
  }

  public function getKategoria()
  {
    $eredmeny = array(
      "hibakod" => 0,
      "uzenet" => "",
      "pizzak" => array()
    );

    try {
      $dbh = new PDO(
        'mysql:host=127.0.0.1;dbname=pizzeria2',
        'pizzeria2',
        'x2Hkt839Dw',
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
      );
      $dbh->query('SET NAMES utf8 COLLATE utf8_hungarian_ci');

      $sql = "SELECT * FROM `pizza`;";
      $sth = $dbh->prepare($sql);
      $sth->execute(array());
      $eredmeny['nev'] = $sth->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      $eredmeny["hibakod"] = 1;
      $eredmeny["uzenet"] = "Adatbázis hiba: " . $e->getMessage();
    }

    return $eredmeny;
  }

  public function getRendeles()
  {
    $$eredmeny = array(
      "hibakod" => 0,
      "uzenet" => "",
      "rendeles" => array()
    );

    try {
      $dbh = new PDO(
        'mysql:host=127.0.0.1;dbname=pizzeria2',
        'pizzeria2',
        'x2Hkt839Dw',
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
      );
      $dbh->query('SET NAMES utf8 COLLATE utf8_hungarian_ci');

      $sql = "SELECT pizzanev, darab, felvetel, kiszallitas FROM `rendeles`;";
      $sth = $dbh->prepare($sql);
      $sth->execute(array());
      $eredmeny['rendeles'] = $sth->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      $eredmeny["hibakod"] = 1;
      $eredmeny["uzenet"] = "Adatbázis hiba: " . $e->getMessage();
    }

    return $eredmeny;
  }
}

$options = array(
  "uri" => "http://gorogritabead1.nhely.hu/logicals/soapSzerver.php"
);

$server = new SoapServer(null, $options);
$server->setClass('Pizzak');
$server->handle();
