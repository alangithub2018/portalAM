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
                                    <li id="vclosecom"><a class="logout" id="cierracom" href="assets/includes/closer.php?<?php echo random_system(); ?>" name="closesystem"><i class="fa fa-sign-out"></i > Salir</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </header>
                <aside>
                    <div id="sidebar"  class="nav-collapse">
                        <ul class="sidebar-menu" id="nav-accordion">
                            <p class="centered"><a href="#"><img src="assets/img/ui-sam.png" class="img-circle" width="60"></a></p>
                            <h5 class="centered" style="font-weight: 700;"><u><?php echo utf8_encode($_SESSION['nameuser']); ?></u></h5>
                            <?php echo tools::appMenu($_SESSION['gpouser']); ?>
                        </ul>
                    </div>
                </aside>
                <section id="main-content">
                    <section class="wrapper">
                        <div class="row" style="margin-bottom: 10px;">
                            <div class="col-lg-12">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <h3 class="panel-title"><i class="fa fa-folder-open"></i> Registros</h3>
                                        <span class="pull-right clickable"><i class="fa fa-chevron-up"></i></span>
                                    </div>
                                    <div class="panel-body" style="display: block;">                                        
                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <div class="form-group">
                                                    <label for="shRegsSpt" class="control-label">Filtro de Fechas</label>
                                                    <input class="form-control" type="text" id="shRegsSpt" value="" placeholder="Ingresa las fechas..." onkeypress="return false" onkeydown="return false" onkeyup="return false">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <div class="form-group">
                                                    <label for="filusr" class="control-label">Resuelve</label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-user-circle-o"></i></span>
                                                        <select id="filusr" class="form-control">
                                                            <option value="x">Todos</option>
                                                            <?php echo consultas::getAtndSpt(); ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-11" id="octregs">
                                                <div class="form-group">
                                                    <label for="filregs" class="control-label">Filtrar</label>
                                                    <select id="filregs" class="form-control">
                                                        <option value="10">10</option>
                                                        <option value="25">25</option>
                                                        <option value="50">50</option>
                                                        <option value="100">100</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 pull-right" style="margin-right:-10px; margin-top: -5px;">
                                                <div id="totregs" class="pull-right badge bg-theme04">
                                                    <?php echo trim(str_replace("|","",consultas::getSpt('', false, false, 'tblRegsSpt','','','',true,false,'',''))) . ' REGISTROS'; ?>
                                                </div>
                                                <input id="ttregs" type="hidden" value="<?php echo trim(str_replace("|","",consultas::getSpt('', false, false, 'tblRegsSpt','','','',true,false,'',''))); ?>" />
                                            </div>
                                            </div>
                                            <div class="row">
                                            <div class="col-lg-12" id="tableregs" style="margin-top:0; padding-top: 0;">
                                                <div class="form-group" style="margin:0;">
                                                    <div id="tlregs" class="table-responsive" style="width:100%; overflow: auto; margin-top:4px;">
                                                        <table class="table table-condensed table-bordered table-condensed table-hover table-striped">
                                                            <caption style="margin-top:0;">REGISTROS DE SOPORTE</caption>
                                                            <thead>
                                                                <tr>
                                                                    <th class="centered">REGISTRO</th>
                                                                    <th>USUARIO</th>
                                                                    <th>DEPARTAMENTO</th>
                                                                    <th>AREA</th>
                                                                    <th>CATEGORIA</th>
                                                                    <th>PROBLEMA</th>
                                                                    <th>F.SOLUCION</th>
                                                                    <th>RESUELVE</th>
                                                                    <th>SOLUCI&Oacute;N</th>
                                                                    <th>ESTATUS</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="tblRegsSpt" style="font-size:0.865em;">
                                                                <?php echo consultas::getSpt('', false, false, 'tblRegsSpt','','','',false,true,'1','10'); ?>
                                                            </tbody>
                                                        </table>
                                                    </div> 
                                                </div>
                                            </div>
                                            <div id="paginator2" class="col-lg-6" style="margin: 0px 0px 0px 15px; padding: 0; max-height: 54px;"></div>
                                        </div>
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
            <script src="assets/js/jquery.bootpag.js.php"></script>
        </body>
    </html>