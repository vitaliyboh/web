<?php
// nactu rozhrani kontroleru
require_once(DIRECTORY_CONTROLLERS."/IController.interface.php");

/**
 * Ovladac zajistujici vypsani stranky se spravou recenzi.
 */
class SpravaRecenziController implements IController {

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

        if(isset($_POST['odstranit'])) {
            $this->db->odstranHodnoceni($_POST['odstranit']);
        }

        if(isset($_POST['schvalit'])) {
            $this->db->zmenStav($_POST['schvalit'],1);
        }
        if(isset($_POST['zamitnout'])) {
            $this->db->zmenStav($_POST['zamitnout'],-1);
        }

        $recenzenti = $this->db->getAllRecenzents();
        $tplData['recenzenti'] = $recenzenti;

        $clanky = $this->db->getAllClanky();
        foreach ($clanky as $key => $clanek) {
            $clanky[$key]['autor'] = ($this->db->getJmenoPodleId($clanek['id_uzivatele']))['jmeno'];
            $hodnoceni = $this->db->getHodnoceni($clanek['id_prispevku']);
            foreach ($hodnoceni as $klic => $hodn) {
                $recenzent = $this->db->getJmenoPodleId($hodn['id_uzivatele']);
                $hodnoceni[$klic]['recenzent'] = $recenzent['jmeno'];

                $pocetHvezd = ($hodn['kvalita_obsahu'] + $hodn['formalni_uroven'] +$hodn['novost'] + $hodn['kvalita_jazyku'])/4;
                $hodnoceni[$klic]['hvezdy'] = $pocetHvezd;
            }
            $clanky[$key]['hodnoceni'] = $hodnoceni;
        }
        $tplData['clanky'] = $clanky;


        foreach ($clanky as $cl) {
            if(isset($_POST['pridej'.$cl['id_prispevku']])) {
                $data = explode(",",$_POST['recenzenti'.$cl['id_prispevku']]);
                $this->db->pridejClanekRecenzentu($data[0], $data[1]);
                header("Refresh: 0");
            }
        }

        if(isset($_POST['zobrazit'])) {
            $tplData['boolZobraz'] = true;
            $tplData['zobrClanek'] = $this->db->clanekPodleSouboru($_POST['zobrazit']);
        }
        else {
            $tplData['boolZobraz'] = false;
        }


        ob_start();
        require(DIRECTORY_VIEWS ."/recenzeAdmin.php");
        $obsah = ob_get_clean();

        return $obsah;
    }

}

?>