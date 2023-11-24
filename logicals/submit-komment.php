<?php session_start(); ?>
<?php
$hibak = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $nev = $_SESSION['login'];
  $komment = $_POST["komment"];


  if (empty($komment)) {
    echo "A komment nem lehet üres";
  } elseif (strlen($komment) > 300) {
    echo "A komment nem lehet hosszabb 300 karakternél";
    $hibak++;
  }
}

if ($hibak == 0) {
  try {
    $dbh = new PDO('mysql:host=127.0.0.1;dbname=pizzeria2',
    'pizzeria2',
    'x2Hkt839Dw',
      array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
    );
    $dbh->query('SET NAMES utf8 COLLATE utf8_hungarian_ci');
    $sql = "INSERT INTO kommentek (nev, komment) VALUES (:nev, :komment)";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':nev', $nev);
    $stmt->bindParam(':komment', $komment);

    try {
      $stmt->execute();
      echo "Sikeres hozzaadas!";
    } catch (PDOException $e) {
      echo "Error: " . $e->getMessage();
    }
  } catch (PDOException $e) {
    echo "Kapcsolódási probléma: " . $e->getMessage();
  }
}
