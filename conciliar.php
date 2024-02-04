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
        <meta http-equiv="Expires" content="0" />
        <meta http-equiv="Pragma" content="no-cache" />
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
        <title>.::|::. Portal AM .::|::.</title>
        <link href="assets/css/bootstrap.css.php" rel="stylesheet">
        <link rel="shortcut icon"  type="image/png"  href="assets/img/favicon.ico">
        <style>
            .ui-dialog { z-index: 9999 !important ; }
        </style>
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
                            <li id="vclosecom"><a class="logout" id="cierracom" href="assets/includes/closer.php?<?php echo random_system();?>" name="closesystem"><i class="fa fa-sign-out"></i > Salir</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </header>
        <aside>
            <div id="sidebar"  class="nav-collapse">
                <ul class="sidebar-menu" id="nav-accordion">
                    <p class="centered"><a href="#"><img src="assets/img/ui-sam.png" class="img-circle" width="60"></a></p>
                    <h5 class="centered" style="font-weight: 700;"><u><?php echo utf8_encode($_SESSION['nameuser']);?></u></h5>
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
                                <h3 class="panel-title"><i class="fa fa-clock-o"></i> Conciliar periodos</h3>
                                <span class="pull-right clickable"><i class="fa fa-chevron-up"></i></span>
                            </div>
                            <div class="panel-body" style="display: block;">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="slcompany" class="control-label">Empresa</label>
                                            <select id="slcompany" class="form-control">
                                                <?php echo prntemp(); ?>
                                            </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                  <label for="shemploy" class="control-label">Empleado</label>
                                  <div class="input-group">
                                      <input type="text" class="form-control round-form" id="shemploy" placeholder="Teclea el empleado..." style="z-index:0;">
                                    <span class="input-group-btn">
                                        <button id="btnshemploy" class="btn btn-theme round-form" type="button"><i class="fa fa-history fa-lg"></i></button>
                                    </span>
                                  </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="col-lg-6" id="nameus"></div>
                                    <div class="col-lg-6" id="ingus" style="text-align:right;"></div>
                                </div>
                                <div style="max-height: 390px; width: 100%; overflow: auto;">
                                    <div id="tblconcprds" class="table-responsive" style="display:none;">
                                    <table class="table table-bordered table-striped table-condensed table-hover">
                                        <caption style="margin-top:0;">TABULADOR DE PERIODOS</caption>
                                        <thead>
                                        <tr>
                                            <th class='centered'>A&ntilde;o</th>
                                            <th class='centered'>Periodo</th>
                                            <th class='centered'>Con derecho a</th>
                                            <th class='centered'>Disfrutados</th>
                                            <th class='centered'>Por disfrutar</th>
                                            <th class='centered'>Por autorizar</th>
                                            <th class="centered">Vigencia</th>
                                            <th class="centered" width="90">Accion</th>
                                        </tr>
                                        </thead>
                                        <tbody id="tblconcvacs">
                                        </tbody>
                                    </table>
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