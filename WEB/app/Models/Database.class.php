<?php

/**
 * Trida pro spravu databaze.
 */
class Database{

    /** @var PDO $db  Instance PDO pro praci s databazi. */
    private $db;

    /**
     * Inicilalizace pripojeni k databazi.
     */
    public function __construct(){
        // nacteni nastaveni
        require_once("settings.inc.php");
        // vytvoreni instance PDO  pro praci s DB
        $this->db = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASSWORD);
        // vynuceni kodovani UTF-8
        $q = "SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'";
        $this->db->query($q);
    }

    /**
     *  Provede dotaz a bud vrati ziskana data, nebo pri chybe ji vypise a vrati null.
     *  Varianta, pokud je pouzit PDO::ERRMODE_EXCEPTION
     *
     *  @param string $dotaz        SQL dotaz.
     *  @return PDOStatement|null    Vysledek dotazu.
     */
    private function executeQuery(string $dotaz){
        // vykonam dotaz
        try {
            $res = $this->db->query($dotaz);
            return $res;
        } catch (PDOException $ex){
            echo "Nastala výjimka: ". $ex->getCode() ."<br>"
                ."Text: ". $ex->getMessage();
            return null;
        }
    }

    /**
     * Select z jedné tabulky
     *
     * @param string $tableName         Název tabulky
     * @param string $whereStatement    Pripadne omezeni na ziskani radek tabulky. Default "".
     * @param string $orderByStatement  Pripadne razeni ziskanych radek tabulky. Default "".
     * @return array                    Vraci pole ziskanych radek tabulky.
     */
    public function selectFromTable(string $tableName, string $whereStatement = "", string $orderByStatement = ""):array {
        $q = "SELECT * FROM ".$tableName
            .(($whereStatement == "") ? "" : " WHERE $whereStatement")
            .(($orderByStatement == "") ? "" : " ORDER BY $orderByStatement");

        $obj = $this->executeQuery($q);
        if($obj == null){
            return [];
        }
        return $obj->fetchAll();
    }

    /**
     * Dle zadane podminky maze radky v prislusne tabulce.
     *
     * @param string $tableName         Nazev tabulky.
     * @param string $whereStatement    Podminka mazani.
     * @return bool
     */
    public function deleteFromTable(string $tableName, string $whereStatement):bool {
        // slozim dotaz
        $q = "DELETE FROM $tableName WHERE $whereStatement";
        // provedu ho a vratim vysledek
        $obj = $this->executeQuery($q);
        // pokud ($obj == null), tak vratim false
        return ($obj != null);
    }

    /**
     * Upráva řádku databáze
     *
     * @param string $tableName                     Nazev tabulky.
     * @param string $updateStatementWithValues     Cela cast updatu s hodnotami.
     * @param string $whereStatement                Cela cast pro WHERE.
     * @return bool                                 Upraveno v poradku?
     */
    public function updateInTable(string $tableName, string $updateStatementWithValues, string $whereStatement):bool {

        $q = "UPDATE $tableName SET $updateStatementWithValues WHERE $whereStatement";

        $obj = $this->executeQuery($q);
        if($obj == null){
            return false;
        } else {
            return true;
        }
    }

