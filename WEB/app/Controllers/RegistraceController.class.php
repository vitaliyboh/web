<?php
// nactu rozhrani kontroleru
require_once(DIRECTORY_CONTROLLERS."/IController.interface.php");

/**
 * Ovladac zajistujici vypsani stranky s registraci.
 */
class RegistraceController implements IController {

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

        //Registrace nového uzivatele
        if (isset($_POST['registruj']) and isset($_POST['login']) and
            isset($_POST['password']) and isset($_POST['email']) and isset($_POST['jmeno']) and
            $_POST['registruj'] == "registruj"){

            $jmeno = htmlspecialchars($_POST['jmeno']);
            $login = htmlspecialchars($_POST['login']);
            $email = htmlspecialchars($_POST['email']);
            $heslo = htmlspecialchars($_POST['password']);
            $isRegistered = $this->db->getAUser($login);
            if(!count($isRegistered)){
                $tplData['oka'] = $this->db->registrujUzivatele( $jmeno,$login,$heslo,$email);
                $tplData['userLogged'] = $this->user->userLogin($login,$heslo);
                $tplData['info'] = "Registrace se zdařila! Vítejte ".$jmeno;
            } else {
                $tplData['oka'] = false;
                $tplData['info'] = "Je mi líto, ale registrace se nezdařila. Tento login je již použit.";
            }
        }

        ob_start();
        require(DIRECTORY_VIEWS ."/registrace.php");
        $obsah = ob_get_clean();

        return $obsah;
    }

}

?>