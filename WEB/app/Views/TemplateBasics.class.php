<?php

/**
 * Trida vypisujici HTML hlavicku a paticku stranky.
 */
class TemplateBasics {

    /**
     *  Vrati vrsek stranky az po oblast, ve ktere se vypisuje obsah stranky.
     *  * @param $styleHref    /Stylesheet
     */
    public function createHeader( $styleHref) {
        ?>
        <!doctype html>
        <html>
        <head>
            <meta charset='utf-8'>
            <meta name="viewport" content="width=device-width, initial-scale=1">


            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<!--            <script src="https://kit.fontawesome.com/788503f4f8.js" crossorigin="anonymous"></script>-->

            <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
            <link rel="stylesheet" href="<?=$styleHref?>">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

        </head>
        <?php
    }


    /**
     * vrati navbar podle toho jestli je uzivatel prihlesen nebo ne a take podle prava uzivatele
     * @param $pravo
     * @param $stav
     * @return void
     */
    public function createNav($pravo,$stav="prihlaseni") {
        ?>
        <div class="d-flex navbar bg-dark border-bottom shadow-sm navbar-dark sticky-top">
            <div class="container">
                <?php
                $vypis = "";
                if ($stav=='prihlaseni') {
                    $vypis .= "WebKonference";
                }
                else { $vypis .= "Úvodní stránka"; }
                ?>
                <a href="index.php?page=uvod"><h5 class="my-0 mr-md-auto font-weight-normal"><?= $vypis ?></h5></a>
                <nav class="navbar-expand-md">
                    <button class="navbar-toggler bg-dark justify-content-around collapsed" type="button" data-toggle="collapse" data-target="#collapsibleNavbar" aria-expanded="false">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="navbar-collapse collapse" id="collapsibleNavbar">
                        <div class="navbar-nav">

                            <?php
                            if ($pravo == null){ ?>
                                <a class="btn btn-outline-secondary text-white" href="index.php?page=registrace">Registrace</a>
                            <?php }

                            if($pravo == 4){ ?>
                                <a class="p-2 text-white" href="index.php?page=clanky">Články</a>
                            <?php }
                            if ($pravo == 1 || $pravo == 2) {?>
                                <a class="p-2 text-white" href="index.php?page=sprava">Uživatelé</a>
                                <a class="p-2 text-white" href="index.php?page=recenzeAdmin">Recenze</a>
                            <?php }
                            if($pravo == 3){ ?>
                                <a class="p-2 text-white" href="index.php?page=recenzeRec">Recenze</a>
                            <?php }
                            if ($pravo != null){ ?>
                                <a class="p-2 text-white " href="index.php?page=profil">Profil</a>
                            <?php }
                            if ($stav=='prihlaseni'){ ?>
                                <a class="btn btn-outline-primary" href="index.php?page=prihlaseni">Přihlášení</a>
                            <?php } else { ?>
                                <form method="post">
                                    <button type="submit" name="odhlasit" value="odhlasit" class="btn btn-outline-primary">Odhlásit se</button>
                                </form>
                            <?php } ?>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
        <?php
    }

    /**
     *  Vrati paticku stranky.
     */
    public function getHTMLFooter(){
        ?>
        <br>
        <footer><p align ="center">Copyright &copy; Vitaliy Bohera</p></footer>
        <body>
        </html>

        <?php
    }

}

?>