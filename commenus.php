<?php include_once "assets/includes/funciones.php";?>
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
                <?php echo tools::appMenu($_SESSION['gpouser']); ?>
            </ul>
        </div>
    </aside>
    <section id="main-content">
        <section class="wrapper">
            <?php if($_SESSION['gpouser']=='4'){
                echo sysmsg()."<div class=\"row mt\" id=\"mnppal\" style='margin-bottom: 4%; margin-top: 0.8%;'>";
                echo prntmenususer()."</div>";
            }else{if($_SESSION['gpouser']!='2' or ($_SESSION['gpouser']=='3' or $_SESSION['gpouser']=='0')){ ?>
                <div class="row" id="mnppal">
                    <div class="col-lg-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-folder-o"></i> Catalogo de menus</h3>
                                <span class="pull-right clickable"><i class="fa fa-chevron-up"></i></span>
                            </div>
                            <div class="panel-body" style="display: block;">
                                <div class="row">
                                    <div class="col-lg-4" style="text-align: right;">
                                        <div class="form-group">
                                            <div class="input-group">
                                              <input type="text" class="form-control" placeholder="Buscar en el sistema..." id="shcatmenu">
                                                <span class="input-group-btn">
                                                  <button class="btn btn-default" type="button" id="btnshcatmenu">Buscar</button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                                <div class="table-responsive" style="margin-right: 1%; margin-left: 1%; margin-top: 0; margin-bottom: 0.5%; width: 98%; max-height: 150px; overflow-y: auto;">
                                                    <table class="table table-bordered table-striped table-condensed table-hover">
                                                        <caption style="margin-top:0;">LISTADO DE PLATILLOS</caption>
                                                        <thead>
                                                        <tr>
                                                            <th>Nombre</th>
                                                            <th>Descripcion</th>
                                                            <th class="centered">Acciones</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="tblcatamenus">
                                                        <?php echo printcatmenus('');?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <a data-toggle="modal" href="#nwcatmenu" class="btn btn-default" data-backdrop="static" data-keyboard="false"><i class="fa fa-files-o"></i> Nuevo Menu</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php echo sysmsg(); ?>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-coffee"></i> Cargar menu al sistema</h3>
                                <span class="pull-right clickable"><i class="fa fa-chevron-up"></i></span>
                            </div>
                            <div class="panel-body" style="display: block;">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label">Nombre</label>
                                        <input type="text" id="vnplat" class="form-control" placeholder="Nombre del platillo" aria-describedby="basic-addon" maxlength="200">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label">Descripcion</label>
                                        <input type="text" id="vdescpl" class="form-control" placeholder="Descripcion del platillo" maxlength="200">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="spinner" class="control-label">Cantidad (Platillos)</label>
                                        <input type="text" id="spinner" name="value" value="0" class="form-control ui-spinner-input" aria-valuenow="1" autocomplete="off" role="spinbutton">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="spinner2" class="control-label">Precio (Platillos)</label>
                                        <input type="text" id="spinner2" name="value" value="0" class="form-control ui-spinner-input" aria-valuenow="1" autocomplete="off" role="spinbutton">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="diasmenu" class="control-label">Dia</label>
                                        <div class="input-group">
                                            <select id="diasmenu" class="form-control" aria-describedby="basic-addon" style="z-index: 0;">
                                                <option value="0">Seleccione el dia</option>
                                                <?php echo imprimeselect();?>
                                            </select>
                                            <a class="input-group-addon btn btn-default" id="ingmenu"><i class="fa fa-save"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="diasadd" class="control-label">Nuevo</label>
                                        <div class="input-group">
                                            <select id="diasadd" class="form-control" aria-describedby="basic-addon" style="z-index: 0;">
                                                <option value="0">Agregar mas...</option>
                                                <?php echo imprimeselectadd();?>
                                            </select>
                                            <a class="input-group-addon btn btn-default" id="addmenu"><i class="fa fa-plus"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" value="" id="envmenucat">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="nwcatmenu" tabindex="-1" role="dialog" aria-labelledby="largeModal" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-book"></i> Agregar Menu</h4>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="namemenu" class="control-label">Nombre</label>
                                            <input type="text" class="form-control" id="namemenu">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="descmenu" class="control-label">Descripcion</label>
                                            <input type="text" class="form-control" id="descmenu">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                <button type="button" class="btn btn-primary" id="nwmenucat"><i class="fa fa-save"></i> Guardar</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-calendar"></i> Dias de la semana</h3>
                                <span class="pull-right clickable"><i class="fa fa-chevron-up"></i></span>
                            </div>
                            <div class="panel-body" style="display: block; padding-top: 0.2em;">
                                    <div class="table-responsive" style="margin-right: 1%; margin-left: 1%; margin-top: 1%; margin-bottom: 0.5%; width: 98%;">
                                        <table class="table table-bordered table-striped table-condensed table-hover">
                                            <caption style="margin-top:0;">PLATILLOS DEL DIA</caption>
                                            <thead>
                                                <tr style="cursor:default" onselectstart="return false">
                                                <th>Dia</th>
                                                <th>Nombre</th>
                                                <th>Descripcion</th>
                                                <th>Cantidad</th>
                                                <th>Apartados</th>
                                                <th>Disponibles</th>
                                                <th>Precio</th>
                                                <th>Fecha</th>
                                            </tr>
                                            </thead>
                                            <tbody id="tblmenus">
                                            <?php echo imprimetabla();?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
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
    <?php echo prntloading().mdlEdtMns().tools::prntmdlpltcs(); ?>
    </body>
    </html>