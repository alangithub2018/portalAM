<?php include_once "assets/includes/funciones.php"; if(isset($_POST['userid']) and isset($_POST['passuser'])){$usreval=$_POST['userid'];$psweval=$_POST['passuser']; $rteval=evalualogin($usreval,$psweval);} ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="description" content="Portal AM" />
        <meta name="author" content="Sistemas AM" />
        <meta name="keyword" content="Sistema, Portal AM, Servicios" />
        <meta http-equiv="cache-control" content="max-age=0" />
        <meta http-equiv="cache-control" content="no-cache, no-store, must-revalidate" />
        <meta http-equiv="Expires" content="0" />
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
            <?php echo printlogin();?>
        </header>
        <aside>
            <div id="sidebar"  class="nav-collapse">
                <ul class="sidebar-menu" id="nav-accordion">
                    <?php echo tools::appMenu($_SESSION['gpouser']);?>
                </ul>
            </div>
        </aside>
        <section id="main-content">
            <section class="wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-user"></i> Datos de empleado</h3>
                                <span class="pull-right clickable"><i class="fa fa-chevron-up"></i></span>
                            </div>
                            <div class="panel-body" style="display: block;" id="ppal1">
                                <div class="form-group">
                                    <div class="col-lg-12">
                                        <label class="control-label">N.S.S.</label>
                                        <div class="input-group">
                                            <input type="text" id="vnss" style="z-index:0;" class="form-control round-form" placeholder="Numero de seguridad social" aria-describedby="basic-addon" maxlength="11">
                                            <a class="input-group-addon btn btn-primary round-form" id="ingnss">Ingresar</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-12">
                                        <br>
                                        <div id="msgalert" class="alert alert-danger alert-dismissable" style="display: none;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php echo sysmsg(); ?>
                </div>
                <div class="row" id="verificado" style="display: none;">
                    <div class="col-lg-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-key"></i> Sesion de usuario</h3>
                                <span class="pull-right clickable"><i class="fa fa-chevron-up"></i></span>
                            </div>
                            <div class="panel-body" id="datos" style="display: block;">
                                <div id="contenido"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </section>
        <footer class="footer" style="position: fixed;">
            <div style="text-align: center; margin-top: 0.8%;">Todos los derechos reservados &copy; 2015 Periodico AM.</div>
            <a class="go-top animated fadeInRight"><i></i></a>
        </footer>
    </section>
    <script src="assets/js/jquery-1.10.2.min.js.php"></script>
    <script src="assets/js/bootstrap.min.js.php"></script>
    <script src="assets/js/jquery-ui.min.js.php" type="text/javascript"></script>
    <?php if(isset($_SESSION['usercorrect'])){echo prntloading(); }else{echo printerrorlog().msgerrlogrcpas().prntloading(); }?>
    </body>
    </html>