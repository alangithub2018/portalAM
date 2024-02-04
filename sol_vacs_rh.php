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
                <div class="row" style="padding-bottom:0;">
                    <div class="col-lg-12" style="margin: 0px; padding-bottom: 0;">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-calendar-check-o"></i> <span id="ttlflslvc"></span></h3>
                                <span class="pull-right clickable"><i class="fa fa-chevron-up"></i></span>
                            </div>
                            <div class="panel-body" style="display: block;">
                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                                        <label for="shfolsolvac" class="control-label">Buscar</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="shfolsolvac" placeholder="Ingresa los datos...">
                                            <span class="input-group-btn">
                                                <button id="btnshslvc" class="btn btn-theme" type="button"><i class="fa fa-search"></i></button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                            <label class="control-label" for="slprdsrptvcs">Periodo</label>
                                            <select class="form-control" id="slprdsrptvcs">
                                                <?php echo prntprdsrptvcs($_SESSION['periodo'], '1'); ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                            <label for="seltptrsolvc" class="control-label">Estatus</label>
                                            <select class="form-control selectpicker" id="seltptrsolvc">
                                                <option value="1" data-content=" <span class='badge bg-warning'><i class='fa fa-history'></i> Pendientes</span>"></option>
                                                <option value="2" data-content="<span class='badge bg-danger' style='background-color:#cd0a0a;'><i class='fa fa-ban'></i> Canceladas</span>"></option>
                                                <option value="3" data-content="<span class='badge bg-theme'><i class='fa fa-check'></i> Autorizadas</span>"></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-5 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                            <label for="slenterprise" class="control-label">Empresa</label>
                                            <select id="slenterprise" class="form-control">
                                                <?php echo prntemp(); ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-1 col-md-11" id="octfils">
                                        <div class="form-group">
                                            <label for="filsols" class="control-label">Filtrar</label>
                                            <select id="filsols" class="form-control">
                                                <option value="10">10</option>
                                                <option value="25">25</option>
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-1 col-md-1 pull-right" style="margin-right:-10px; margin-top: -5px;">
                                        <div id="totsols" class="pull-right badge bg-theme04">
                                            <?php echo trim(str_replace("*", "", printsolsvacrh('1', 'Todos', '', '12', TRUE, FALSE, '', ''))) . ' SOLICITUDES'; ?>
                                        </div>
                                        <input id="ttsols" type="hidden" value="<?php echo trim(str_replace("*", "", printsolsvacrh('1', 'Todos', '', '12', TRUE, FALSE, '', ''))); ?>" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12" id="tablesols" style="margin-top:0; padding-top: 0;">
                                        <div class="form-group" style="margin:0;">
                                            <div id="slstbls" class="table-responsive" style="width:100%; overflow-y: auto; margin-top:4px;">
                                                <table class="table table-condensed table-bordered table-condensed table-hover table-striped">
                                                    <caption style="margin-top:0;">SOLICITUDES DE VACACIONES</caption>
                                                    <thead>
                                                        <tr>
                                                            <th class='centered'>Folio</th>
                                                            <th class='centered'>Empleado</th>
                                                            <th class='centered'>Nombre</th>
                                                            <th class='centered'>Periodo</th>
                                                            <th class='centered'>Dia(s)</th>
                                                            <th class="centered">Registro</th>
                                                            <th class="centered">Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="vacstblrh">
                                                        <?php echo printsolsvacrh('1', 'Todos', '', '12', FALSE, TRUE, '1', '10'); ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div id="paginator" class="col-lg-6" style="margin: 0px 0px 0px 15px; padding: 0; max-height: 54px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php echo sysmsg(); ?>
                    <div id="dtlssol" role="dialog" data-backdrop="static" data-keyboard="false" class="modal fade" aria-hidden="true" aria-labelledby="dtlssolLabel" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h4 class="modal-title" id="httledtlsvc"></h4>
                                </div>
                                <div class="modal-body" id="cntdtlssol">
                                </div>
                                <div class="modal-footer">
                                    <button data-dismiss="modal" class="btn btn-default" type="button">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="mtcncsl" role="dialog" data-backdrop="static" data-keyboard="false" class="modal fade" aria-hidden="true" aria-labelledby="mtcncslLabel" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h4 class="modal-title" id="ttlmtvcncsl"></h4>
                                </div>
                                <div class="modal-body" id="cntmtcncsl">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label for="slmtvcnc" class="control-label">
                                                    Motivo
                                                </label>
                                                <select class="form-control" id="slmtvcnc">
                                                    <?php echo prntmtvcncsl(''); ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-theme addmtvcncsl" type="button">Aceptar</button>
                                    <button data-dismiss="modal" class="btn btn-default" type="button">Cancelar</button>
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