<?php
// nactu rozhrani kontroleru
require_once(DIRECTORY_CONTROLLERS."/IController.interface.php");

/**
 * Ovladac zajistujici vypsani stranky s clankami pro roli autora.
 */
class ClankyController implements IController {

    /** @var Database $db  Sprava databaze. */
    private $db;
    /**
     * @var SpravaUzivatele  $user Správa uživatele
     */
    private $user;

    /**
     * Inicializace pripojeni k databazi.
     */
    public function __construct() {
        // inicializace prace s DB
        require_once (DIRECTORY_MODELS ."/Database.class.php");
        $this->db = new Database();
        require_once (DIRECTORY_MODELS ."/SpravaUzivatele.class.php");
        $this->user = new SpravaUzivatele();
    }

    /**
     * Vrati obsah stranky.
     * @param string $pageTitle     Nazev stranky.
     * @return string               Vypis v sablone.
     */
    public function show(string $pageTitle):string {
        global $tplData;
        $tplData = [];

        $tplData['title'] = $pageTitle;

        if(isset($_POST['odhlasit']) and $_POST['odhlasit'] == "odhlasit"){
            $this->user->userLogout();
        }

        $tplData['userLogged'] = $this->user->isUserLogged();

        if($tplData['userLogged']){
            $user = $this->user->getLoggedUserData();
            $tplData['pravo'] = $user['idprava'];
        } else {
            // Nastavím právo pro nepřihlášeného uživatele NULL
            $tplData['pravo'] = null;
            header('location: index.php?page=uvod');
        }

        if (isset($_POST['zobrazPridat'])){
            $tplData['zobrazit'] = true;
        }
        else {
            $tplData['zobrazit'] = false;
        }


        if(isset($_POST['pridat'])) {
            $nazev = htmlspecialchars($_POST['nazev']);
            $abstrakt = htmlspecialchars($_POST['abstrakt']);

            $this->author = $_SESSION['user'];
            $target_dir = "app/uploads";
            $tmp_name = $_FILES["file"]["tmp_name"];
            $name = basename($_FILES["file"]["name"]);
            $file = "$target_dir/$name";
            move_uploaded_file($tmp_name, "$target_dir/$name");

            //Přidání příspěvku do databáze
            $this->db->pridejPrispevek($nazev, $abstrakt, $user, $file);
            header('location: index.php?page=clanky');
        }

        $clanky = $this->db->clankyAutora($user);
        foreach ($clanky as $key => $clanek) {
            $hodnoceni = $this->db->getHodnoceni($clanek['id_prispevku']);
            foreach ($hodnoceni as $klic => $hodn) {
                $recenzent = $this->db->getJmenoPodleId($hodn['id_uzivatele']);
                $hodnoceni[$klic]['recenzent'] = $recenzent['jmeno'];

                $pocetHvezd = ($hodn['kvalita_obsahu'] + $hodn['formalni_uroven'] +$hodn['novost'] + $hodn['kvalita_jazyku'])/4;
                $hodnoceni[$klic]['hvezdy'] = $pocetHvezd;
            }


            $clanky[$key]['hodnoceni'] = $hodnoceni;
        }


        $tplData['prispevky'] = $clanky;

        if(isset($_POST['odebrat'])) {
            $this->db->odstranClanek($_POST['odebrat']);
            header('location: index.php?page=clanky');
        }


        if(isset($_POST['zobrazit'])) {
            $tplData['boolZobraz'] = true;
            $tplData['zobrClanek'] = $this->db->clanekPodleSouboru($_POST['zobrazit']);
        }
        else {
            $tplData['boolZobraz'] = false;
        }


        ob_start();
        require(DIRECTORY_VIEWS ."/clanky.php");
        $obsah = ob_get_clean();

        return $obsah;
    }

}

?>