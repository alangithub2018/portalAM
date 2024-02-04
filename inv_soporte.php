<?php include_once "assets/includes/funciones.php"; ?>
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
                    <div class="row" style="margin-bottom:2px;">
                        <div class="col-lg-12">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><i class="fa fa-headphones"></i> Soporte</h3>
                                    <span class="pull-right clickable"><i class="fa fa-chevron-up"></i></span>
                                </div>
                                <div class="panel-body" style="display: block;">
                                    <div class="row">
                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <label for="sptrec" class="control-label">Fecha/Solicitud</label>
                                                <div class="input-group">
                                                    <input id="sptrec" type="text" class="form-control" style="font-size:1em;" readonly="readonly" value="<?php echo date('Y-m-d H:i:s'); ?>" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="sptmanage" class="control-label">Atiende</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="fa fa-id-badge"></i></span>
                                                    <input id="sptmanage" type="text" class="form-control" readonly="readonly" value="<?php echo utf8_encode($_SESSION['nameuser']); ?>" />
                                                    <input type="hidden" id="lactatnd" value="<?php echo utf8_encode($_SESSION['nameuser']);?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="sptcat" class="control-label">Categoria</label>
                                                <div class="input-group">
                                                    <select id="sptcat" class="form-control">
                                                        <option value="0">Selecciona...</option>
                                                        <?php echo consultas::getClSpt('categoria'); ?>
                                                    </select>
                                                    <div class="input-group-btn">
                                                        <button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <span class="sr-only">Toggle Dropdown</span>
                                                            <i class="fa fa-snowflake-o"></i>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-left">
                                                            <a class="btnAltCat dropdown-item"><i class="fa fa-files-o"></i> Nuevo</a>
                                                            <div role="separator" class="dropdown-divider"></div>
                                                            <a class="btnEdtCat dropdown-item disabled"><i class="fa fa-pencil"></i> Editar</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <label for="sptest" class="control-label">Estatus</label>
                                                <select id="sptest" class="form-control selectpicker">
                                                    <option data-icon="fa fa-shield" data-content="<span class='badge bg-theme'><i class='fa fa-shield'></i> Activa</span>" value="A">Activa</option>
                                                    <option disabled="disabled" data-icon="fa fa-hourglass-end" data-content="<span class='badge bg-warning'><i class='fa fa-hourglass-end'></i> Terminada</span>" value="T">Terminada</option>
                                                    <option disabled="disabled" data-icon="fa fa-times" data-content="<span class='badge bg-danger'><i class='fa fa-times'></i> Cancelada</span>" value="C">Cancelada</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="selsspt" class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="sptdpto" class="control-label">Departamento</label>
                                                <div class="input-group">
                                                    <select id="sptdpto" class="form-control">
                                                        <option value="0">Selecciona...</option>
                                                        <?php echo consultas::getClSpt('departamento'); ?>
                                                    </select>
                                                    <div class="input-group-btn">
                                                        <button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <span class="sr-only">Toggle Dropdown</span>
                                                            <i class="fa fa-snowflake-o"></i>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-left">
                                                            <a class="btnAltCat dropdown-item"><i class="fa fa-files-o"></i> Nuevo</a>
                                                            <div role="separator" class="dropdown-divider"></div>
                                                            <a class="btnEdtCat dropdown-item disabled"><i class="fa fa-pencil"></i> Editar</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="sptarea" class="control-label">Area</label>
                                                <div class="input-group">
                                                    <select id="sptarea" class="form-control">
                                                        <option value="0">Selecciona...</option>
                                                        <?php echo consultas::getClSpt('area'); ?>
                                                    </select>
                                                    <div class="input-group-btn">
                                                        <button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <span class="sr-only">Toggle Dropdown</span>
                                                            <i class="fa fa-snowflake-o"></i>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-left">
                                                            <a class="btnAltCat dropdown-item"><i class="fa fa-files-o"></i> Nuevo</a>
                                                            <div role="separator" class="dropdown-divider"></div>
                                                            <a class="btnEdtCat dropdown-item disabled"><i class="fa fa-pencil"></i> Editar</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="sptuser" class="control-label">Usuario</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="fa fa-user-circle"></i></span>
                                                    <input id="sptuser" type="text" class="form-control" value="" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label for="sptdamage"><i class="fa fa-heartbeat"></i> Problema</label>
                                                <textarea class="form-control" rows="2" id="sptdamage" style="resize: none; overflow-y: scroll;"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-7">
                                            <div class="form-group">
                                                <label for="sptcorrect"><i class="fa fa-wrench"></i> Solucion</label>
                                                <textarea id="sptcorrect" class="form-control" rows="4" style="resize: none; overflow-y: scroll;" disabled="disabled"></textarea>
                                            </div>
                                        </div>
                                        <input id="edtSptvl" type="hidden" value="" />
                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <label for="sptfsol" class="control-label">Fecha/Solucion</label>
                                                <input id="sptfsol" type="text" class="form-control" onkeydown="return false" onkeypress="return false" onkeyup="return false" disabled="disabled" />
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label for="sptend" class="control-label">Resuelve</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="fa fa-user-md"></i></span>
                                                    <select id="sptend" class="form-control" disabled="disabled">
                                                        <?php echo tools::PrntCmbRsv(false,'all'); ?>
                                                    </select>
                                                    <input type="hidden" id="antRsv" value="" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group" style="margin-bottom:0;">
                                                <button id="sptsave" class="btn btn-theme" type="button" style="margin-right:10px;"><i class="fa fa-save fa-lg"></i> Guardar</button>
                                                <?php if($_SESSION['gpouser']==1){?>
                                                <button id="viewPends" class="btn btn-theme04 pull-right" type="button" style="margin-right:10px;"><i class="fa fa-thumbs-o-up fa-lg"></i> Validar</button>
                                                <?php }?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top:10px;">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <div id="lscroll" class="table-responsive" style="width:100%; max-height:430px; overflow-y: auto; margin-top:5px;">
                                                    <table class="table table-condensed table-bordered table-hover">
                                                        <caption>ACTIVOS</caption>
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
                                                            </tr>
                                                        </thead>
                                                        <tbody id="tblSpt" style="font-size:0.865em;">
                                                            <?php echo consultas::getSpt($_SESSION['nameuser'],false,true,'tblSpt','','','',false,false,'',''); ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if($_SESSION['gpouser']==1){?>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <div id="lscrollt" class="table-responsive" style="width:100%; max-height:330px; overflow-y: auto; margin-top:5px;">
                                                    <table class="table table-condensed table-bordered table-hover">
                                                        <caption>TERMINADOS</caption>
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
                                                            </tr>
                                                        </thead>
                                                        <tbody id="tblvPndsEdt" style="font-size:0.865em;">
                                                            <?php echo consultas::getSpt('',true,true,'tblvPndsEdt','','','',false,false,'',''); ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php }?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" role="dialog" id="mdlAltCat" data-backdrop="static" data-keyboard="false">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button id="clcols" type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h4 class="modal-title" id="ttlAltCat"></h4>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="txtAltCat">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-secondary asgAltCat" type="button"><i class="fa fa-hdd-o"></i></button>
                                                </span>
                                            </div>
                                        </div>
                                        <input type="hidden" id="AntAltCat" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" role="dialog" id="mdlPends" data-backdrop="static" data-keyboard="false">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button id="clsvld" type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h4 class="modal-title"><i class="fa fa-steam fa-lg"></i>&nbsp;Validar Pendientes</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="sptfvl" class="control-label">Fecha/Valida</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="fa fa-calendar-check-o"></i></span>
                                                    <input id="sptfvl" type="text" class="form-control" value="" disabled="disabled" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="sptvld" class="control-label">Valid&oacute;</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="fa fa-user-secret"></i></span>
                                                    <select id="sptvld" class="form-control" value="" disabled="disabled" >
                                                        <?php echo tools::PrntCmbRsv(false,'1'); ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <div id="lscrollv" class="table-responsive" style="width:100%; max-height:330px; overflow-y: auto; margin-top:0;">
                                                    <table class="table table-condensed table-bordered table-hover">
                                                        <caption style="margin-top:0;">POR VALIDAR</caption>
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
                                                            </tr>
                                                        </thead>
                                                        <tbody id="tblvPnds" style="font-size:0.865em;">
                                                            <?php echo consultas::getSpt('',true,false,'tblvPnds','','','',false,false,'',''); ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button id="bvldcls" type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php echo sysmsg();?>
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