<?php session_start(); ?>
<?php
$hibak = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $nev = $_SESSION['login'];
  $hir = $_POST["hir"];


  if (empty($hir)) {
    echo "A hír nem lehet üres";
  } elseif (strlen($hir) > 300) {
    echo "A hír nem lehet hosszabb 300 karakternél";
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
    $sql = "INSERT INTO hirek (nev, hir) VALUES (:nev, :hir)";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':nev', $nev);
    $stmt->bindParam(':hir', $hir);

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
