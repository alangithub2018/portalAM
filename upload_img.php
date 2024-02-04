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
                            <li id="vclosecom"><a class="logout" id="cierracom" href="assets/includes/closer.php?<?php echo random_system();?>" name="closesystem"><i class="fa fa-sign-out"></i> Salir</a></li>
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
                                <h3 class="panel-title"><i class="fa fa-bell"></i> Avisos del sistema</h3>
                                <span class="pull-right clickable"><i class="fa fa-chevron-up"></i></span>
                            </div>
                            <div class="panel-body" style="display: block;">
                                <form enctype="multipart/form-data">
                                    <label>Listado de avisos</label>
                                    <input type="file" accept="image/jpeg" id="file-es" name="file-es[]" multiple="true" class="file-loading" />
                                </form>
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
    <?php $directorio="assets/img/sliders/";$images=glob($directorio."*.jpg");$tots=count($images); ?>
    <script>
      $(document).ready(function(){$('#file-es').fileinput({ uploadUrl: 'assets/includes/upload.php', uploadAsync: true, minFileCount: 1, maxFileCount: 4, overwriteInitial: false, language: 'es', autoReplace: true, showRemove: false, showUpload: false, showCaption: true, browseIcon: '<i class="fa fa-picture-o"></i>&nbsp;', browseLabel: "Cargar aviso", previewFileType: "image", allowedFileExtensions : ['jpg'], maxFileSize: 600,/*minImageWidth: 1504,maxImageWidth: 1504,minImageHeight: 566,maxImageHeight: 566,*/browseClass: "btn btn-theme btn-block", initialPreview: [<?php foreach($images as $image){?> "<img src='<?php echo $image;?>' style='width:auto;height:auto;max-width:100%;max-height:100%;' class='file-preview-image'>", <?php }?> ], initialPreviewConfig: [<?php foreach($images as $image){$infoImagenes=explode("/",$image);?> {caption: "<?php echo end($infoImagenes);?>", height: "120px", url: "assets/includes/borrar.php", key: "<?php echo end($infoImagenes);?>" }, <?php }?> ]});$('#file-es').on("filepredelete", function(jqXHR) { var abort = true; if (confirm("Realmente deseas quitar este anuncio?")) { abort = false; } return abort; });});
    </script>
    </body>
    </html>