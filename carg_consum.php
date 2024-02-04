<?php include_once "assets/includes/funciones.php";?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="Sistemas AM">
        <meta name="keyword" content="Sistema, Portal AM, Servicios">
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
        <meta http-equiv="cache-control" content="max-age=0" />
        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
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
    <section id="main-content" style="min-height:420px;">
        <section class="wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-plus-square"></i> Nuevo consumo</h3>
                            <span class="pull-right clickable"><i class="fa fa-chevron-up"></i></span>
                        </div>
                        <div class="panel-body" style="display: block;">
                            <div class="col-lg-4" id="chkvisit" style="display: none; margin-top: -1%;">
                                <div class="checkbox">
                                    <label><input type="checkbox" value="" id="chkvste" style="cursor: pointer;"><i class="fa fa-child"></i> Visitante</label>
                                </div>
                            </div>
                            <div class="col-lg-4" id="chkmrcons" style="display: none; margin-top: -1%;">
                                <div class="checkbox">
                                    <label><input type="checkbox" value="" id="chkmrcns" style="cursor: pointer;">Nuevo consumo</label>
                                </div>
                            </div>
                            <div class="col-lg-4" id="fchantg" style="display: none; margin-top: -1%;">
                                <div class="checkbox">
                                    <label><input type="checkbox" value="" id="chkant" style="cursor: pointer;"><i class="fa fa-calendar"></i> Antiguo</label>
                                </div>
                            </div>
                            <div class="col-lg-4" id="tipocrgcol">
                                <div class="form-group">
                                    <label for="seltipocrg" class="control-label">Tipo</label>
                                    <select class="form-control" id="seltipocrg">
                                        <option value="0">Selecciona el tipo</option>
                                        <option value="1">Menu de la semana</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4" id="ocultamenucrg" style="display: none;">
                                <div class="form-group">
                                    <label for="selmenucg" class="control-label">Menu</label>
                                    <select class="form-control selectpicker" id="selmenucg">
                                        <option value="0">Seleccione el menu</option>
                                        <?php echo menusem(0,'','');?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4" id="ocultamenusel" style="display: none;">
                                <div class="form-group">
                                    <label for="selmenumore" class="control-label">Elige el menu</label>
                                    <select class="form-control" id="selmenumore">
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4" style="display: none;" id="spnrcantvist">
                                <div class="form-group">
                                    <label for="spinnervisit" class="control-label">Cantidad</label><br>
                                    <input type="text" id="spinnervisit" name="value" value="0" class="form-control ui-spinner-input" aria-valuenow="1" autocomplete="off" role="spinbutton">
                                </div>
                            </div>
                            <div class="col-lg-6" id="selmrcons" style="display: none;">
                                <div class="form-group">
                                    <label for="selmrcns" class="control-label">Elige el usuario</label>
                                    <div class="input-group">
                                        <select id="selmrcns" class="form-control selectpicker" data-live-search="true" aria-describedby="basic-addon">
                                            <?php echo selectusers();?>
                                        </select>
                                        <a class="input-group-addon btn btn-default" id="btnmrcns"><i class="fa fa-plus fa-lg"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5" id="namevisit" style="display: none;">
                                <div class="form-group">
                                    <label for="nmvst" class="control-label">Nombre</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="nmvst" maxlength="150">
                                        <span class="input-group-btn">
                                            <a class="btn btn-primary" id="btnvst">Cargar consumo</a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6" id="txtfchant" style="display: none;">
                                <div class="form-group">
                                    <label for="txtfant">Selecciona fecha</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control pull-right" id="txtfant">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php echo sysmsg();?>
            </div>
            <div class="row" style="display: none;" id="ocultblusrconsums">
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-users"></i> Usuarios</h3>
                            <span class="pull-right clickable"><i class="fa fa-chevron-up"></i></span>
                        </div>
                        <div class="panel-body" style="display: block;">
                                <div class="table-responsive" style="margin: 0;">
                                    <table class="table table-bordered table-striped table-condensed table-hover">
                                        <caption style="margin-top:0;">CONSUMOS REGISTRADOS</caption>
                                        <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Nombre</th>
                                            <th class="centered">Fecha</th>
                                            <th class="centered">Registro</th>
                                            <th class="centered">Acciones</th>
                                        </tr>
                                        </thead>
                                        <tbody id="tblcrgmnusrs">
                                        <?php echo llenatablacrgmn('');?>
                                        </tbody>
                                    </table>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="display: none;" id="ocultblconsvisit">
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-street-view"></i> Consumos de Visitantes</h3>
                            <span class="pull-right clickable"><i class="fa fa-chevron-up"></i></span>
                        </div>
                        <div class="panel-body" style="display: block;">
                                <div class="table-responsive" style="margin: 1.2%;">
                                    <table class="table table-bordered table-striped table-condensed table-hover">
                                        <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Menu</th>
                                            <th>Fecha</th>
                                            <th>Cantidad</th>
                                            <th>Precio</th>
                                            <th>Importe</th>
                                            <th>Registro</th>
                                            <th class="centered">Acciones</th>
                                        </tr>
                                        </thead>
                                        <tbody id="tblcrgmnvst">
                                        <?php echo consvisitsmnu('','');?>
                                        </tbody>
                                    </table>
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
    <?php echo prntloading(); ?>
    </body>
    </html>