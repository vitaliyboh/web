<?php
// nactu rozhrani kontroleru
require_once(DIRECTORY_CONTROLLERS."/IController.interface.php");

/**
 * Ovladac zajistujici vypsani profilove stranky.
 */
class ProfilController implements IController {

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
     * Vrati obsah profilovou stranky.
     * @param string $pageTitle     Nazev stranky.
     * @return string               Vypis v sablone.
     */
    public function show(string $pageTitle):string {
        global $tplData;
        $tplData = [];

        $tplData['title'] = $pageTitle;

        if(isset($_POST['odhlasit']) and $_POST['odhlasit'] == "odhlasit"){
            $this->user->userLogout();
            header('location: index.php?page=uvod');
        }

        $tplData['userLogged'] = $this->user->isUserLogged();
        if($tplData['userLogged']){
            $user = $this->user->getLoggedUserData();
            $tplData['pravo'] = $user['idprava'];
            $tplData['nazevPrava'] = $this->db->getNazevPrava($user['idprava'])['nazev'];
            $tplData['uzivatel'] = $user;
        } else {
            $tplData['pravo'] = null;
            $tplData['uzivatel'] = null;
        }

        ob_start();
        require(DIRECTORY_VIEWS ."/profil.php");
        $obsah = ob_get_clean();

        return $obsah;
    }

}

?>