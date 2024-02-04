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
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <h3 class="panel-title"><i class="fa fa-qrcode"></i> Inventario</h3>
                                        <span class="pull-right clickable"><i class="fa fa-chevron-up"></i></span>
                                    </div>
                                    <div class="panel-body" style="display: block; padding: 5px;">
                                        <div class="col-lg-12" id="tbl_Inv" style="margin: 0; padding: 0;">
                                            <div id="divInv" class="table-responsive" style="width:100%; overflow-x: auto; overflow-y: auto; margin-top:5px;">
                                                <table id="tblInvent" class="table table-bordered table-condensed table-hover" style="cursor: default; font-size: 0.885em;">
                                                    <thead style="background-color:#FFFFE0; font-size: 1.165em;">
                                                        <tr>
                                                            <th width="13%" onselectstart="return false;" class="th-ordered centered">Departamento <i class="fa fa-sort-alpha-asc"></i></th>
                                                            <th width="6%" onselectstart="return false;" class="th-ordered">Area <i class="fa fa-sort"></i></th>
                                                            <th width="5%" onselectstart="return false;" class="th-ordered centered">Tipo <i class="fa fa-sort"></i></th>
                                                            <th width="6%" onselectstart="return false;" class="th-ordered centered">Marca <i class="fa fa-sort"></i></th>
                                                            <th width="12.5%" onselectstart="return false;" class="th-ordered">Modelo <i class="fa fa-sort"></i></th>
                                                            <th width="12.5%" onselectstart="return false;" class="th-ordered">Serie <i class="fa fa-sort"></i></th>
                                                            <th width="15.5%" onselectstart="return false;" class="th-ordered">Monitor <i class="fa fa-sort"></i></th>
                                                            <th width="11.5%" onselectstart="return false;" class="th-ordered">Asignado <i class="fa fa-sort"></i></th>
                                                            <th width="7%" onselectstart="return false;" class="th-ordered">Fecha <i class="fa fa-sort"></i></th>
                                                            <th width="11%" onselectstart="return false;" class="th-ordered">Estatus <i class="fa fa-sort"></i></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tblinvent">
                                                    <?php consultas::getInventario('departamento', 'asc', false, ''); ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-lg-1" style="margin-top: 5px; padding: 0;">
                                            <div class="form-group" style="margin: 0; padding: 0;">
                                                <button id="nwteamInv" class="btn btn-default"><i class="fa fa-stack-overflow"></i> Nuevo</button>
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
            <div class="modal fade" id="mdledtInv" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="largeModal" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="ttledtinv"></h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="deptoInv">Departamento</label>
                                        <div class="input-group">
                                            <select id="deptoInv" class="form-control">
                                                <?php consultas::selCtrlsInv('departamento'); ?>
                                            </select>
                                            <div class="input-group-btn">
                                                <button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                    <i class="fa fa-puzzle-piece"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-left">
                                                    <a class="ctrlNw dropdown-item"><i class="fa fa-files-o"></i> Nuevo</a>
                                                    <div role="separator" class="dropdown-divider"></div>
                                                    <a class="ctrlEdt dropdown-item disabled"><i class="fa fa-pencil"></i> Editar</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="areInv">Area</label>
                                        <div class="input-group">
                                            <select id="areInv" class="form-control">
                                                <?php consultas::selCtrlsInv('area'); ?>
                                            </select>
                                            <div class="input-group-btn">
                                                <button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                    <i class="fa fa-puzzle-piece"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-left">
                                                    <a class="ctrlNw dropdown-item"><i class="fa fa-files-o"></i> Nuevo</a>
                                                    <div role="separator" class="dropdown-divider"></div>
                                                    <a class="ctrlEdt dropdown-item disabled"><i class="fa fa-pencil"></i> Editar</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="mrcInv">Marca</label>
                                        <div class="input-group">
                                            <select id="mrcInv" class="form-control">
                                                <?php consultas::selCtrlsInv('marca'); ?>
                                            </select>
                                            <div class="input-group-btn">
                                                <button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                    <i class="fa fa-puzzle-piece"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-left">
                                                    <a class="ctrlNw dropdown-item"><i class="fa fa-files-o"></i> Nuevo</a>
                                                    <div role="separator" class="dropdown-divider"></div>
                                                    <a class="ctrlEdt dropdown-item disabled"><i class="fa fa-pencil"></i> Editar</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="modInv">Modelo</label>
                                        <div class="input-group">
                                            <select id="modInv" class="form-control">
                                                <?php consultas::selCtrlsInv('modelo'); ?>
                                            </select>
                                            <div class="input-group-btn">
                                                <button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                    <i class="fa fa-puzzle-piece"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-left">
                                                    <a class="ctrlNw dropdown-item"><i class="fa fa-files-o"></i> Nuevo</a>
                                                    <div role="separator" class="dropdown-divider"></div>
                                                    <a class="ctrlEdt dropdown-item disabled"><i class="fa fa-pencil"></i> Editar</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="tipoInv">Tipo</label>
                                        <div class="input-group">
                                            <select id="tipoInv" class="form-control">
                                                <?php consultas::selCtrlsInv('tipo'); ?>
                                            </select>
                                            <div class="input-group-btn">
                                                <button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                    <i class="fa fa-puzzle-piece"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-left">
                                                    <a class="ctrlNw dropdown-item"><i class="fa fa-files-o"></i> Nuevo</a>
                                                    <div role="separator" class="dropdown-divider"></div>
                                                    <a class="ctrlEdt dropdown-item disabled"><i class="fa fa-pencil"></i> Editar</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="estInv">Estatus</label>
                                        <select id="estInv" class="form-control">
                                            <option value="x">Selecciona...</option>
                                            <option value="1">Activo</option>
                                            <option value="0">Baja</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label for="fchInv">Fecha</label>
                                        <input id="fchInv" type="text" class="form-control" onkeypress="return false" onkeyup="return false" onkeydown="return false">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="sreInv">Serie</label>
                                        <input id="sreInv" type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="mntInv">Monitor</label>
                                        <input id="mntInv" type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="asgInv">Asignado</label>
                                        <input id="asgInv" type="text" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" id="idEdtInv" />
                        </div>
                        <div class="modal-footer">
                            <button id="btnSEInv" class="btn btn-theme" type="button"><i class="fa fa-save"></i> Guardar</button>
                            <button id="btnCInv" data-dismiss="modal" class="btn btn-default" type="button"><i class="fa fa-ban"></i> Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" role="document" id="ctrlRsg" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title"><i class="fa fa-exchange"></i> Reasignaci&oacute;n</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="mtvRsg">Motivo:</label>
                                        <textarea class="form-control" rows="5" id="mtvRsg"></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="usrRsg" class="control-label">Atendio</label>
                                        <input id="usrRsg" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="fchRsg">Fecha</label>
                                        <input id="fchRsg" type="text" class="form-control" onkeydown="return false" onkeypress="return false" onkeyup="return false">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input id="chkNSend" class="form-check-input" type="checkbox" value="">
                                                No enviar
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button id="btnSvRsg" class="btn btn-theme" type="button"><i class="fa fa-save"></i> Guardar</button>
                            <button id="btnCRsg" data-dismiss="modal" class="btn btn-default" type="button"><i class="fa fa-ban"></i> Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" role="dialog" id="HServs" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="ttlHSrvs"></h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-12">
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
            <div class="modal fade" role="dialog" id="ctrlAltas" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="ttlnwInv"></h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="txtnwInv">
                                        <span class="input-group-btn">
                                            <button class="btn btn-secondary asgID" type="button"><i class="fa fa-check-circle"></i></button>
                                        </span>
                                    </div>
                                </div>
                                <input type="hidden" id="vlsAnt" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script src="assets/js/jquery-1.10.2.min.js.php"></script>
            <script src="assets/js/bootstrap.min.js.php"></script>
            <script src="assets/js/jquery-ui.min.js.php" type="text/javascript"></script>
        </body>
    </html>