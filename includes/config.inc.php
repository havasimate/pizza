<?php
$ablakcim = array(
    'cim' => 'Carlitos Pizza.',
);

$MAPPA = "../images/";
$TIPUSOK = array ('.jpg', '.png');
$MEDIATIPUSOK = array('image/jpeg', 'image/png');
$DATUMFORMA = "Y.m.d. H:i";
$MAXMERET = 500*1024;

$fejlec = array(
	'motto' => 'Carlito Pizza'
);

$lablec = array(
    'copyright' => 'Copyright '.date("Y").'.',
    'ceg' => 'Pizza dos Carlito Kft.'
);

$oldalak = array(
	'/' => array('fajl' => 'cimlap', 'szoveg' => 'Címlap', 'menun' => array(1,1)),
    'belepes' => array('fajl' => 'belepes', 'szoveg' => 'Belépés', 'menun' => array(1,0)),
    'kilepes' => array('fajl' => 'kilepes', 'szoveg' => 'Kilépés', 'menun' => array(0,1)),
    'belep' => array('fajl' => 'belep', 'szoveg' => '', 'menun' => array(0,0)),
    'regisztral' => array('fajl' => 'regisztral', 'szoveg' => '', 'menun' => array(0,0)),
    'hozzaadas' => array('fajl' => 'hozzaadas', 'szoveg' => 'Hozzáadás', 'menun' => array(0,1)),
    'kommentek' => array('fajl' => 'kommentek', 'szoveg' => 'Kommentek', 'menun' => array(0,1)),
    'hirek' => array('fajl' => 'hirek', 'szoveg' => 'Hírek', 'menun' => array(0,1)),
    'pizzak' => array('fajl' => 'pizzak', 'szoveg' => 'Pizzák', 'menun' => array(0,1)),
    'devizaPar' => array('fajl' => 'devizaPar', 'szoveg' => 'Devizák', 'menun' => array(1,1)),
    'devizaGrafikon' => array('fajl' => 'devizaGrafikon', 'szoveg' => 'Grafikon', 'menun' => array(1,1))


);

$hiba_oldal = array ('fajl' => '404', 'szoveg' => 'A keresett oldal nem található!');
?>