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
        <style> .ui-dialog { z-index: 9999 !important ; } </style>
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
                                <h3 class="panel-title"><i class="fa fa-user"></i> Usuarios</h3>
                                <span class="pull-right clickable"><i class="fa fa-chevron-up"></i></span>
                            </div>
                            <div class="panel-body" style="display: block;">
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="shusrinsys" class="control-label">Buscar</label>
                                        <div class="input-group">
                                            <input type="text" id="shusrinsys" style="z-index: 1;" class="form-control" placeholder="Ingresa el dato..."/>
                                            <span class="input-group-btn">
                                                <a id="btnshusers" class="btn btn-default"><i class="fa fa-search"></i></a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="est_usr" class="control-label">Estatus</label>
                                        <select class="form-control" id="est_usr">
                                            <option value="1" selected="selected">Vigente</option>
                                            <option value="0">Baja</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="control-label">Empresa</label>
                                        <select id="fempusrs" class="form-control"><?php echo prntemp(); ?></select>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="control-label">Ordenar</label>
                                        <div class="input-group">
                                            <select id="slordusrs" class="form-control"><?php echo prntclusr();?></select>
                                            <span class="input-group-btn">
                                                <button id="gen_rptusrs" class="btn btn-default" type="button"><i style="color: red;" class="fa fa-file-pdf-o fa-lg"></i></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12" style="max-height: 410px; overflow-x: auto; overflow-y: auto;">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped table-condensed table-hover" style="cursor: default;">
                                                <caption style="margin-top:0;">EMPLEADOS</caption>
                                            <thead>
                                                <tr>
                                                    <th width="5%" style="text-align: center;"><span class="badge bg-important"><i class="fa fa-database fa-xs"></i></span></th>
                                                    <th width="6%" style="text-align: center;">CODIGO</th>
                                                    <th width="9%">NSS</th>
                                                    <th width="35%">NOMBRE</th>
                                                    <th width="30%">EMAIL</th>
                                                    <th width="10%" style="text-align: center;">INGRESO</th>
                                                    <th width="5%" style="text-align: center;"><i class="fa fa-group fa-xs"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody id="tblusers">
                                                <?php echo consultas::prntusers('','codigo',0,'1','0'); ?>
                                            </tbody>
                                        </table>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php echo sysmsg(); ?>
            </section>
        </section>
        <footer class="footer" style="position: fixed; z-index: 1;">
            <div style="text-align: center; margin-top: 0.8%;">Todos los derechos reservados &copy; 2015 Periodico AM.</div>
            <a class="go-top animated fadeInRight"><i></i></a>
        </footer>
    </section>
    <script src="assets/js/jquery-1.10.2.min.js.php"></script>
    <script src="assets/js/bootstrap.min.js.php"></script>
    <script src="assets/js/jquery-ui.min.js.php" type="text/javascript"></script>
    <?php echo tools::prntmdledtusers(); ?>
    </body>
    </html>