<?php
try {
    $dbh = new PDO('mysql:host=127.0.0.1;dbname=pizzeria2',
    'pizzeria2',
    'x2Hkt839Dw',
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
    );
    $dbh->query('SET NAMES utf8 COLLATE utf8_hungarian_ci');
    $sql = "SELECT * FROM kommentek ORDER BY idopont";
    $stmt = $dbh->prepare($sql);

try {
        $stmt->execute();
        echo '<table>';
        echo '<thead><tr><th scope="col">Név</th><th scope="col">Komment</th><th scope="col">Időpont</th></tr></thead>';
        echo '<tbody>';
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr>';
            echo '<td>' . $row['nev'] . '</td>';
            echo '<td>' . $row['komment'] . '</td>';
            echo '<td>' . $row['idopont'] . '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger" role="alert">Error: ' . $e->getMessage() . '</div>';
    }
} catch (PDOException $e) {
    echo '<div class="alert alert-danger" role="alert">Kapcsolati hiba: ' . $e->getMessage() . '</div>';
}
?>