<?php include_once "assets/includes/funciones.php";?>
    <!DOCTYPE html>
    <html moznomarginboxes mozdisallowselectionprint>
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="Sistemas AM">
        <meta name="keyword" content="Sistema, Portal AM, Servicios">
        <meta http-equiv="cache-control" content="max-age=0" />
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
        <meta http-equiv="Expires" content="0" />
        <meta http-equiv="Pragma" content="no-cache" />
        <title>.::|::. Portal AM .::|::.</title>
        <link href="assets/css/bootstrap.css.php" rel="stylesheet">
        <link rel="shortcut icon"  type="image/png"  href="assets/img/favicon.ico">
        <link rel="stylesheet" type="text/css" href="assets/css/formatos.css">
        <style>
            body{
                color:#000;
            }
        </style>
        <style type="text/css" media="print">
        @page 
        {
            size:auto portrait;
            margin: 0mm;  /* this affects the margin in the printer settings */
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        _:-ms-lang(x), _:-webkit-full-screen, .invoice-wrapper {
            zoom: -1;
        }
        _:-ms-lang(x), _:-webkit-full-screen, #bbP, div.bottom-bar{
            bottom: 0mm;
        }
        _:-ms-lang(x), _:-webkit-full-screen, section.back {
            margin-left: -250pt;
            padding-top: 1rem;
        }
        _:-ms-lang(x), _:-webkit-full-screen,#Politicas{
            margin-top: 2rem;
        }
        _:-ms-lang(x), _::-webkit-meter-bar, body {
            width: 216mm;
            height: 279mm;
        }
        #Politicas, #medioM{
            line-height: 19pt;
        }
        .invoice-bottom-total{
            margin-top: 2pt;
        }
        .total-box{
            background-color: #EEEEEE !important;
            color: #000 !important;
            padding: 1em !important;
            margin-bottom: 0;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        #medioM{
            color: #000 !important;
        }
        div.bottom-bar{
            padding-bottom: 0mm;
            margin-bottom: 0mm;
            bottom: -84mm;
            left: -12.1mm;
            padding-right: 0mm;
            margin-right: -12.1mm;
            height: 6.879167mm;
            background-color: #009947 !important; /*#5abc27;*/
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .back, .invoice-wrapper{
            height: 260mm;
        }
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
                            <section class="back">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="invoice-wrapper">
                                                <div class="invoice-top">
                                                    <div class="row">
                                                        <div id="mTImpresion" class="col-sm-12">
                                                            <div class="invoice-top-left">
                                                                <h2>PERMISO DE MATRIMONIO</h2>
                                                                <img id="membretada" class="pull-right" src="assets/img/logo.png" width="136" height="33">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <div id="screenLey" class="invoice-top-right">
                                                                <span id="Politicas">
                                                                    <strong id="leym">Dentro de la ley mexicana</strong> no obliga a las empresas a brindar vacaciones a los trabajadores al contraer matrimonio, sin embargo
                                                                la empresa s&iacute; lo considera y brinda dos d&iacute;as libres. De acuerdo al Reglamento interno de trabajo.
                                                                </span>
                                                            </div>
                                                            <h6 id="reiterar" class="specs"><em id="enfasis">Este permiso esta sujeto a la autorizacion del Jefe inmediato y a las necesidades.</em></h6>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="invoice-bottom">
                                                    <div class="row" style="padding-bottom:10pt;">
                                                        <div class="col-xs-12">
                                                            <div class="task-table-wrapper">
                                                                <table class="table">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td class="desc">
                                                                                <h3>Nombre completo<span class="respuestas pull-right"><em><?php echo utf8_encode($_SESSION['nameuser']);?></em></span></h3>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="desc">
                                                                                <h3>Puesto<span class="respuestas pull-right"><em><?php echo consultas::printdeppues('pue',$_SESSION['idusuario'],$_SESSION['idempresa']);?></em></span></h3>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="desc">
                                                                                <h3>Departamento<span class="respuestas pull-right"><em><?php echo consultas::printdeppues('dep',$_SESSION['idusuario'],$_SESSION['idempresa']);?></em></span></h3>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="desc">
                                                                                <h3>Observaciones</h3>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                <div class="col-sm-12 no-padding">
                                                                                    <div class="total-box">
                                                                                        <span id="medioM">El colaborador deber&aacute; entregar a Capital Humano en un periodo de 5 d&iacute;as posterior a la boda COPIA del acta de matrimonio para
                                                                                        que los d&iacute;as con permiso de goce de sueldo s&eacute;an aplicados.
                                                                                        </span>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="clearfix"></div>
                                                        <div class="col-md-12">
                                                            <div class="invoice-bottom-total">
                                                                <div class="col-sm-12 no-padding">
                                                                    <div class="sub-total-box">
                                                                        <h6>Fecha de Solicitud</h6>
                                                                        <h5 id="fsolic"><?php setlocale(LC_ALL,"es_ES"); echo date('d')."/".tools::reducemes(tools::obtenermes(date('m')))."/".date('Y');?></h5>
                                                                    </div>
                                                                    <div class="add-box">
                                                                        <h3>|</h3>
                                                                    </div>
                                                                    <div class="tax-box">
                                                                        <h6>Numero de d&iacute;as</h6>
                                                                        <h5 id="ndias">2</h5>
                                                                    </div>
                                                                    <div class="add-box">
                                                                        <h3>|</h3>
                                                                    </div>
                                                                    <div class="sub-total-box">
                                                                        <h6>Fecha de Salida</h6>
                                                                        <h5 id="fsolic">&nbsp;</h5>
                                                                    </div>
                                                                    <div class="add-box">
                                                                        <h3>|</h3>
                                                                    </div>
                                                                    <div class="tax-box">
                                                                        <h6>Fecha de Regreso</h6>
                                                                        <h5 id="ndias">&nbsp;</h5>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="clearfix"></div>
                                                        <div id="firmas" class="col-xs-12">
<!--                                                            <hr class="divider">-->
                                                        </div>
                                                        <div class="col-sm-4 col-xs-4 sign">
                                                            <h6 class="text-left"><strong>Firma Colaborador</strong></h6>
                                                        </div>
                                                        <div class="col-sm-4 col-xs-4 sign">
                                                            <h6 class="text-center"><strong>Firma Jefe Inmediato</strong></h6>
                                                        </div>
                                                        <div class="col-sm-4 col-xs-4 sign">
                                                            <h6 class="text-right"><strong>Firma Capital Humano</strong></h6>
                                                        </div>
                                                    </div>
                                                    <div id="bbM" class="bottom-bar"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="contenedor">
                                    <a id="printFm" target="_blank" style="cursor:pointer;">
                                        <button id="printM" class="botonF1 animated fadeInRight">
                                            <span><i class="fa fa-print"></i></span>
                                        </button>
                                    </a>
                                </div>
                            </section>
                    </div>
                </div>
            </section>
        </section>
        <footer class="footer" style="position: fixed;">
            <div style="text-align: center; margin-top: 0.8%;">Todos los derechos reservados &copy; 2015 Periodico AM.</div>
            <a class="go-top animated fadeInRight"><i></i></a>
        </footer>
    </section>
    <!--<script src="assets/js/jquery-3.2.1.slim.min.js" type="text/javascript"></script>-->
    <script src="assets/js/jquery-1.10.2.min.js.php"></script>
    <script src="assets/js/bootstrap.min.js.php"></script>
    <script src="assets/js/jquery-ui.min.js.php" type="text/javascript"></script>
    <script>
        $("#printFm").attr("href","assets/includes/genFormatsPDF.php?html=" + btoa("mat"));
        $(window).resize(function(){
           $(".wrapper").css({
               "height": screen.availHeight-42,
               "margin-bottom": "20px"
           });
        });
    </script>
    <?php echo tools::prntmdlpltcs(); ?>
    </body>
    </html>