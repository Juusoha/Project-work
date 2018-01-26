<?php
/**
 * PHP sis��nkirjautmis scripti
 *
 * K�ytt�� PHP $_SESSION
 *
 */
//Tarkistetaan minimi vaatimus PHP-versiolle
if (version_compare(PHP_VERSION, '5.3.7', '<')) {
    exit("PHP kirjautminen vaatii v�hint��n version 5.3.7 !");
} else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
    //Lis�t��n PHP 5.5 salasanan hashing vanhempiin versioihin
    require_once("kirjastot/tarkastus_kirjasto.php");
}
//Sis�llytet��n konfiguraatio/tietokanta.php)
require_once("konfiguraatiot/tietokanta.php");
//Ladataan muokkaus-luokka
require_once("luokat/Muokkaus.php");
//Luodaan ilmentym� muokkaus-oliosta
//K�sittelee muokkausprosessin
include("nakymat/muokkaa_tietoja.php");

$muokkaus = new Muokkaus();
?>