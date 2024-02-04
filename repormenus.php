<?php include_once "assets/includes/funciones.php";
    if(isset($_POST["vini"]) and isset($_POST["vfin"]) and isset($_POST["vempre"]) and isset($_POST["nameemp"]) and $_POST['typerpt'] == ''){ 
        reportes::GeneraEXCEL($_POST['vini'],$_POST['vfin'],$_POST['vempre'],$_POST['nameemp'],'','','','','cm','csv');
    }elseif(isset($_POST["vini"]) and isset($_POST["vfin"]) and isset($_POST["vempre"]) and isset($_POST["nameemp"]) and $_POST['typerpt'] == 'xls'){
        reportes::GeneraEXCEL($_POST['vini'],$_POST['vfin'],$_POST['vempre'],$_POST['nameemp'],'','','','','xlscm','xls');
    }?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="description" content="Portal AM" />
        <meta name="author" content="Sistemas AM" />
        <meta name="keyword" content="Sistema, Portal AM, Servicios" />
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
        <meta http-equiv="cache-control" content="max-age=0" />
        <meta http-equiv="cache-control" content="no-cache, no-store, must-revalidate" />
        <meta http-equiv="Expires" content="0" />
        <meta http-equiv="Pragma" content="no-cache" />
        <title>.::|::. PORTAL AM .::|::.</title>
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
        <section id="main-content" style="min-height:400px;">
            <section class="wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-sellsy"></i> Reportes de comedor</h3>
                                <span class="pull-right clickable"><i class="fa fa-chevron-up"></i></span>
                            </div>
                            <div class="panel-body" style="display: block;">
                                <form name="datarep" id="envdtrep" action="" method="post">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="vrempresa" class="control-label">Empresa</label>
                                            <select class="form-control" name="vempre" id="vrempresa" required="required" onchange="document.getElementById('text_content').value=this.options[this.selectedIndex].text">
                                                <?php echo prntemp(); ?>
                                            </select>
                                            <input type="hidden" name="nameemp" id="text_content" value="" />
                                            <input type="hidden" name="typerpt" id="tiporeport" value="" />
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="fini" class="control-label">Fecha Inicial</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" name="vini" class="form-control" data-inputmask="'alias': 'yyyy-mm-dd'" data-mask="" id="fini" required="required">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="ffin" class="control-label">Fecha Final</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" name="vfin" class="form-control" data-inputmask="'alias': 'yyyy-mm-dd'" data-mask="" id="ffin" required="required">
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-download"></i> <span class="caret"></span></button>
                                                    <ul style="min-width: 79px;" class="dropdown-menu dropdown-menu-right">
                                                        <li style="cursor: pointer;"><a id="btngenpdf"><img src="assets/img/pdf.png" /> PDF</a></li>
                                                        <li style="cursor: pointer;"><a id="btnxlscmdr"><img src="assets/img/excel.png" /> EXCEL</a></li>
                                                        <li style="cursor: pointer;"><a id="envdatos"><img src="assets/img/csv.png" /> CSV</a></li>
                                                    </ul>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <a data-toggle="modal" href="#rptgens" class="btn btn-default" id="vgens"><i class="fa fa-exchange"></i> Generados</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php echo sysmsg();?>
                </div>
                <div aria-hidden="true" aria-labelledby="rptgensLabel" role="dialog" tabindex="-1" id="rptgens"class="modal fade">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close cerrardesc" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title"><i class="fa fa-exchange"></i> Log de Archivos Generados</h4>
                            </div>
                            <div class="modal-body">
                                <section id="unseen">
                                    <div class="table-responsive" style="margin-right: 1%; margin-left: 1%; margin-top: 1%; margin-bottom: 0.5%; width: 98%; height: 450px; overflow-y: auto;">
                                        <table class="table table-bordered table-striped table-condensed">
                                            <caption style="margin-top: 0;">REPORTES GENERADOS</caption>
                                            <thead>
                                            <tr>
                                                <th>Host</th>
                                                <th>IP</th>
                                                <th>Generado</th>
                                                <th>Fecha Inicial</th>
                                                <th>Fecha Final</th>
                                                <th>Empresa</th>
                                            </tr>
                                            </thead>
                                            <tbody id="tbllogsfle">
                                            <?php echo generadoslogs();?>
                                            </tbody>
                                        </table>
                                    </div>
                                </section>
                            </div>
                            <div class="modal-footer">
                                <button data-dismiss="modal" class="btn btn-default cerrardesc" type="button">Cerrar</button>
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