//---------------------------UZIVATELE--------------------------------------------//

    /**
     * Ziskani zaznamu vsech uzivatelu aplikace.
     *
     * @return array    Pole se vsemi uzivateli.
     */
    public function getAllUsers(){
        // ziskam vsechny uzivatele z DB razene dle ID a vratim je
        $users = $this->selectFromTable(TABLE_UZIVATELE, "", "idprava");
        return $users;
    }

    /**
     * Nalezne uzivatele s danym loginem a heslem a vrati je.
     * @param string $log   Login.
     * @param string $pas   Heslo.
     * @return array
     */
    public function vratUzivatele($log, $pas){
        $hash = $this->getHash($log);
        $isOk = password_verify($pas, $hash);
        if($isOk) {
            $q = "SELECT * FROM " . TABLE_UZIVATELE . " WHERE login=:uLogin;";
            $vystup = $this->db->prepare($q);
            $vystup->bindValue(":uLogin", $log);
        }
        else {
            return null;
        }
        if($vystup->execute()){
            // dotaz probehl v poradku
            // vsechny radky do pole a to vratim
            return $vystup->fetchAll();
        } else {
            // dotaz skoncil chybou
            return null;
        }
        /////// KONEC: Osetreni SQL Injection ///

    }

    /**
     * Získání uživatele podle Loginu.
     *
     * @param string $login    Login pro vyhledání v databízi.
     * @return array    Pole se vsemi uživateli (vždycky bude pouze jeden uživatel).
     */
    public function getAUser(string $login): ?array
    {
        $q = "SELECT * FROM ".TABLE_UZIVATELE
            ." WHERE login=:uLogin;";
        $user = $this->db->prepare($q);
        $user->bindValue(":uLogin",$login);
        if($user->execute()){
            return $user->fetchAll();
        } else {
            return null;
        }
    }



    /**
     * Registruje nového uživatele
     *
     * @param $email /Email uživatele
     * @param $username /Uživatelské jméno
     * @param $password /Heslo
     * @param string $pravo /právo uživatele
     * @return bool         Povedlo se?
     */
    public function registrujUzivatele($jmeno, $log, $password, $email, $privileges = "4"): bool
    {
//        $uzivatel = $this->vratUzivatele($log,$password);
        $password = password_hash($password, PASSWORD_BCRYPT);

        //if(!isset($uzivatel) || count($uzivatel)==0){
            $q = "INSERT INTO ".TABLE_UZIVATELE." (jmeno, login, heslo, email, idprava) VALUES (:jmeno, :login, :heslo, :email, :pravo);";
            $vystup = $this->db->prepare($q);
            $vystup->bindValue(":jmeno", $jmeno);
            $vystup->bindValue(":email", $email);
            $vystup->bindValue(":login", $log);
            $vystup->bindValue(":heslo", $password);
            $vystup->bindValue(":pravo", $privileges);

            if($vystup->execute()){
                return true;
            } else {
                return false;
            }
        //}
        //return false;
    }

    /**
     * zmeni pravo uzivatele
     * @param int $idUzivatele id uzivatele u ktereho chceme zmenit pravo
     * @param int $idPravo  pravo ktere chceme nastavit
     * @return bool povedlo se?
     */
    public function zmenPravo(int $idUzivatele, int $idPravo){
        // slozim cast s hodnotami
        $updateStatementWithValues = "idprava='$idPravo'";
        // podminka
        $whereStatement = "id_uzivatele='$idUzivatele'";
        // provedu update
        return $this->updateInTable(TABLE_UZIVATELE, $updateStatementWithValues, $whereStatement);
    }

    /**
     * zjisteni id prava podle id uzivatele
     * @param int $idUzivatele id uzivatele
     * @return mixed
     */
    public function getIDPravaPodleUzivatele(int $idUzivatele) {
        $pravo = $this->selectFromTable(TABLE_UZIVATELE, "id_uzivatele='$idUzivatele'");
        return $pravo[0]['idprava'];
    }

    /**
     * zjisti jmeno podle id uzivatele
     * @param $ID id uzivatele
     * @return mixed|null
     */
    public function getJmenoPodleId($ID) {
        $query = "select * from ".TABLE_UZIVATELE." where id_uzivatele = :id";

        $vystup = $this->db->prepare($query);
        $vystup->bindparam(':id', $ID);

        if($vystup->execute()){
            // dotaz probehl v poradku
            // vsechny radky do pole a to vratim
            return ($vystup->fetchAll())[0];
        } else {
            // dotaz skoncil chybou
            return null;
        }
    }

