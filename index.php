<?php include_once "assets/includes/funciones.php"; if(isset($_POST['userid']) and isset($_POST['passuser'])){ $usreval=$_POST['userid']; $psweval=$_POST['passuser']; $rteval=evalualogin($usreval,$psweval); } $dir_avisos="assets/img/sliders/"; $archivos=glob($dir_avisos."*.jpg"); $total=count($archivos); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Portal AM" />
    <meta name="author" content="Sistemas AM">
    <meta name="keyword" content="Sistema, Portal AM, Servicios" />
    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="cache-control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Expires" content="-1">
    <meta http-equiv="Pragma" content="no-cache" />
    <title>.::|::. Portal AM .::|::.</title>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link href="assets/css/bootstrap.css.php" rel="stylesheet">
    <link rel="shortcut icon"  type="image/png"  href="assets/img/favicon.ico">
</head>
<body>
    <section id="container">
    <header class="header">
        <div class="sidebar-toggle-box">
            <div id="navigator" class="fa fa-windows"></div>
        </div>
        <a href="index.php" class="logo"><img src="assets/img/logo.png" width="136" height="33"></a>
        <?php echo printlogin(); ?>
    </header>
    <aside>
        <div id="sidebar" class="nav-collapse">
            <ul class="sidebar-menu" id="nav-accordion">
                <?php if(isset($_SESSION['nameuser']) and !empty($_SESSION['nameuser'])){?>
                    <p class="centered"><a href="#"><img src="assets/img/ui-sam.png" class="img-circle" width="60"></a></p>
                    <h5 class="centered" style="font-weight: 700;"><u><?php echo utf8_encode($_SESSION['nameuser']); ?></u></h5>
                <?php } echo tools::appMenu($_SESSION['gpouser']); ?>
            </ul>
        </div>
    </aside>
    <section id="main-content" style="padding-top: 55px; padding-bottom: 35px;">
        <section>
            <?php if($total > 0){ ?>
                <div id="carousel-example-generic" class="carousel slide" data-ride="carousel" data-interval="15000">
                        <ol class="carousel-indicators">
                            <?php
                            for($i=0;$i<$total;$i++){
                                echo '<li data-target="#carousel-example-generic" data-slide-to="'.$i.'"';
                                if($i==0){ echo ' class="active">'; }else{ echo ">"; }echo "</li>";
                            } ?>
                        </ol>
                        <div class="carousel-inner">
                            <?php
                            for($y=0;$y<$total;$y++){
                                echo '<div class="item ';
                                if($y==0){echo 'active';}
                                echo '"><img src="'.$archivos[$y].'" class="img-responsive" /></div>';}
                            ?>
                        </div>
                        <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
                            <span class="icon-prev"></span>
                        </a>
                        <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
                            <span class="icon-next"></span>
                        </a>
                    </div>
            <?php } ?>
        </section>
    </section>
    <footer class="footer" style="position: fixed;">
        <div style="text-align: center; margin-top: 0.8%;">Todos los derechos reservados &copy; 2015 Periodico AM.</div>
        <a class="go-top animated fadeInRight"><i></i></a>
    </footer>
</section>
<script src="assets/js/jquery-1.10.2.min.js.php"></script>
<script src="assets/js/bootstrap.min.js.php"></script>
<?php if(isset($_SESSION['usercorrect'])){echo prntloading().tools::prntmdlpltcs(); }else{echo printerrorlog().msgerrlogrcpas().prntloading(); }?>
</body>
</html>