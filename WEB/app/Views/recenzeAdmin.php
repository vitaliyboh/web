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
            width: 100px;
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
    <h2 style="text-align: center"> <?= $tplData['title'] ?> </h2>
    <div style=" border-style: groove; padding: 10px 5px; margin-top: 10px; background-color: linen">
        <form method="post">
            <?php
            if (count($tplData['clanky']) == 0) { ?>
                <h4><b>Dosud žádné přispěvky</b></h4>
            <?php }
            else {
                foreach ($tplData['clanky'] as $clanek) {?>
                    <div style="position: relative; border-radius: 25px;background-color: lightcyan;border-style: ridge; padding:10px 5px; margin: 5px 5px ">


                        <?php if($clanek['akceptovano'] == 0) { ?>
                            <span class="status" style="background: deepskyblue; color: white; padding-left: 5px; padding-right: 5px">
                                        <i> Čeká na posouzení </i>
                                    </span>
                        <?php }
                        if($clanek['akceptovano'] == -1) { ?>
                            <span class="status" style="background: red; color: white; padding-left: 5px; padding-right: 5px">
                                        <i>Článek zamítnut </i>
                                    </span>
                        <?php }
                        if($clanek['akceptovano'] == 1) { ?>
                            <span class="status" style="background: green; color: white; padding-left: 5px; padding-right: 5px">
                                        <i> Článek akceptován </i>
                                    </span>
                        <?php } ?>
                        <div style="margin-top: 5px">
                            <div class="row" style="margin-top: 10px">
                                <div class="col-lg-9">
                                    <u style="padding-left: 10px"><i> <?php echo $clanek['autor'] ?>:</i> <?php echo $clanek['nazev']?></u>
                                </div>
                                <div>
                                    <div class="btn-group btn-group col-sm-12 col-lg-3" style="margin-left: 10px" >
                                        <button type="submit" name="schvalit" value="<?= $clanek['id_prispevku'] ?>" class="btn btn-success"><i class="fa fa-check-circle-o"></i> Schválit</button>
                                        <button type="submit" name="zamitnout" value="<?= $clanek['id_prispevku'] ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i> Zamítnout</button>

                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="btn-group" style="position: relative; left: 1%; margin-top: 5px">
                            <button name="zobrazit" value='<?=$clanek['soubor']?>' type="submit" class="btn btn-primary"><i class="fa fa-file-text"></i> Zobrazit</button>
                            <?php if($tplData['boolZobraz'] and $tplData['zobrClanek']['id_prispevku'] == $clanek['id_prispevku']) { ?>
                                <button name="skryt" type="submit" class="btn btn-secondary"><i class="fa fa-file-text"></i> Skrýt</button>
                            <?php } ?>
                        </div>
                        <br>
                        <?php if($tplData['boolZobraz'] and $tplData['zobrClanek']['id_prispevku'] == $clanek['id_prispevku']) { ?>
                            <iframe style="width: 100%; height: 500px; margin-top: 10px" src="<?=$tplData['zobrClanek']['soubor']?>" frameborder="0"></iframe>
                        <?php }
                        ?>

                        <div style="padding-left: 10px; margin-top: 5px">
                            Recenze: <br>
                            <?php
                            if(count($clanek['hodnoceni']) < 3)  {?>
                            <label for="recenzenti" style="background-color: lightgray; float: left; padding: 3px 3px; border-radius: 2px"> Přidat recenzenta </label>
                            <select id="recenzenti" name="recenzenti<?= $clanek['id_prispevku']?>" style="float: left; padding: 3px 3px; width: 200px">

                                <?php
                                    foreach ($tplData['recenzenti'] as $recenzent) {
                                        $ok = true;
                                        foreach ($clanek['hodnoceni'] as $hodnoceni) {
                                            if ($recenzent['id_uzivatele'] == $hodnoceni['id_uzivatele']) {
                                                $ok = false;
                                            }
                                        }
                                        if($ok) { ?>
                                            <option value="<?= $recenzent['id_uzivatele'].','.$clanek['id_prispevku'] ?>"><?= $recenzent['jmeno'] ?></option>
                                        <?php }
                                    }
                                    ?>

                            </select>
                            <button type="submit" name="pridej<?= $clanek['id_prispevku']?>" class="btn btn-success btn-sm">Přidat</button>
                            <?php } ?>
                        </div>
                        <div class="table-responsive" style="padding-left: 10px">
                            <table class="table table-bordered table-sm" style="width: fit-content; margin-top: 10px">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Celkem</th>
                                    <th>Obsah</th>
                                    <th>Formálně</th>
                                    <th>Novost</th>
                                    <th>Jazyk</th>
                                    <th>Akce</th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php
                                if (count($clanek['hodnoceni']) == 0) { ?>
                                    <tr>
                                        <td colspan="7">
                                            <div style="background-color: lightyellow; padding: 10px 15px; color: brown">
                                                Přiřaďte 3 recenzenty.
                                            </div>
                                        </td>
                                    </tr>
                                <?php }
                                else {
                                    foreach ($clanek['hodnoceni'] as $hodnoceni) {?>
                                        <tr>
                                            <td><b><?= $hodnoceni['recenzent']?></b></td>
                                            <?php if($hodnoceni['hvezdy'] != -1) { ?>
                                                <td>
                                                    <span class="hodnoceni">
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
                                                </td>
                                                <td>
                                                    <span class="hodnoceni">
                                                        <?php
                                                        for($i=0; $i<5; $i++) {
                                                            if ($hodnoceni['kvalita_obsahu'] - $i >=1) {?>
                                                                <i class="fa fa-star"></i>
                                                            <?php }
                                                            elseif ($hodnoceni['kvalita_obsahu'] - $i >0) {?>
                                                                <i class="fa fa-star-half-o"></i>
                                                            <?php }
                                                            else {?>
                                                                <i class="fa fa-star-o"></i>
                                                            <?php }
                                                        } ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="hodnoceni">
                                                        <?php
                                                        for($i=0; $i<5; $i++) {
                                                            if ($hodnoceni['formalni_uroven'] - $i >=1) {?>
                                                                <i class="fa fa-star"></i>
                                                            <?php }
                                                            elseif ($hodnoceni['formalni_uroven'] - $i >0) {?>
                                                                <i class="fa fa-star-half-o"></i>
                                                            <?php }
                                                            else {?>
                                                                <i class="fa fa-star-o"></i>
                                                            <?php }
                                                        } ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="hodnoceni">
                                                        <?php
                                                        for($i=0; $i<5; $i++) {
                                                            if ($hodnoceni['novost'] - $i >=1) {?>
                                                                <i class="fa fa-star"></i>
                                                            <?php }
                                                            elseif ($hodnoceni['novost'] - $i >0) {?>
                                                                <i class="fa fa-star-half-o"></i>
                                                            <?php }
                                                            else {?>
                                                                <i class="fa fa-star-o"></i>
                                                            <?php }
                                                        } ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="hodnoceni">
                                                        <?php
                                                        for($i=0; $i<5; $i++) {
                                                            if ($hodnoceni['kvalita_jazyku'] - $i >=1) {?>
                                                                <i class="fa fa-star"></i>
                                                            <?php }
                                                            elseif ($hodnoceni['kvalita_jazyku'] - $i >0) {?>
                                                                <i class="fa fa-star-half-o"></i>
                                                            <?php }
                                                            else {?>
                                                                <i class="fa fa-star-o"></i>
                                                            <?php }
                                                        } ?>
                                                    </span>
                                                </td>
                                            <?php }
                                            else { ?>
                                                <td colspan="5">
                                                    <div class="status" style="background-color: darkcyan; padding: 3px 5px; color: white">
                                                        čeká na hodnocení
                                                    </div>
                                                </td>
                                            <?php } ?>
                                            <td>
                                                <button type="submit" name="odstranit" value="<?= $hodnoceni['id_hodnoceni'] ?>" class="btn btn-outline-danger"><i class="fa fa-close"></i></button>
                                            </td>
                                            <?php } ?>

                                        <?php if(count($clanek['hodnoceni']) == 1) {?>
                                            <tr>
                                                <td colspan="7">
                                                    <div style="background-color: lightyellow; padding: 10px 15px; color: brown">
                                                        Přiřaďte ještě 2 recenzenty.
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php }
                                        if (count($clanek['hodnoceni']) == 2) {?>
                                                <tr>
                                                    <td colspan="7">
                                                        <div style="background-color: lightyellow; padding: 10px 15px; color: brown">
                                                            Přiřaďte ještě 1 recenzenta.
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tr>
                                <?php }
                                ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                <?php } }
            ?>

        </form>
    </div>
</div>

<?php
$tplHeaders->getHTMLFooter();
?>
