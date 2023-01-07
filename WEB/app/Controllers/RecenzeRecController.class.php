<?php
// nactu rozhrani kontroleru
require_once(DIRECTORY_CONTROLLERS."/IController.interface.php");

/**
 * Ovladac zajistujici vypsani stranky s recenzemi pro recenzenta.
 */
class RecenzeRecController implements IController {

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
            $tplData['user'] = $user;
            $tplData['pravo'] = $user['idprava'];
        } else {
            // Nastavím právo pro nepřihlášeného uživatele NULL
            $tplData['pravo'] = null;
            header('location: index.php?page=uvod');
        }

        $tplData['zobraz'] = false;
        if(isset($_POST['recenzovatZobr'])) {
            $tplData['zobraz'] = true;
            $tplData['zobrRecProClanek'] = $_POST['recenzovatZobr'];
        }

        if(isset($_POST['ulozit'])) {
            $this->db->updateHodnoceni($_POST['kvalita'],$_POST['formalita'],$_POST['novost'],$_POST['jazyk'],$user['id_uzivatele'],$_POST['ulozit']);
        }

        $clanky = $this->db->clankyRecenzenta($user['id_uzivatele']);
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



        ob_start();
        require(DIRECTORY_VIEWS ."/recenzeRec.php");
        $obsah = ob_get_clean();

        return $obsah;
    }

}

?>