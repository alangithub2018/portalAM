<?php include_once "assets/includes/funciones.php";
    if(isset($_POST['xlscmxdt']) and !empty($_POST['xlscmxdt']) or isset($_POST['xlscmxrng']) and !empty($_POST['xlscmxrng'])){ 
    reportes::GeneraEXCEL('','','','','','',$_POST['xlscmxdt'],$_POST['xlscmxrng'],'cmanot','xls'); } ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="description" content="Portal AM" />
        <meta name="author" content="Sistemas AM">
        <meta name="keyword" content="Sistema, Portal AM, Servicios" />
        <meta http-equiv="cache-control" content="max-age=0" />
        <meta http-equiv="cache-control" content="no-cache, no-store, must-revalidate" />
        <meta http-equiv="Expires" content="-1">
        <meta http-equiv="Pragma" content="no-cache" />
        <title>.::|::. Portal AM .::|::.</title>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
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
        <section id="main-content">
            <section class="wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-leanpub"></i> Registrados en comedor</h3>
                                <span class="pull-right clickable"><i class="fa fa-chevron-up"></i></span>
                            </div>
                            <div class="panel-body" style="display: block;">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="control-label">Buscar</label>
                                        <div class="input-group">
                                            <input type="text" id="shantmnusr" class="form-control" placeholder="Buscar empleado..." aria-describedby="basic-addon" maxlength="200">
                                            <a id="btnshantmnusr" class="input-group-addon btn btn-primary">Buscar</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="rangosmn">Rango de fechas</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" class="form-control pull-right" id="rangosmn">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="fchmn" class="control-label">Menu</label>
                                        <div class="input-group">
                                            <select class="form-control" id="fchmn" style="border-top-right-radius: 0; border-bottom-right-radius: 0; margin-left: 1px;">
                                                <option value="0">Seleccione el menu</option>
                                                <?php echo menusem(1,'',''); ?>
                                            </select>
                                            <span class="input-group-btn">
                                                <?php if($_SESSION['gpouser']=='3'){ ?>
                                                <a style="cursor: pointer;" id="exppdf" href="assets/includes/exporta_pdf.php" target="_blank"><button id="rdirbtn" class="btn btn-default" type="button" style="border-top-left-radius: 0; border-bottom-left-radius: 0;"><i style="color: red;" class="fa fa-file-pdf-o fa-lg"></i></button></a>
                                                <?php }elseif ($_SESSION['gpouser']=='2' OR $_SESSION['gpouser'] == '0') { ?>
                                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-download"></i> <span class="caret"></span></button>
                                                    <ul style="min-width: 79px;" class="dropdown-menu dropdown-menu-right">
                                                        <li style="cursor: pointer;"><a style="cursor: pointer;" id="exppdf" href="assets/includes/exporta_pdf.php" target="_blank"><img src="assets/img/pdf.png" /> PDF</a></li>
                                                        <li style="cursor: pointer;"><a id="xlsandscmdr"><img src="assets/img/excel.png" /> EXCEL</a></li>
                                                    </ul>
                                                <?php } ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div style="display: none;">
                                    <form name="paramrpt" id="frmenvdate" action="" method="POST">
                                        <input type="hidden" name="xlscmxdt" id="fchone" value="" />
                                        <input type="hidden" name="xlscmxrng" id="rngfch" value="" />
                                    </form>
                                </div>
                                <div class="col-lg-12" style="max-height: 410px; overflow-x: auto; overflow-y: auto;">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-condensed">
                                            <caption style="margin-top: 0;">EMPLEADOS ANOTADOS</caption>
                                            <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Nombre</th>
                                                <th>Menu</th>
                                                <th>Empresa</th>
                                                <th>Fecha</th>
                                                <th>Registrado</th>
                                            </tr>
                                            </thead>
                                            <tbody id="tblanotados">
                                            <?php $busquedahoy="SELECT E.codigo , U.empresa, U.id AS iduser, M.id AS idmenu, F.menu, F.id, R.menuporfecha, U.`usuario` AS empleado, U.`nombre` AS nomusuario, M.`nombre` AS nommenu, E.`descripcion` AS empresa, F.`fecha` AS fechamenu, R.`registro` AS anotado FROM `com_anotaciones` R INNER JOIN `com_menuporfecha` F ON R.`menuporfecha` = F.`id` INNER JOIN `com_menus` M ON F.`menu` = M.`id` INNER JOIN `com_usuarios` U ON R.`usuario` = U.`id` INNER JOIN `com_empresas` E ON U.`empresa` = E.`codigo` AND F.`fecha` = CURDATE() ORDER BY R.registro DESC"; echo anotadosmenu($busquedahoy,false); $sql_obttot="SELECT SUM(`precio`) as total FROM `com_anotaciones` R INNER JOIN `com_menuporfecha` F ON R.`menuporfecha` = F.`id` AND F.`fecha` = CURDATE();"; $rs_obttot=mysqli_query($conexion,$sql_obttot); $rw_obttot=mysqli_fetch_assoc($rs_obttot); if($rw_obttot['total']!=null and !empty($rw_obttot['total'])){ echo "<td colspan='3' align='left' style='text-align: left;'>TOTAL: $<strong style=\"font-family: 'Roboto', sans-serif; font-size: larger;\">".$rw_obttot['total']."</strong></td></tr>";}?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php echo sysmsg();?>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-street-view"></i> Visitantes</h3>
                                <span class="pull-right clickable"><i class="fa fa-chevron-up"></i></span>
                            </div>
                            <div class="panel-body" style="display: block;">
                                <section id="visits">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-condensed">
                                            <caption style="margin-top: 0;">VISITANTES REGISTRADOS</caption>
                                            <thead>
                                            <tr>
                                                <th>Menu</th>
                                                <th>Fecha</th>
                                                <th>Cantidad</th>
                                                <th>Precio</th>
                                                <th>Importe</th>
                                                <th>Registro</th>
                                            </tr>
                                            </thead>
                                            <tbody id="tblvisitantes">
                                            <?php echo consvisitsmnu("SELECT M.`nombre`, F.`fecha`, V.`cantidad`, V.`precio`, (V.`cantidad` * V.`precio`) AS importe, V.`registro` FROM `com_visitantes` V INNER JOIN `com_menuporfecha` F ON V.`menuporfecha` = F.`id` INNER JOIN `com_menus` M ON F.`menu` = M.`id` WHERE F.`fecha` = CURDATE()",date('Y-m-d'));?>
                                            </tbody>
                                        </table>
                                    </div>
                                </section>
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