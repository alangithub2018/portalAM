<?php include_once "assets/includes/funciones.php"; if(isset($_POST['vcsin']) and !empty($_POST['vcsin']) and isset($_POST['vcsfn']) and !empty($_POST['vcsfn']) and isset($_POST['vcsempr']) and isset($_POST['nmempvc']) and empty($_POST['emplyvacs'])){
    reportes::GeneraEXCEL($_POST['vcsin'],$_POST['vcsfn'],$_POST['vcsempr'],$_POST['nmempvc'],'','','','','vc','xls');
}elseif(isset($_POST['emplyvacs']) and !empty($_POST['emplyvacs']) and isset($_POST['vcsempr']) and isset($_POST['nmempvc']) and empty($_POST['vcsin']) and empty($_POST['vcsfn'])){
    reportes::GeneraEXCEL('','',$_POST['vcsempr'],$_POST['nmempvc'], trim($_POST["emplyvacs"]),'','','','vcsxus','xls');
}elseif(isset($_POST['vcsprd']) and !empty($_POST['vcsprd']) and isset($_POST['vcsempr']) and isset($_POST['nmempvc']) and empty($_POST['vcsin']) and empty($_POST['vcsfn']) and empty($_POST['emplyvacs'])){
    reportes::GeneraEXCEL('','',$_POST['vcsempr'],$_POST['nmempvc'],'',$_POST["vcsprd"],'','','vcsxprd','xls');}?>
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
        <section id="main-content" style="min-height: 400px;">
            <section class="wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Reportes de vacaciones</h3>
                                <span class="pull-right clickable"><i class="fa fa-chevron-up"></i></span>
                            </div>
                            <div class="panel-body" style="display: block;">
                                <form name="vcsrep" id="envvacs" action="" method="post">
                                    <div class="col-lg-4">
                                    <div class="form-group">
                                      <label for="sltprptvcs" class="control-label">Tipo</label>
                                      <select class="form-control" name="myrptvcs" id="sltprptvcs" required="required">
                                           <option value="1">Periodo</option>
                                           <option value="2">Usuario</option>
                                           <option value="3">Fechas</option>
                                      </select>
                                    </div>
                                </div>
                                <div class="col-lg-4" id="dvrptempvc">
                                    <div class="form-group">
                                        <label for="rptempvcs" class="control-label">Empresa</label>
                                            <select name="vcsempr" class="form-control" id="rptempvcs" required="required" onchange="document.getElementById('empretxt').value=this.options[this.selectedIndex].text">
                                                <?php echo prntemp(); ?>
                                            </select>
                                        <input type="hidden" name="nmempvc" id="empretxt" value="" />
                                    </div>
                                </div>
                                <div class="col-lg-4" id="rptvcxvds">
                                    <div class="form-group">
                                      <label for="prdrptvc" class="control-label">Periodo</label>
                                        <div class="input-group">
                                            <select name="vcsprd" class="form-control" id="prdrptvc">
                                                <?php echo prntprdsrptvcs($_SESSION['periodo'],''); ?>
                                            </select>
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-download"></i> <span class="caret"></span></button>
                                                    <ul style="min-width: 79px;" class="dropdown-menu dropdown-menu-right">
                                                        <li style="cursor: pointer;"><a id="btngenpdfvxvdo"><img src="assets/img/pdf.png" /> PDF</a></li>
                                                        <li style="cursor: pointer;"><a id="xlsprdsvcs"><img src="assets/img/excel.png" /> EXCEL</a></li>
                                                    </ul>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4" id="rptvcxusr" style="display: none;">
                                    <div class="form-group">
                                        <label for="txtrptursvcs" class="control-label">#Empleado</label>
                                        <div class="input-group">
                                            <input type="text" name="emplyvacs" id="txtrptursvcs" class="form-control" placeholder="Numero de empleado...">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-download"></i> <span class="caret"></span></button>
                                                <ul style="min-width: 79px;" class="dropdown-menu dropdown-menu-right">
                                                    <li style="cursor: pointer;"><a id="btngenpdfvxusr"><img src="assets/img/pdf.png" /> PDF</a></li>
                                                    <li style="cursor: pointer;"><a id="xlsvcseply"><img src="assets/img/excel.png" /> EXCEL</a></li>
                                                </ul>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4" id="rptvcxrng" style="display: none;">
                                    <div class="form-group">
                                        <label for="txtrptrngvcs" class="control-label">Rangos de fecha</label>
                                        <div class="input-group">
                                            <input class="form-control" type="text" id="txtrptrngvcs" placeholder="Selecciona las fechas..." oncopy="return false;" onpaste="return false;">
                                            <input name="vcsin" id="rginrptvc" type="hidden" value="" /> <input name="vcsfn" id="rfinrptvc" type="hidden" value="" />
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-download"></i> <span class="caret"></span></button>
                                                    <ul style="min-width: 79px;" class="dropdown-menu dropdown-menu-right">
                                                        <li style="cursor: pointer;"><a id="btngenpdfvxrng"><img src="assets/img/pdf.png" /> PDF</a></li>
                                                        <li style="cursor: pointer;"><a id="dtvcscvs"><img src="assets/img/excel.png" /> EXCEL</a></li>
                                                    </ul>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                </form>
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