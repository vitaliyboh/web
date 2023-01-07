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
        if(isset($_POST['prihlasit'])){

            if($tplData['povedloSe']){
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
                            window.location.replace("index.php?page=prihlaseni");
                        }
                    });
                </script>
                <?php
            }
        }
        ?>


<div class="container-fluid">
    <div class="wrapper" >
        <form action ="" class="form-signin" method="post">
            <h2 class="form-signin-heading">Přihlásit se</h2>

            <span class="fa fa-users fa"></span>
            <label for="login" class=""></label>
            <input type="text" id="login" class="form-control" name="login" placeholder="Login" required="" autofocus="" />
            <br>

            <span class="fa fa-lock fa-lg"></span>
            <label for="password" class=""></label>
            <input type="password" id="password" class="form-control" name="password" placeholder="Heslo" required=""/>

            <label for="btn" class=""></label>
            <button name="prihlasit" id="btn" class="btn btn-lg btn-primary btn-block" type="submit" value="prihlasit">Přihlásit</button>

        </form>
    </div>
</div>

