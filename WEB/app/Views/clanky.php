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
    <form method="post" enctype="multipart/form-data">
        <button name="zobrazPridat" id="btn" class="btn btn-success" type="submit"><i class="fa fa-plus-square"></i> Přidat nový článek</button>

        <?php if($tplData['zobrazit']) { ?>
        <div style="border-style: solid;padding: 10px 5px; margin: 10px 10px; background-color: lightgray">
            <div class="form-group col-xs-12">
                <div class="row">
                    <div class="form-group col-xs-5">
                        <div class="form-group" style="margin-left:20px; margin-right:20px">
                            <label>Název článku</label>
                            <textarea name="nazev" class="form-control" id="exampleTextarea" rows="2" cols="130" required=""></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-xs-6" style="margin-left:20px; margin-right:20px">
                        <label>Abstrakt</label>
                        <textarea name="abstrakt" class="form-control" id="exampleTextarea" rows="5" cols="130" required=""></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-xs-6">
                        <div class="form-group" style="margin-left:20px">
                            <label for="file">Nahrajte pdf soubor</label>
                            <input type="file" name="file" accept=".pdf" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-xs-11" style="margin-left:20px">
                        <button name="pridat" type="submit" class="btn btn-primary">Přidat článek</button>
                        <button name="zrusit"  class="btn btn-primary" formnovalidate>Zrušit</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
        } ?>

        <div style="border-style: groove; padding: 10px 5px; margin-top: 10px; background-color: linen">
            <?php
            if(count($tplData['prispevky'])== 0) {?>
                <h4><b>Dosud žádné přispěvky</b></h4>
            <?php }
            else {
                foreach ($tplData['prispevky'] as $prispevek) { ?>
                    <div style="border-radius: 25px;background-color: lightcyan;border-style: ridge; padding:10px 5px; margin: 5px 5px ">
                        <div style="border-radius: 10px; background-color: lightgreen; padding: 5px 5px">
                            <div>
                                <span class="status">
                                        Hodnoceni:

                                <?php $pocet = 0;
                                    if (count($prispevek['hodnoceni']) == 0) { $pocet++; ?>
                                        <span style="background-color: white; margin: 10px; border-radius: 5px">žádné</span>
                                    <?php }
                                foreach ($prispevek['hodnoceni'] as $hodnoceni) {
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
                                    <?php } ?>
                                <?php }
                                if($pocet==0) {?> <span style="background-color: white; margin: 10px; border-radius: 5px">žádné</span> <?php } ?>
                                </span>
                                <?php if($prispevek['akceptovano'] == 0) { ?>
                                    <span class="status" style="background: deepskyblue; color: white">
                                        <i>Status: čeká na posouzení.</i>
                                    </span>
                                <?php }
                                if($prispevek['akceptovano'] == -1) { ?>
                                    <span class="status" style="background: red; color: white">
                                        <i>Status: zamítnuto.</i>
                                    </span>
                                <?php }
                                if($prispevek['akceptovano'] == 1) { ?>
                                    <span class="status" style="background: green; color: white">
                                        <i>Status: akceptováno.</i>
                                    </span>
                                <?php } ?>
                            </div>
                        </div>
                        <u><i><?php echo $prispevek['nazev']?></i></u><br>
                        <b>Abstrakt: </b>
                        <?php echo $prispevek['abstrakt']?>
                        <br>
                        <div class="btn-group" style="position: relative; left: 40%; margin-top: 5px">
                            <button name="zobrazit" value='<?=$prispevek['soubor']?>' type="submit" class="btn btn-primary"><i class="fa fa-file-text"></i> Zobrazit</button>
                            <?php if($tplData['boolZobraz'] and $tplData['zobrClanek']['id_prispevku'] == $prispevek['id_prispevku']) { ?>
                            <button name="skryt" type="submit" class="btn btn-secondary"><i class="fa fa-file-text"></i> Skrýt</button>
                            <?php } ?>
                            <button name="odebrat" value="<?=$prispevek['id_prispevku']?>" type="submit" class="btn btn-outline-danger"><i class="fa fa-close"></i> Odebrat</button>
                        </div>
                        <br>
                        <?php if($tplData['boolZobraz'] and $tplData['zobrClanek']['id_prispevku'] == $prispevek['id_prispevku']) { ?>
                            <iframe style="width: 100%; height: 500px; margin-top: 10px" src="<?=$tplData['zobrClanek']['soubor']?>" frameborder="0"></iframe>
                        <?php }
                        ?>
                    </div>
                <?php
                } } ?>
        </div>
    </form>
</div>




<?php
$tplHeaders->getHTMLFooter();
?>

