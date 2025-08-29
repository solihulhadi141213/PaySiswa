<?php
    include "_Config/Connection.php";
    include "_Config/GlobalFunction.php";
    include "_Config/Session.php";
    if(empty($SessionIdAkses)){
        header("Location:Login.php");
    }else{
        //Apabila Login Berrhasil
        include "_Config/FungsiAkses.php";
        include "_Config/SettingGeneral.php";
        include "_Config/Notifikasi.php";
?>
    <!DOCTYPE html>
    <html lang="en">
        <head>
            <?php
                include "_Partial/Head.php";
            ?>
        </head>
        <body>
            <header id="header" class="header fixed-top d-flex align-items-center nav_background">
                <?php
                    include "_Partial/DashboardLogo.php";
                    include "_Partial/DashboardSearch.php";
                ?>
                <nav class="header-nav ms-auto">
                    <ul class="d-flex align-items-center">
                        <?php
                            include "_Partial/IconSearch.php";
                            include "_Partial/Notifikasi.php";
                            include "_Partial/NotifikasiPesan.php";
                            include "_Partial/Profile.php";
                        ?>
                    </ul>
                </nav>
            </header>
            <?php
                include "_Partial/Menu.php";
            ?>
            <main id="main" class="main">
                <?php
                    include "_Partial/RoutingPage.php";
                    include "_Partial/Modal.php";
                ?>
            </main>
            <?php
                include "_Partial/Copyright.php";
                include "_Partial/FooterJs.php";
                include "_Partial/RoutingJs.php";
                include "_Partial/RoutingSwal.php";
            ?>
        </body>
    </html>
<?php } ?>