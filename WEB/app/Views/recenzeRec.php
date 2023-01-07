<?php
global $tplData;

// pripojim objekt pro vypis hlavicky a paticky HTML
require(DIRECTORY_VIEWS ."/TemplateBasics.class.php");
$tplHeaders = new TemplateBasics();

// hlavicka
$tplHeaders->createHeader("app/styles/uvod.css");

?>
<style>
    .hodnoceni{
        background-color: white;
        margin: 10px;
        border-radius: 5px;
        display: inline-block;
        width: fit-content;
    }
    .status {
        display: inline-block;
        text-align: left;
        border-radius: 5px;
        width: fit-content;
        position: relative;
        top: 50%;
        left: 1%;
        right: 1%;
    }
</style>

<body>
<?php
if (!$tplData['userLogged']) {
    $tplHeaders->createNav($tplData['pravo']);
} else {
    $tplHeaders->createNav($tplData['pravo'],"odhlaseni");
}
?>


<div class="container">
    <h1 style="text-align: center"> <?= $tplData['title'] ?> </h1>
    <form method="post">

        <div style="border-style: groove; padding: 10px 5px; margin-top: 10px; background-color: linen">
            <?php
            if(count($tplData['clanky']) == 0) {?>
                <h4><b>Dosud žádné přispěvky k recenzi</b></h4>
            <?php }
            else {
                foreach ($tplData['clanky'] as $clanek) { ?>
                    <div style=" border-radius: 25px;background-color: lightcyan;border-style: ridge; padding:10px 5px; margin: 5px 5px ">
                        <div style="border-radius: 10px; background-color: lightgreen; padding: 5px 5px">
                            <div>
                                <span class="status">
                                        Hodnoceni:

                                <?php $pocet = 0;
                                if (count($clanek['hodnoceni']) == 0) { $pocet++; ?>
                                    <span style="background-color: white; margin: 10px; border-radius: 5px">žádné</span>
                                <?php }
                                foreach ($clanek['hodnoceni'] as $hodnoceni) {
                                    if($tplData['user']['id_uzivatele']== $hodnoceni['id_uzivatele']) {
                                            if($hodnoceni['hvezdy'] != -1) { $pocet++; ?>
                                                <span class="hodnoceni" style="background-color: aqua">
                                                    <?php echo "Moje";?>
                                                    <?php
                                                    for($i=0; $i<5; $i++) {
                                                        if ($hodnoceni['hvezdy'] - $i >=1) {?>
                                                            <i class="fa fa-star"></i>
                                                        <?php }
                                                        elseif ($hodnoceni['hvezdy'] - $i >0) {?>
                                                            <i class="fa fa-star-half-o"></i>
                                                        <?php }
                                                        else {?>
                                                            <i class="fa fa-star-o"></i>
                                                        <?php }
                                                    } ?>
                                                    </span>
                                            <?php }
                                    }
                                    else {
                                        if($hodnoceni['hvezdy'] != -1) { $pocet++; ?>
                                        <span class="hodnoceni">
                                                    <?= $hodnoceni['recenzent'] ?>
                                            <?php
                                            for($i=0; $i<5; $i++) {
                                                if ($hodnoceni['hvezdy'] - $i >=1) {?>
                                                    <i class="fa fa-star"></i>
                                                <?php }
                                                elseif ($hodnoceni['hvezdy'] - $i >0) {?>
                                                    <i class="fa fa-star-half-o"></i>
                                                <?php }
                                                else {?>
                                                    <i class="fa fa-star-o"></i>
                                                <?php }
                                            } ?>
                                        </span>
                                    <?php } }?>
                                <?php }
                                if($pocet==0) {?> <span style="background-color: white; margin: 10px; border-radius: 5px">žádné</span> <?php } ?>
                                </span>
                                <?php if($clanek['akceptovano'] == 0) { ?>
                                    <span class="status" style="background: deepskyblue; color: white">
                                        <i>Status: čeká na posouzení.</i>
                                    </span>
                                <?php }
                                if($clanek['akceptovano'] == -1) { ?>
                                    <span class="status" style="background: red; color: white">
                                        <i>Status: zamítnuto.</i>
                                    </span>
                                <?php }
                                if($clanek['akceptovano'] == 1) { ?>
                                    <span class="status" style="background: green; color: white">
                                        <i>Status: akceptováno.</i>
                                    </span>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="row" style="margin-top: 10px">
                            <div class="col-lg-9">
                                <u><i><?php echo $clanek['nazev']?></i></u><br>
                            </div>
                            <div>
                                <div class="btn-group btn-group-sm col-sm-12 col-lg-3" >
                                    <?php if($clanek['akceptovano'] != 1) { ?>
                                        <button type="submit" name="recenzovatZobr" value="<?= $clanek['id_prispevku'] ?>" class="btn btn-primary"><i class="fa fa-pencil"></i> Recenzovat</button>
                                    <?php } ?>
                                    <a href="<?php echo $clanek['soubor']; ?>" class="btn btn-success" download><i class="fa fa-download"></i> Stáhnout
                                    </a>
                                </div>
                            </div>

                        </div>

                        <b>Abstrakt: </b>
                        <?php echo $clanek['abstrakt']?>
                        <br>
                        <br>

                        <?php
                            if($tplData['zobraz'] and $tplData['zobrRecProClanek'] == $clanek['id_prispevku']) {?>
                                <div style="border-style: solid;padding: 10px 5px; margin: 10px 10px; background-color: lightgray">
                                    <div class="form-group col-xs-12">
                                        <div style="margin-left: 10px">
                                            <label for="kvalita" style="width: 130px">Kvalita obsahu:</label>
                                            <input type="number" id="kvalita" name="kvalita" min="0" max="5" value="0"><br>
                                            <label for="formalita" style="width: 130px">Formální úroveň:</label>
                                            <input type="number" id="formalita" name="formalita" min="0" max="5" value="0"><br>
                                            <label for="novost" style="width: 130px">Novost:</label>
                                            <input type="number" id="novost" name="novost" min="0" max="5" value="0"><br>
                                            <label for="jazyk" style="width: 130px">Kvalita jazyka:</label>
                                            <input type="number" id="jazyk" name="jazyk" min="0" max="5" value="0"><br>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-xs-11" style="margin-left:20px">
                                                <button name="ulozit" type="submit" value="<?= $clanek['id_prispevku'] ?>" class="btn btn-success">Uložit recenzi</button>
                                                <button name="zrusit"  class="btn btn-outline-danger" formnovalidate>Zrušit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        <?php }?>
                    </div>
                <?php
                } } ?>
        </div>
    </form>
</div>


<?php
$tplHeaders->getHTMLFooter();
?>
