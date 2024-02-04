<?php include_once "assets/includes/funciones.php";?>
    <!DOCTYPE html>
    <html lang="es">
        <head>
            <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta name="description" content="">
            <meta name="author" content="Sistemas AM">
            <meta name="keyword" content="Sistema, Portal AM, Servicios">
            <meta http-equiv="cache-control" content="max-age=0" />
            <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
            <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
            <meta http-equiv="Expires" content="0" />
            <meta http-equiv="Pragma" content="no-cache" />
            <title>.::|::. Portal AM .::|::.</title>
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
                    <div class="top-menu">
                        <ul class="nav-collapse navbar-right" style="margin-top: 16px; margin-right: 2px;">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-gears fa-2x"></i> <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="#"><i class="fa fa-windows"></i>&nbsp;Perfil</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li id="vclosecom"><a class="logout" id="cierracom" href="assets/includes/closer.php?<?php echo random_system(); ?>" name="closesystem"><i class="fa fa-sign-out"></i > Salir</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </header>
                <aside>
                    <div id="sidebar" class="nav-collapse">
                        <ul class="sidebar-menu" id="nav-accordion">
                            <p class="centered"><a href="#"><img src="assets/img/ui-sam.png" class="img-circle" width="60"></a></p>
                            <h5 class="centered" style="font-weight: 700;"><u><?php echo utf8_encode($_SESSION['nameuser']); ?></u></h5>
                        <?php echo tools::appMenu($_SESSION['gpouser']); ?>
                        </ul>
                    </div>
                </aside>
                <section id="main-content">
                    <section class="wrapper">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <h3 class="panel-title"><i class="fa fa-briefcase"></i> Catalogos</h3>
                                        <span class="pull-right clickable"><i class="fa fa-chevron-up"></i></span>
                                    </div>
                                    <div class="panel-body" style="display: block;">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label for="txtmtvcanc" class="control-label">Descipcion</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="txtmtvcanc" placeholder="Ingresa el motivo..." />
                                                    <span class="input-group-btn">
                                                        <a id="btnnmtvvcs" class="btn btn-default"><i class="fa fa-save"></i></a>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 table-responsive" style="max-height: 250px; overflow-y: auto; overflow-x: auto;">
                                            <div class="form-group">
                                            <table class="table table-bordered table-striped table-condensed">
                                                <caption style="margin-top: 0;">MOTIVOS DE CANCELACION</caption>
                                                <thead>
                                                    <tr><th width="85%">Motivo</th>
                                                        <th width="15%" style="text-align: center;">Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="recmtvcncvcs">
                                                  <?php echo trim(consultas::prntmtvstblvcs()); ?>
                                                </tbody>
                                            </table>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <h5 style="font-weight: bold;">Festivos en sistema</h5>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label for="dyfest">Calendario</label>
                                                <div id="dyfest"></div>
                                                <input type="hidden" id="hddcalenfst" value="">
                                            </div>
                                        </div>
                                        <div class="col-lg-3" style="margin-top: 10%; margin-left: auto; margin-right: auto; text-align: center;">
                                            <button id="btnlockdt" class="btn btn-default" type="button"><i class="fa fa-forward fa-lg"></i></button>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="dtslock" class="control-label">Bloqueos</label>
                                                <select id="dtslock" class="form-control" size="12">
                                                    <?php echo selectfest(); ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php echo sysmsg(); ?>
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
        </body>
    </html>