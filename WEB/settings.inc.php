<?php
//////////////////////////////////////////////////////////////////
/////////////////  Globalni nastaveni aplikace ///////////////////
//////////////////////////////////////////////////////////////////

//// Pripojeni k databazi ////

/** Adresa serveru. */
define("DB_HOST","localhost");
/** Nazev databaze. */
define("DB_NAME","webSP");
/** Uzivatel databaze. */
define("DB_USER","root");
/** Heslo uzivatele databaze */
define("DB_PASSWORD","");


//// Nazvy tabulek v DB ////

/** Tabulka s pohadkami. */
define("TABLE_UZIVATELE", "uzivatele");
/** Tabulka s uzivateli. */
define("TABLE_PRAVA", "prava");
/** Tabulka s clanky. */
define("TABLE_PRISPEVKY", "prispevky");
/** Tabulka s hodnoceni. */
define("TABLE_HODNOCENI", "hodnoceni");


//// Dostupne stranky webu ////

/** Adresar kontroleru. */
const DIRECTORY_CONTROLLERS = "app\Controllers";
/** Adresar modelu. */
const DIRECTORY_MODELS = "app\Models";
/** Adresar sablon */
const DIRECTORY_VIEWS = "app\Views";

/** Klic defaultni webove stranky. */
const DEFAULT_WEB_PAGE_KEY = "uvod";

/** Dostupne webove stranky. */
const WEB_PAGES = array(
    //// Uvodni stranka ////
    "uvod" => array(
        "title" => "Úvodní stránka",

        //// kontroler
        "file_name" => "UvodController.class.php",
        "class_name" => "UvodController",
    ),
    //// KONEC: Uvodni stranka ////

    //// Sprava uzivatelu ////
    "sprava" => array(
        "title" => "Správa uživatelů",

        //// kontroler
        "file_name" => "SpravaUzivateluController.class.php",
        "class_name" => "SpravaUzivateluController",
    ),
    //// KONEC: Sprava uzivatelu ////

    "registrace" => array(
        "title" => "Registrace",

        //// kontroler
        "file_name" => "RegistraceController.class.php",
        "class_name" => "RegistraceController"
    ),

    "prihlaseni" => array(
        "title" => "Prihlaseni",

        //// kontroler
        "file_name" => "PrihlaseniController.class.php",
        "class_name" => "PrihlaseniController"
    ),

    "recenzeAdmin" => array(
        "title" => "Správa članků a recenzí",

        //// kontroler
        "file_name" => "SpravaRecenziController.class.php",
        "class_name" => "SpravaRecenziController"
    ),

    "recenzeRec" => array(
        "title" => "Vlastní přiřazené recenze",

        //// kontroler
        "file_name" => "RecenzeRecController.class.php",
        "class_name" => "RecenzeRecController"
    ),

    "clanky" => array(
        "title" => "Moje vlastní články",

        //// kontroler
        "file_name" => "ClankyController.class.php",
        "class_name" => "ClankyController"
    ),

    "profil" => array(
        "title" => "Profil",

        //// kontroler
        "file_name" => "ProfilController.class.php",
        "class_name" => "ProfilController"
    ),

);

?>
