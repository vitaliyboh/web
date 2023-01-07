<?php
// nactu rozhrani kontroleru
require_once(DIRECTORY_CONTROLLERS."/IController.interface.php");

/**
 * Ovladac zajistujici vypsani stranky se spravou uzivatelu.
 */
class SpravaUzivateluController implements IController {

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

        $uzivatele = $this->db->getAllUsers();

        foreach ($uzivatele as $uzivatel) {
            if(isset($_POST['zmenit'.$uzivatel['id_uzivatele']])) {
                $ID = $_POST['zmenit'.$uzivatel['id_uzivatele']];
                $pravo = $_POST['vybranePravo'.$uzivatel['id_uzivatele']];
                $this->db->zmenPravo($ID, $pravo);
                header("Refresh:0");
            }
        }

        foreach ($uzivatele as $key => $uzivatel) {
            $uzivatele[$key]['pravo'] = $this->db->getNazevPrava($uzivatel['idprava'])['nazev'];
        }

        $tplData['uzivatele'] = $uzivatele;


        if(isset($_POST['Odstranit'])){
            $ID = $_POST['Odstranit'];
            $this->db->deleteFromTable(TABLE_UZIVATELE, "id_uzivatele='$ID'");
            header("Refresh:0");
        }


        if(isset($_POST['odhlasit']) and $_POST['odhlasit'] == "odhlasit"){
            $this->user->userLogout();
            header('location: index.php?page=uvod');
        }

        $tplData['userLogged'] = $this->user->isUserLogged();
        if($tplData['userLogged']){
            $user = $this->user->getLoggedUserData();
            $tplData['pravo'] = $user['idprava'];
        } else {
            $tplData['pravo'] = null;
        }


        ob_start();
        require(DIRECTORY_VIEWS ."/sprava.php");
        $obsah = ob_get_clean();

        return $obsah;
    }

}

?>