<?php include_once "assets/includes/funciones.php"; actpersvac($_SESSION['idloguser']);?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="Sistemas AM">
        <meta name="keyword" content="Sistema, Portal AM, Servicios">
        <meta http-equiv="Cache-Control" content="max-age=0" />
        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
        <meta http-equiv="Expires" content="0" />
        <meta http-equiv="Pragma" content="no-cache" />
        <title>.::|::. Portal AM .::|::.</title>
        <link href="assets/css/bootstrap.css.php" rel="stylesheet">
        <link rel="shortcut icon"  type="image/png"  href="assets/img/favicon.ico">
        <style> .ui-datepicker-month,.ui-datepicker-year{color:#000000;} .ui-datepicker .ui-datepicker-calendar .ui-state-highlight a { background: #743620 none; color: white; } .wrapper{ padding: 0 15px 105px; } </style>
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
                        <a class="dropdown-toggle" data-toggle="dropdown" style="cursor: pointer;" aria-haspopup="true" aria-expanded="true"><i class="fa fa-gears fa-2x"></i> <span class="caret"></span></a>
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
                    <?php echo tools::appMenu($_SESSION['gpouser']); ?>
                </ul>
            </div>
        </aside>
        <section id="main-content" style="min-height:500px;">
            <section class="wrapper">
                <div class="row">
                    <div class="col-lg-12" style="margin-bottom: 0; padding-bottom: 0;">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-calendar"></i> PROGRAMAR</h3>
                                <span class="pull-right clickable"><i class="fa fa-chevron-up"></i></span>
                            </div>
                            <div class="panel-body" style="display: block; margin-bottom: 0; padding-bottom: 0;">
                                <div class="col-lg-6" style="margin-bottom: -0.5%;">
                                    <div class="form-group">
                                        <h4 style="margin-top: 0.5%;"><?php $paramemp=$_SESSION['idempresa']; echo imprimeempresausr($paramemp); ?></h4>
                                    </div>
                                </div>
                                <div class="col-lg-6" style="text-align: right;">
                                    <div class="form-group">
                                        <span><?php setlocale(LC_ALL,"es_ES"); echo tools::retornadia(date('w'))." ".date('d')." de ".tools::obtenermes(date('m'))." de ".date('Y');?></span>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        Empleado <span style="color: #00734E; font-size: small;"><strong><?php echo substr($_SESSION['idusuario'],2);?></strong></span>
                                        <span style="text-align: right; float: right;">Antiguedad <strong style="color: #00734E; font-size: small;"><?php $piduser=$_SESSION['idloguser']; $datafch=consultas::obtieneingusr($piduser); $datos=explode("-",$datafch); $ingusr = $datos[2]."-".$datos[1]."-".$datos[0]; echo tools::antiguedad($ingusr, 1);?></strong></span>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <span>Nombre <strong><?php echo utf8_encode($_SESSION['nameuser']);?></strong></span>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <span>Puesto</span>&nbsp;<?php echo consultas::printdeppues('pue',$_SESSION['idusuario'],$_SESSION['idempresa']);?>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <span>Departamento</span>&nbsp;<?php echo consultas::printdeppues('dep',$_SESSION['idusuario'],$_SESSION['idempresa']);?>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <span>Ingreso <strong><?php echo $datos[2]."/".tools::reducemes(tools::obtenermes($datos[1]))."/".$datos[0];?></strong></span>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="selper" class="control-label">Periodo</label>
                                        <select id="selper" class="form-control">
                                            <?php echo printper($_SESSION['periodo']); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="seldias" class="control-label">Dias a disfrutar</label>
                                        <select id="seldias" class="form-control show-tick show-menu-arrow">
                                            <option data-icon="fa fa-calendar" value="0">Selecciona dias a disfrutar</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="seldescanso" class="control-label">Descanso</label>
                                        <select id="seldescanso" class="form-control">
                                            <option value="x">Selecciona dia de descanso</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3" style="display: none;" id="shwcalendarvacs">
                                    <div class="form-group">
                                        <label for="calendarvacs">Calendario</label>
                                        <div class="well well-sm" style="display: block; width: 264px;">
                                            <div id="calendarvacs"></div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" class="form-control" id="seleccionadosvac">
                                <input type="hidden" class="form-control" id="vacsvigencia">
                                <input type="hidden" class="form-control" id="dataing" value="<?php echo $datafch;?>">
                                <input type="hidden" class="form-control" id="csalidas" value="">
                                <input type="hidden" class="form-control" id="cregresos" value="">
                                <div class="col-lg-3" id="selectedsdate" style="display: none; text-align: center; margin: 0 auto;">
                                    <div class="form-group" style="margin: 0 auto; text-align: left; margin-left: 10px; margin-top: -10px;">
                                        <label class="control-label">Dia(s) de vacaciones &nbsp;<strong style="font-size: 1.5em; text-align: left; margin-left: 10px;" id="numselecteds"></strong></label>
                                        <div class="well well-sm" id="selectedsdatewell" style="max-width: 240px; text-align: center; height: 235px; overflow-y: auto;">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3" id="contsalidasvac" style="display: none;">
                                    <div class="form-group">
                                        <label for="fchsalidasvac" class="control-label">Fecha(s) de salida</label>
                                        <select id="fchsalidasvac" size="8" class="form-control"></select>
                                    </div>
                                </div>
                                <div class="col-lg-3" id="contregresvac" style="display: none;">
                                    <div class="form-group">
                                        <label for="fchregresvac" class="control-label">Fecha(s) de regreso</label>
                                        <select id="fchregresvac" size="8" class="form-control"></select>
                                    </div>
                                </div>
                                <div class="col-lg-12" style="text-align: right; display: none;" id="contbtnsolicita">
                                    <div class="form-group">
                                        <a class="btn btn-primary" id="btnsolicita"><i class="fa fa-ship"></i> Solicitar</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php echo sysmsg();?>
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