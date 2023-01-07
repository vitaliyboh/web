<?php
global $tplData;

// pripojim objekt pro vypis hlavicky a paticky HTML
require(DIRECTORY_VIEWS ."/TemplateBasics.class.php");
$tplHeaders = new TemplateBasics();

// hlavicka
$tplHeaders->createHeader("app/styles/profil.css");
?>
<body>

<?php
if (!$tplData['userLogged']) {
    $tplHeaders->createNav($tplData['pravo']);
} else {
    $tplHeaders->createNav($tplData['pravo'],"odhlaseni");
}
?>

<!--<div class="container">-->
<!--    <h1> --><?//= $tplData['title']?><!-- </h1>-->
<!--    <hr>-->
<!--    <div class="vypisy">-->
<!--        <h3><b>Jméno:</b> --><?//= $tplData['uzivatel']['jmeno']?><!--</h3><hr>-->
<!--        <h3><b>Login:</b> --><?//= $tplData['uzivatel']['login']?><!--</h3><hr>-->
<!--        <h3><b>Email:</b> --><?//= $tplData['uzivatel']['email']?><!--</h3><hr>-->
<!--        <h3><b>Role:</b> --><?//= $tplData['nazevPrava']?><!--</h3>-->
<!--    </div>-->
<!--</div>-->

<div class="container">
    <div style="border-style: groove; padding: 10px 5px; margin-top: 10px; background-color: linen">
            <div style="text-align: center">
                <h2>Profil</h2>
            </div>

                <div style="border-radius: 25px;background-color: lightcyan;border-style: ridge; padding:10px 5px; margin: 5px 5px ">
                    <h3><b>Jméno:</b> <?= $tplData['uzivatel']['jmeno']?></h3><hr>
                    <h3><b>Login:</b> <?= $tplData['uzivatel']['login']?></h3><hr>
                    <h3><b>Email:</b> <?= $tplData['uzivatel']['email']?></h3><hr>
                    <h3><b>Role:</b> <?= $tplData['nazevPrava']?></h3>
                </div>
    </div>
</div>


<?php $tplHeaders->getHTMLFooter(); ?>
