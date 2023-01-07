<?php

//// vypis sablony
// urceni globalnich promennych, se kterymi sablona pracuje
global $tplData;

// pripojim objekt pro vypis hlavicky a paticky HTML
require(DIRECTORY_VIEWS ."/TemplateBasics.class.php");
$tplHeaders = new TemplateBasics();

// hlavicka
$tplHeaders->createHeader("app/styles/uvod.css");
?>
<body>

<?php
if (!$tplData['userLogged']) {
    $tplHeaders->createNav($tplData['pravo']);
} else {
    $tplHeaders->createNav($tplData['pravo'],"odhlaseni");
}
?>

<div class="container">
    <div style="border-style: groove; padding: 10px 5px; margin-top: 10px; background-color: linen">
        <form method="post">
            <div style="text-align: center">
                <h3>Aktuální články</h3>
            </div>
            <?php
            foreach ($tplData['clanky'] as $clanek) {?>
            <div style="border-radius: 25px;background-color: lightcyan;border-style: ridge; padding:10px 5px; margin: 5px 5px ">
                <b>Autor:</b> <i> <?php echo $clanek['autor']?></i><br>
                <b>Název článku:</b>
                <u><i> <?php echo $clanek['nazev']?></i></u><br><br>
                    <b>Abstrakt: </b>
                    <?php echo $clanek['abstrakt']?>
                    <br>
                    <div class="btn-group" style="position: relative; left: 40%; margin-top: 5px">
                        <button name="zobrazit" value='<?=$clanek['soubor']?>' type="submit" class="btn btn-primary"><i class="fa fa-file-text"></i> Zobrazit</button>
                        <?php if($tplData['boolZobraz'] and $tplData['zobrClanek']['id_prispevku'] == $clanek['id_prispevku']) { ?>
                            <button name="skryt" type="submit" class="btn btn-secondary"><i class="fa fa-file-text"></i> Skrýt</button>
                        <?php } ?>
                    </div>
                    <br>
                    <?php if($tplData['boolZobraz'] and $tplData['zobrClanek']['id_prispevku'] == $clanek['id_prispevku']) { ?>
                        <iframe style="width: 100%; height: 500px; margin-top: 10px" src="<?=$tplData['zobrClanek']['soubor']?>" frameborder="0"></iframe>
                    <?php }
                    ?></div>
                <?php }
                ?>
        </form>
    </div>

    <div id="demo" class="carousel slide" data-ride="carousel">

        <!-- Indicators -->
        <ul class="carousel-indicators">
            <li data-target="#demo" data-slide-to="0" class="active"></li>
            <li data-target="#demo" data-slide-to="1"></li>
            <li data-target="#demo" data-slide-to="2"></li>
        </ul>

        <!-- The slideshow -->
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="https://www.vseoprumyslu.cz/media/k2/items/cache/df8bdc0787eb68c35292ff74fa60466b_L.jpg">
            </div>
            <div class="carousel-item">
                <img src="https://community.thriveglobal.com/wp-content/uploads/2019/02/office-work.jpg">
            </div>
            <div class="carousel-item">
                <img src="https://vceliste.cz/wp-content/uploads/2022/09/tipy-na-podzimni-marketingove-konference.jpg">
            </div>
        </div>

        <!-- Left and right controls -->
        <a class="carousel-control-prev" href="#demo" data-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </a>
        <a class="carousel-control-next" href="#demo" data-slide="next">
            <span class="carousel-control-next-icon"></span>
        </a>

    </div>
</div>

<?php
// paticka
$tplHeaders->getHTMLFooter()

?>


