<?php
// nactu rozhrani kontroleru
require_once(DIRECTORY_CONTROLLERS."/IController.interface.php");

/**
 * Ovladac zajistujici vypsani uvodni stranky.
 */
class UvodController implements IController {

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
     * Vrati obsah uvodni stranky.
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
        }

        $clanky = $this->db->getAkceptovaneClanky();

        foreach ($clanky as $key => $clanek) {
            $clanky[$key]['autor'] = ($this->db->getJmenoPodleId($clanek['id_uzivatele']))['jmeno'];
        }

        $tplData['clanky'] = $clanky;


        if(isset($_POST['zobrazit'])) {
            $tplData['boolZobraz'] = true;
            $tplData['zobrClanek'] = $this->db->clanekPodleSouboru($_POST['zobrazit']);
        }
        else {
            $tplData['boolZobraz'] = false;
        }

        ob_start();
        require(DIRECTORY_VIEWS ."/uvod.php");
        $obsah = ob_get_clean();

        return $obsah;
    }

}

?>