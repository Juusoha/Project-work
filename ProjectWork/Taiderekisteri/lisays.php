<?php
/**
 * PHP sisäänkirjautmis scripti
 * Käyttää PHP $_SESSION
 *
 */
//Tarkistetaan minimi vaatimus PHP-versiolle
if (version_compare(PHP_VERSION, '5.3.7', '<')) {
    exit("PHP kirjautminen vaatii vähintään version 5.3.7 !");
} else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
    //Lisätään PHP 5.5 salasanan hashing vanhempiin versioihin
    require_once("kirjastot/tarkastus_kirjasto.php");
}

?>

<html>
	<title>Ilmoitukset</title>
	<head>
		<link rel = "stylesheet" type = "text/css" href = "nakymat/Lisaystyylit.css?version=51">
		<script type="text/javascript" src="modernizr-latest.js"></script>
        <meta name=viewport content="width=device-width, initial-scale=1">
    </head>
	<body>
		<?php
			//Sisällytetään konfiguraatio/tietokanta.php)
			require_once("konfiguraatiot/tietokanta.php");
			//Ladataan lisäämis-luokka
			require_once("luokat/Lisaaminen.php");

			$lisays = new Lisaaminen();
		?>
	
		<br><br><br>
		<a class = "ilmoitukset_a" href="index.php">Takaisin päävalikkoon</a>
	</body>
</html>



