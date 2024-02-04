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
                            <h3 class="panel-title"><i class="fa fa-envelope-o"></i> Mis sugerencias</h3>
                            <span class="pull-right clickable"><i class="fa fa-chevron-up"></i></span>
                        </div>
                        <div class="panel-body" style="display: block;">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="tsugs" class="control-label">Titulo</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-comment-o"></i>
                                        </div>
                                        <input type="text" name="titlesugs" class="form-control" id="tsugs" required="required">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="editor1" class="control-label">Sugerencias</label>
                                    <textarea class="form-control" cols="50" id="editor1" name="editor1" rows="6"></textarea>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <a class="btn btn-primary" id="envsugs"><i class="fa fa-send"></i> Enviar</a>
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
                            <h3 class="panel-title"><i class="fa fa-list-alt"></i> Listado</h3>
                            <span class="pull-right clickable"><i class="fa fa-chevron-up"></i></span>
                        </div>
                        <div class="panel-body" style="display: block;">
                            <div class="form-group">
                                <section id="unseen">
                                    <div class="table-responsive" style="margin-right: 1.2%; margin-top: -1%; margin-bottom: -2%;">
                                        <table class="table table-bordered table-striped table-condensed">
                                            <thead>
                                            <tr>
                                                <th>Titulo</th>
                                                <th>Sugerencia</th>
                                                <th>Fecha</th>
                                            </tr>
                                            </thead>
                                            <tbody id="tblmissugs">
                                            <?php echo missugs($_SESSION['idloguser']); ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </section>
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
    <?php echo tools::prntmdlpltcs(); ?>
  </body>
</html>