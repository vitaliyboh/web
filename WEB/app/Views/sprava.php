<?php
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

<div class="container table-responsive">
    <h2 style="text-align: center">Správa uživatelů</h2>
    <form method="post">
        <table class="table table-hover">
            <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Login</th>
                <th>Jméno</th>
                <th>Email</th>
                <th>Role</th>
                <th>Akce</th>
            </tr>
            </thead>
            <tbody>
                <?php
                foreach ($tplData['uzivatele'] as $uzivatel) {?>
                    <tr class="table-primary">
                        <td><?=$uzivatel['id_uzivatele']?></td>
                        <td><?=$uzivatel['login']?></td>
                        <td><?=$uzivatel['jmeno']?></td>
                        <td><?=$uzivatel['email']?></td>

                        <td><?=$uzivatel['pravo']?> <br>

                            <?php if($tplData['pravo'] == 1 and $tplData['pravo']< $uzivatel['idprava']) { ?>
                                    <div class="input-group mb-2" style="width: fit-content">
                                        <select name="vybranePravo<?=$uzivatel['id_uzivatele'] ?>" class="custom-select" id="inputGroupSelect02">
                                            <option value="4">Autor</option>
                                            <option value="3">Recenzent</option>
                                            <option value="2">Admin</option>
                                        </select>
                                    </div>

                                    <button class='btn btn-primary' type='submit' value='<?=$uzivatel['id_uzivatele']?>' name='zmenit<?= $uzivatel['id_uzivatele'] ?>' id='<?=$uzivatel['id_uzivatele']?>'>Změnit</button>
                            <?php }
                            elseif($uzivatel['idprava'] == 3 or $uzivatel['idprava'] == 4) { ?>
                                <div class="input-group mb-2" style="width: fit-content">
                                    <select name="vybranePravo<?=$uzivatel['id_uzivatele'] ?>" class="custom-select" id="inputGroupSelect02">
                                        <option value="4">Autor</option>
                                        <option value="3">Recenzent</option>
                                    </select>
                                </div>

                                <button class='btn btn-primary' type='submit' value='<?=$uzivatel['id_uzivatele']?>' name='zmenit<?= $uzivatel['id_uzivatele'] ?>' id='<?=$uzivatel['id_uzivatele']?>'>Změnit</button>
                                <?php
                                } ?>
                        </td>
                        <?php
                        if ($uzivatel['idprava']>$tplData['pravo']) {?>
                        <td><button class='btn btn-danger' type='submit' value='<?=$uzivatel['id_uzivatele']?>' name='Odstranit' id='<?=$uzivatel['id_uzivatele']?>'>Odstranit</button></td>
                        <?php
                        }
                        else { ?> <td></td> <?php } ?>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </form>
</div>

<?php $tplHeaders->getHTMLFooter(); ?>
