<?php

class Rekisteroityminen
{
    /**
     * @var object $tietokanta_yhteys 
     */
    private $tietokanta_yhteys = null;
    /**
     * @var array $virheet Virhe ilmoitukset
     */
    public $virheet = array();
    /**
     * @var array $ilmoitukset 
     */
    public $ilmoitukset = array();
    /**
     * Funktio "__construct()" automaattisesti aloittaa toiminnan kun t�m�n luokan olio on luotu,
     * "$registration = new Registration();"
     */
    public function __construct()
    {
        if (isset($_POST["register"])) {
            $this->rekisteroi_uusi_kayttaja();
        }
    }
    /**
     * K�sittelee ja toteuttaa rekister�inti prosessin. Tarkastaa virheiden varalta
     * ja luo uuden k�ytt�j�n jos kaikki on ok
     */
    private function rekisteroi_uusi_kayttaja()
    {
        if (empty($_POST['nimi'])) {
            $this->virheet[] = "Ei k�ytt�j�nime�.";
        } elseif (empty($_POST['uusi_salasana']) || empty($_POST['toisto_salasana'])) {
            $this->virheet[] = "Ei salasanaa";
        } elseif ($_POST['uusi_salasana'] !== $_POST['toisto_salasana']) {
            $this->virheet[] = "Salasanat eiv�t vastaa toisiaan";
        } elseif (strlen($_POST['uusi_salasana']) < 6) {
            $this->virheet[] = "Salasanan tulee olla v�hint��n 6 merkki�";
        } elseif (strlen($_POST['nimi']) > 64 || strlen($_POST['nimi']) < 2) {
            $this->virheet[] = "K�ytt�j�nimen tulee olla 2-64 merkki�";
        } elseif (!preg_match('/^[a-z\d]{2,64}$/i', $_POST['nimi'])) {
            $this->virheet[] = "K�ytt�j�nimi ei saa sis�lt�� erikoismerkkej�";
        } elseif (empty($_POST['sposti'])) {
            $this->virheet[] = "Ei s�hk�postia";
        } elseif (strlen($_POST['sposti']) > 64) {
            $this->virheet[] = "S�hk�posti ei saa olla yli 64 merkki�";
        } elseif (!filter_var($_POST['sposti'], FILTER_VALIDATE_EMAIL)) {
            $this->virheet[] = "S�hk�posti ei ole oikeassa muodossa";
        } elseif (!empty($_POST['nimi'])
            && strlen($_POST['nimi']) <= 64
            && strlen($_POST['nimi']) >= 2
            && preg_match('/^[a-z\d]{2,64}$/i', $_POST['nimi'])
            && !empty($_POST['sposti'])
            && strlen($_POST['sposti']) <= 64
            && filter_var($_POST['sposti'], FILTER_VALIDATE_EMAIL)
            && !empty($_POST['uusi_salasana'])
            && !empty($_POST['toisto_salasana'])
            && ($_POST['uusi_salasana'] === $_POST['toisto_salasana'])
        ) {
            // Luodaan tietokantayhteys
            $this->tietokanta_yhteys = new mysqli(TIETOKANTA_HOST, TIETOKANTA_KAYTTAJA, TIETOKANTA_SALASANA, TIETOKANTA_NIMI);
            // Vaihdetaan utf8 encodeen ja tarkastetaan se
            if (!$this->tietokanta_yhteys->set_charset("utf8")) {
                $this->virheet[] = $this->tietokanta_yhteys->virhe;
            }
            // Jos ei virheit� yhteydess� (= toimiva tietokantayhteys)
            if (!$this->tietokanta_yhteys->connect_errno) {
                // escape, lis�kis poistetaan mahdollinen (html/javascript-) koodi (tietoturva)
                $nimi = $this->tietokanta_yhteys->real_escape_string(strip_tags($_POST['nimi'], ENT_QUOTES));
                $sposti = $this->tietokanta_yhteys->real_escape_string(strip_tags($_POST['sposti'], ENT_QUOTES));
                $tuleva_salasana = $_POST['uusi_salasana'];
                // Cryptaa k�ytt�j�n salasanan PHP 5.5's password_hash() funktiolla, tuloksena 60 merkin
                // hash string. PASSWORD_DEFAULT vakio on m��ritelty PHP 5.5, tai jos k�yt�t
                // PHP 5.3/5.4, salasanan hajatukseen k�ytett�v�n tarkastus_kirjaston avulla
                $salasana = password_hash($tuleva_salasana, PASSWORD_DEFAULT);
                // Tarkistetaan onko k�ytt�j�nimi tai s�hk�posti jo olemassa
                $sql = "SELECT * FROM KAYTTAJA WHERE nimi = '" . $nimi . "' OR sposti = '" . $sposti . "';";
                $tarkasta_nimi = $this->tietokanta_yhteys->query($sql);
                if ($tarkasta_nimi->num_rows == 1) {
                    $this->virheet[] = "K�ytt�j�nimi tai s�hk�posti on jo k�yt�ss�";
                } else {
                    // Kirjoitetaan uuden k�ytt�j�n tiedot tietokantaan
                    $sql = "INSERT INTO KAYTTAJA (nimi, salasana, sposti)
                            VALUES('" . $nimi . "', '" . $salasana . "', '" . $sposti . "');";
                    $tunnuksen_insert = $this->tietokanta_yhteys->query($sql);
                    // Jos k�ytt�j�n lis��minen onnistui
                    if ($tunnuksen_insert) {
                        $this->ilmoitukset[] = "Tunnus luotiin onnistuneesti.";
                    } else {
                        $this->virheet[] = "Rekister�ityminen ep�onnistui, yrit� uudelleen..";
                    }
                }
            } else {
                $this->virheet[] = "Tietokantaan muodostaminen ep�onnistui.";
            }
        } else {
            $this->virheet[] = "Tuntematon virhe.";
        }
    }
}
