<?php
global $tplData;

// pripojim objekt pro vypis hlavicky a paticky HTML
require(DIRECTORY_VIEWS ."/TemplateBasics.class.php");
$tplHeaders = new TemplateBasics();

// hlavicka
$tplHeaders->createHeader("app/styles/prihlaseni.css");
?>
<body>
<?php
if (!$tplData['userLogged']) {
    $tplHeaders->createNav($tplData['pravo']);
} else {
    $tplHeaders->createNav($tplData['pravo'],"odhlaseni");
}
?>

<form class="form-signin text-center d-flex" method="post">
    <div class="row justify-content-center align-self-center">

        <?php
        if(isset($_POST['registruj'])){
            if($tplData['oka']){
                ?>
                <script type="text/javascript">
                    Swal.fire({
                        icon: 'success',
                        title: '<?php echo $tplData['info'] ?>',
                        showConfirmButton: true,
                        allowOutsideClick: false,
                        confirmButtonText: `OK`,
                        customClass: {
                            confirmButton: 'order-1',
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.replace("index.php?page=uvod");
                        }
                    });
                </script>
            <?php
            } else {
            ?>
                <script type="text/javascript">
                    Swal.fire({
                        icon: 'error',
                        title: '<?php echo $tplData['info'] ?>',
                        showConfirmButton: true,
                        allowOutsideClick: false,
                        confirmButtonText: `OK`,
                        customClass: {
                            confirmButton: 'order-1',
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.replace("index.php?page=registrace");
                        }
                    });
                </script>
                <?php
            }
        }
        ?>
        <div class="container" >
            <div class="wrapper" >
                <h2 class="form-signin-heading">Registrace</h2>

                <span class="fa fa-user fa"></span>
                <label for="inputUsername" class=""></label>
                <input type="text" name="jmeno" id="inputUsername" class="form-control" placeholder="Zadejte celé jméno" required autofocus>
                <br>

                <span class="fa fa-users fa"></span>
                <label for="inputLogin" class=""></label>
                <input type="text" name="login" id="inputLogin" class="form-control" placeholder="Zadejte login" required>
                <br>

                <span class="fa fa-envelope fa"></span>
                <label for="inputEmail" class=""></label>
                <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Zadejte email" required >
                <br>

                <span class="fa fa-lock fa-lg"></span>
                <label for="inputPassword" class=""></label>
                <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Zadejte heslo" required>

                <label for="btn" class=""></label>
                <button id="btn" class="btn btn-lg btn-primary btn-block" type="submit" name="registruj" value="registruj">Registruj se</button>
            </div>
        </div>



    </div>
</form>

</body>