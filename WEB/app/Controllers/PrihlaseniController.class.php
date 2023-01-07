<?php
// nactu rozhrani kontroleru
require_once(DIRECTORY_CONTROLLERS."/IController.interface.php");

/**
 * Ovladac zajistujici vypsani stranky s prihlasenim.
 */
class PrihlaseniController implements IController {

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
            $tplData['pravo'] = null;
        }

        if (isset($_POST['prihlasit']) and isset($_POST['login']) and
            isset($_POST['password']) and $_POST['prihlasit'] == "prihlasit"){

            $login = htmlspecialchars($_POST['login']);
            $heslo = htmlspecialchars($_POST['password']);
            $uzivatel = $this->db->vratUzivatele($login,$heslo);
            if (!empty($uzivatel)){
                $tplData['userLogged'] = $this->user->userLogin($login,$heslo);
                $tplData['povedloSe'] = true;
                $tplData['info'] = "Přihlášení se povedlo! Vítejte ".$uzivatel[0]['jmeno'];
            } else {
                $tplData['povedloSe'] = false;
                $tplData['info'] = "Zadali jste špatný login nebo heslo!";
            }
        }

        ob_start();
        require(DIRECTORY_VIEWS ."/prihlaseni.php");
        $obsah = ob_get_clean();

        return $obsah;
    }

}

?>