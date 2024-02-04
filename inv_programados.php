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
                        <div class="row" style="margin-bottom: 35px;">
                            <div class="col-lg-12">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <h3 class="panel-title"><i class="fa fa-calendar-check-o"></i> Programados</h3>
                                        <span class="pull-right clickable"><i class="fa fa-chevron-up"></i></span>
                                    </div>
                                    <div class="panel-body" style="display: block;">                                        
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <input class="form-control" type="text" id="shPrgsInv" value="" placeholder="Ingresa las fechas..." onkeypress="return false" onkeydown="return false" onkeyup="return false">
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <table class="table table-condensed table-bordered table-hover">
                                                        <thead>
                                                            <tr onselectstart="return false;" onmousedown="return false;" unselectable="on" style="cursor: default;"><th>ASIGNADO</th><th>DEPARTAMENTO</th><th>AREA</th><th>MARCA</th><th>MODELO</th><th>TIPO</th><th>SERIE</th><th>MONITOR</th><th>FECHA</th><th class="centered"><i class="fa fa-clock-o"></i></th></tr>
                                                        </thead>
                                                        <tbody id="progsInv"><?php echo consultas::getServices('period', date("Y-m-d"), date("Y-m-d")); ?></tbody>
                                                    </table> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" role="document" id="ctrTProg" data-backdrop="static" data-keyboard="false">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <h4 class="modal-title"><i class="fa fa-cloud-upload"></i> Finalizar servicio</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="dscProg">Descripcion:</label>
                                                    <textarea class="form-control" rows="5" id="dscProg"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <label for="txtatnProg" class="control-label">Atendio</label>
                                                <select id="txtatnProg" class="form-control">
                                                    <?php echo tools::PrntCmbRsv(false,'all'); ?>
                                                </select>
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="txtfchProg" class="control-label">Fecha</label>
                                                <input id="txtfchProg" type="text" value="" class="form-control" onkeypress="return false" onkeyup="return false" onkeydown="return false">
                                                <input type="hidden" id="idNProg" value="" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" id="btnSProg" class="btn btn-theme"><i class="fa fa-save"></i> Guardar</button>
                                        <button type="button" id="cncSProg" data-dismiss="modal" class="btn btn-default"><i class="fa fa-ban"></i> Cancelar</button>
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
        </body>
    </html>