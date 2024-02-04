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
                                        <h3 class="panel-title"><i class="fa fa-thumb-tack"></i> Servicios</h3>
                                        <span class="pull-right clickable"><i class="fa fa-chevron-up"></i></span>
                                    </div>
                                    <div class="panel-body" style="display: block;">                                        
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label for="selType" class="control-label">Tipo</label>
                                                <select id="selType" class="dropup form-control" title="Selecciona..">
                                                    <option data-icon="fa fa-warning" value="PREVENTIVO">Preventivo</option>
                                                    <option data-icon="fa fa-wrench" value="CORRECTIVO">Correctivo</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label for="srvdpto" class="control-label">Departamento</label>
                                                <select id="srvdpto" class="form-control">
                                                <?php consultas::selCtrlsInv('departamento'); ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label for="srvarea" class="control-label">Area</label>
                                                <select id="srvarea" class="form-control"></select>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label for="srvasig" class="control-label">Asignado</label>
                                                <select id="srvasig" class="form-control"></select>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label for="srvmrc" class="control-label">Marca</label>
                                                <select id="srvmrc" class="form-control" disabled="disabled"></select>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label for="srvmod" class="control-label">Modelo</label>
                                                <select id="srvmod" class="form-control" disabled="disabled"></select>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label for="srvdsp" class="control-label">Dispositivo</label>
                                                <select id="srvdsp" class="form-control" disabled="disabled"></select>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label for="srvserie" class="control-label">Serie</label>
                                                <select id="srvserie" class="form-control" disabled="disabled"></select>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label for="dSrvInv">Descripcion:</label>
                                                <textarea class="form-control" rows="5" id="dSrvInv"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label for="srvfch">Fecha</label>
                                                <input id="srvfch" type="text" class="form-control" onkeydown="return false" onkeypress="return false" onkeyup="return false">
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label for="srvatnd">Atendió</label>
                                                <select id="srvatnd" class="form-control">
                                                    <?php echo tools::PrntCmbRsv(false,'all'); ?>
                                                </select>
                                            </div>
                                            <input type="hidden" id="ctrUpdSrvs" />
                                        </div>
                                        <div class="col-lg-6" style="padding-top: 25px; padding-bottom: 0; margin-bottom: 0;">
                                            <div class="form-group pull-right">
                                                <button id="btnSvSrv" class="btn btn-theme" style="margin-right: 8px;" type="button"><i class="fa fa-save"></i> Guardar</button>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 table-responsive" id="gtSrvs" style="display: none;">
                                            <div class="form-group">
                                                <table class="table table-condensed table-bordered table-hover">
                                                    <thead>
                                                        <tr onselectstart="return false;" onmousedown="return false;" unselectable="on" style="cursor: default;"><th>TIPO</th><th>DESCRIPCION</th><th>FECHA</th><th>ATENDIO</th></tr>
                                                    </thead>
                                                    <tbody id="chrgSrvs"></tbody>
                                                </table>
                                            </div>
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
        </body>
    </html>