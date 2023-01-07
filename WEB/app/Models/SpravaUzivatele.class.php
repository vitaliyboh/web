<?php

/**
 * Třída userManage slouží ke správě uživatele
 */
class SpravaUzivatele
{

    /** @var string $userSessionKey  Klíč pro session */
    private $userSessionKey = "current_user_id";
    /**
     * @var Database $db Správa databáze
     */
    private $db;
    /**
     * @var Session $mySession Správa sessions
     */
    private $mySession;

    public function __construct(){
        require_once("Database.class.php");
        $this->db = new Database();
        require_once("Session.class.php");
        $this->mySession = new Session();
    }

    /**
     * Pokusím se přihlásit uživatele
     *
     * @param string $login Login uživatele
     * @param string $heslo Heslo uzivatele.
     * @return bool         Byl prihlasen?
     */
    public function userLogin(string $login, string $heslo): bool
    {
        $hash = $this->db->getHash($login);
        $isOk = password_verify($heslo, $hash);
        $where = "login='$login'";
        $user = $this->db->selectFromTable(TABLE_UZIVATELE, $where);

        if($isOk){
            $_SESSION[$this->userSessionKey] = $user[0]['id_uzivatele']; // beru prvniho nalezeneho a ukladam jen jeho ID
            return true;
        } else {
            return false;
        }
    }

    /**
     * Odhlasím uživatele
     */
    public function userLogout(){
        unset($_SESSION[$this->userSessionKey]);
    }

    /**
     * Je uživatel přihlášený?
     *
     * @return bool     Je přihlášen?
     */
    public function isUserLogged(): bool
    {
        return isset($_SESSION[$this->userSessionKey]);
    }

    /**
     * Pokusím se zjistit data o uživateli, pokud je přihlášen
     *
     * @return mixed|null   Data uzivatele nebo null.
     */
    public function getLoggedUserData(){
        if($this->isUserLogged()){
            $userId = $_SESSION[$this->userSessionKey];
            if($userId == null) {
                echo "Data o uživateli nebyla nalezena, odhlašuji uživatele!";
                $this->userLogout();
                return null;
            } else {
                $userData = $this->db->selectFromTable(TABLE_UZIVATELE, "id_uzivatele=$userId");
                if(empty($userData)){
                    echo "Data o uživateli se nenachází v naší databázi, odhlašuji!";
                    $this->userLogout();
                    return null;
                } else {
                    return $userData[0];
                }
            }
        } else {
            return null;
        }
    }


}