<?php
/**
 * PHP sis��nkirjautmis scripti
 *
 * K�ytt�� PHP $_SESSION
 *
 *

 * @var  $login
 *
 */
//Tarkistetaan minimi vaatimus PHP-versiolle
if (version_compare(PHP_VERSION, '5.3.7', '<')) {
    exit("PHP kirjautminen vaatii v�hint��n version 5.3.7 !");
} else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
    //Lis�t��n PHP 5.5 salasanan hashing vanhempiin versioihin
    require_once("kirjastot/tarkastus_kirjasto.php");
}
require_once("luokat/Haku.php");
//Luodaan ilmentym� haku-oliosta
//K�sittelee hakuprosessin

//Sis�llytet��n konfiguraatio/tietokanta.php)
require_once("konfiguraatiot/tietokanta.php");
//Ladataan sis��nkirjautumis-luokka
require_once("luokat/Kirjaudu.php");
//Luodaan ilmentym� sis��nkirjautmis oliosta
//K�sittelee sis��nkirjautumisprosessin
$login = new Kirjaudu();
//Tarkistetaan onko k�ytt�j� kirjautunut sis��n vai ei
if ($login->onKirjautunut() == true) {
    //K�ytt�j� on kirjautunutn sis��n
    //N�ytet��n sis��nkirjautumis-ikkuna
    include("nakymat/kirjautunut.php");
} else {
    // K�ytt�j� ei ole kirjautunut
    // P��sivu erill��n, n�ytet��n uloskirjauneen k�ytt�j�n ikkuna
    include("nakymat/ei_kirjautunut.php");
}
//$hakeminen = new Haku();