//------------------------------------------Články---------------------------------------------//

    /** prida novy prispevek do databaze
     * @param $nazev
     * @param $abstrakt
     * @param $user
     * @param $file
     * @param $akceptovano
     * @return bool povedlo se?
     */
    public  function pridejPrispevek($nazev, $abstrakt, $user, $file, $akceptovano = "0") {
        $id_uzivatele = $user['id_uzivatele'];

        $q = "INSERT INTO ".TABLE_PRISPEVKY." (nazev, abstrakt, id_uzivatele, soubor, akceptovano) VALUES (:nazev, :abstrakt, :id_uzivatele, :soubor, :akceptovano);";
        $vystup = $this->db->prepare($q);
        $vystup->bindValue(":nazev", $nazev);
        $vystup->bindValue(":abstrakt", $abstrakt);
        $vystup->bindValue(":id_uzivatele", $id_uzivatele);
        $vystup->bindValue(":soubor", $file);
        $vystup->bindValue(":akceptovano", $akceptovano);

        if($vystup->execute()){
            return true;
        } else {
            return false;
        }
    }

    //Vrátí články daného autora
    public function clankyAutora($user){
        $query = "select * from ".TABLE_PRISPEVKY." where id_uzivatele = :author order by id_prispevku desc";

        $vystup = $this->db->prepare($query);
        $vystup->bindparam(':author', $user['id_uzivatele']);

        if($vystup->execute()){
            // dotaz probehl v poradku
            // vsechny radky do pole a to vratim
            return $vystup->fetchAll();
        } else {
            // dotaz skoncil chybou
            return null;
        }
    }

    public function clankyRecenzenta($ID) {
        $query = "select p.id_prispevku, p.nazev, p.abstrakt, p.id_uzivatele, p.soubor, p.akceptovano from ".TABLE_PRISPEVKY." p,".TABLE_HODNOCENI." h where p.id_prispevku = h.id_prispevku and h.id_uzivatele = '$ID' order by p.id_prispevku desc";

        $vystup = $this->db->prepare($query);
        if($vystup->execute()){
            // dotaz probehl v poradku
            // vsechny radky do pole a to vratim
            return $vystup->fetchAll();
        } else {
            // dotaz skoncil chybou
            return null;
        }
    }

    //Smaže článek podle id
    public function odstranClanek($ID) {
        $query = "delete from ".TABLE_PRISPEVKY." where id_prispevku = :id";

        $vystup = $this->db->prepare($query);
        $vystup->bindparam(':id', $ID);

        if($vystup->execute()){
            // dotaz probehl v poradku
            // vsechny radky do pole a to vratim
            return $vystup->fetchAll();
        } else {
            // dotaz skoncil chybou
            return null;
        }
    }

    /**
     * vrati clanek podle souboru
     * @param $soubor
     * @return mixed|null
     */
    public function clanekPodleSouboru($soubor) {
        $query = "select * from ".TABLE_PRISPEVKY." where soubor = :soubor";

        $vystup = $this->db->prepare($query);
        $vystup->bindparam(':soubor', $soubor);

        if($vystup->execute()){
            // dotaz probehl v poradku
            // vsechny radky do pole a to vratim
            return ($vystup->fetchAll())[0];
        } else {
            // dotaz skoncil chybou
            return null;
        }
    }

    /**
     * vrati vsechny schvalene clanky
     * @return array|false|null
     */
    public function getAkceptovaneClanky() {
        $query = "select * from ".TABLE_PRISPEVKY." where akceptovano = '1' order by id_prispevku desc";

        $vystup = $this->db->prepare($query);
        if($vystup->execute()){
            // dotaz probehl v poradku
            // vsechny radky do pole a to vratim
            return $vystup->fetchAll();
        } else {
            // dotaz skoncil chybou
            return null;
        }
    }

    /**
     * vrati vsechny clanky
     * @return array|false|null
     */
    public function getAllClanky() {
        $query = "select * from ".TABLE_PRISPEVKY." order by id_prispevku desc";

        $vystup = $this->db->prepare($query);
        if($vystup->execute()){
            // dotaz probehl v poradku
            // vsechny radky do pole a to vratim
            return $vystup->fetchAll();
        } else {
            // dotaz skoncil chybou
            return null;
        }
    }

    /**
     * zmeni stav clanku
     * @param $ID
     * @param $stav
     * @return array|false|null
     */
    public function zmenStav($ID, $stav) {
        $query = "update ".TABLE_PRISPEVKY." set akceptovano = '$stav' where id_prispevku = '$ID'";

        $vystup = $this->db->prepare($query);
        if($vystup->execute()){
            // dotaz probehl v poradku
            // vsechny radky do pole a to vratim
            return $vystup->fetchAll();
        } else {
            // dotaz skoncil chybou
            return null;
        }
    }


//---------------------------HODNOCENI--------------------------------------------//

    /**
     * vrati vsechna hodnoceni daneho clanku s id
     * @param $idClanek
     * @return array|false|null
     */
    public function getHodnoceni($idClanek) {
        $query = "select * from ".TABLE_HODNOCENI." where id_prispevku = :idClanek";

        $vystup = $this->db->prepare($query);
        $vystup->bindparam(':idClanek', $idClanek);

        if($vystup->execute()){
            // dotaz probehl v poradku
            // vsechny radky do pole a to vratim
            return $vystup->fetchAll();
        } else {
            // dotaz skoncil chybou
            return null;
        }
    }

    /**
     * odstrani hodnoceni s id
     * @param $ID
     * @return array|false|null
     */
    public function odstranHodnoceni($ID) {
        $query = "delete from ".TABLE_HODNOCENI." where id_hodnoceni = :id";

        $vystup = $this->db->prepare($query);
        $vystup->bindparam(':id', $ID);

        if($vystup->execute()){
            // dotaz probehl v poradku
            // vsechny radky do pole a to vratim
            return $vystup->fetchAll();
        } else {
            // dotaz skoncil chybou
            return null;
        }
    }

    public function updateHodnoceni($kvalita, $formalita, $novost, $jazyk, $IDuzivatele, $IDprispevku) {
        $query = "update ".TABLE_HODNOCENI." set kvalita_obsahu=$kvalita, formalni_uroven=$formalita, novost=$novost, kvalita_jazyku=$jazyk where id_uzivatele = $IDuzivatele and id_prispevku = $IDprispevku";

        $vystup = $this->db->prepare($query);
        if($vystup->execute()){
            // dotaz probehl v poradku
            // vsechny radky do pole a to vratim
            return $vystup->fetchAll();
        } else {
            // dotaz skoncil chybou
            return null;
        }
    }

//---------------------------JINE--------------------------------------------//

    /**
     * vrati nazev prava podle id
     * @param int $idprava
     * @return array
     */
    public function getNazevPrava(int $idprava): array {
        $pravo = $this->selectFromTable(TABLE_PRAVA, "id_prava='$idprava'");
        return $pravo[0];
    }

    /**
     * vrati hash hesla z databaze
     * @param $login
     * @return mixed|null
     */
    public function getHash($login) {
        $q = "SELECT * FROM ".TABLE_UZIVATELE." WHERE login=:uLogin;";
        $vystup = $this->db->prepare($q);
        $vystup->bindValue(":uLogin", $login);
        if($vystup->execute()){
            // dotaz probehl v poradku
            // vsechny radky do pole a to vratim
            $pole = $vystup->fetchAll();
            return $pole[0]['heslo'];
        } else {
            // dotaz skoncil chybou
            return null;
        }
    }

    /**
     * vrati vsechny recenzenty
     * @return array|false|null
     */
    public function getAllRecenzents() {
        $query = "select * from ".TABLE_UZIVATELE." where idprava = '3'";

        $vystup = $this->db->prepare($query);
        if($vystup->execute()){
            // dotaz probehl v poradku
            // vsechny radky do pole a to vratim
            return $vystup->fetchAll();
        } else {
            // dotaz skoncil chybou
            return null;
        }
    }

    /**
     * priradi recenzentovi clanek k hodnoceni
     * @param $idRecenzent
     * @param $idClanek
     * @return array|false|null
     */
    public function pridejClanekRecenzentu($idRecenzent, $idClanek) {
        $query = "insert into ".TABLE_HODNOCENI." (kvalita_obsahu, formalni_uroven, novost, kvalita_jazyku, id_uzivatele, id_prispevku) values (-1,-1,-1,-1,'$idRecenzent','$idClanek')";

        $vystup = $this->db->prepare($query);
        if($vystup->execute()){
            // dotaz probehl v poradku
            // vsechny radky do pole a to vratim
            return $vystup->fetchAll();
        } else {
            // dotaz skoncil chybou
            return null;
        }
    }


}


?>
