<?php include_once "funciones.php"; ini_set("memory_limit", "-1");
function GeneraFormatos($encabezado, $tpie, $cuerpo, $salida, $orientacion, $mglft, $mgrght, $mgitop, $mgbottom, $forma){
        $mpdf = new mPDF('utf-8', array(215.9,279.4) , 0, '', $mglft, $mgrght, $mgitop, 9, $mgbottom, 4.5, $orientacion);
        $mpdf->cacheTables = true; $mpdf->simpleTables = true; $mpdf->packTableData = true;
        $mpdf->SetHTMLHeader($encabezado);
        $mpdf->SetHTMLFooter('');
        $mpdf->WriteHTML($cuerpo);
        if(base64_decode($forma) == "pat"){
            $mpdf->Line(62, 134, 62, 124); $mpdf->Line(109, 134, 109, 124); $mpdf->Line(155, 134, 155, 124);
        }else if(base64_decode($forma) == "mat"){
            $mpdf->Line(62, 155, 62, 145); $mpdf->Line(109, 155, 109, 145); $mpdf->Line(155, 155, 155, 145);
        }
        $mpdf->Output($salida. '.pdf', 'I');
}
if(isset($_GET['html']) and !empty($_GET['html'])){
    $dataG = $_GET['html'];
    if(base64_decode($dataG) == "pat"){
        $html = '<html><head></head><body><div class="invoice-wrapper" id="back">
        <div class="invoice-top">
            <div class="row">
                <div class="col-sm-12">
                    <div class="invoice-top-left">
                        <h2 style="font-family:\'Trebuchet MS\'; font-weight:300; float:left; padding-top:7pt; margin-bottom:0; vertical-align: bottom;">PERMISO DE PATERNIDAD</h2>
                        <img style="float:right; margin-top:-20pt; margin-bottom: 10pt;" src="../img/LogoPortalAM.png" width="136" height="33">
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="invoice-top-right" style="text-align: justify; line-height: 24pt; margin-bottom:5pt;">
                        <span style="margin-top: 36pt; text-align:justify;">
                            De acuerdo a los Derechos y Obligaciones de los trabajadores y de los Patrones en el Art. 132, párrafo XXVII Bis.
                            Otorgar permiso de paternidad de 5 días laborales con goce de sueldo, a los hombres trabajadores, por el nacimiento de sus hijos y de
                            igual manera en el caso de la adopción de un infante.
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="invoice-bottom">
            <div class="row" style="padding-bottom:10pt;">
                <div class="col-xs-12">
                    <div class="task-table-wrapper">
                        <h3 style="font-size:11pt; font-weight:thin; width:550px; letter-spacing:0.5pt; margin:0; padding:0;"><strong>Nombre</strong> &nbsp; </h3><div style="background-color:#F6F6F6; text-align:right; border-bottom:1px solid #ccc; margin-bottom:2pt; padding-bottom:1pt;"><i>'.utf8_encode($_SESSION['nameuser']).'</i></div>
                        <h3 style="font-size:11pt; font-weight:thin; width:550px; letter-spacing:0.5pt; margin:0; padding:0;"><strong>Puesto</strong> &nbsp; </h3><div style="background-color:#F6F6F6; text-align:right; border-bottom:1px solid #ccc; margin-bottom:2pt; padding-bottom:1pt;"><i>'.utf8_encode(consultas::printdeppues('pue',$_SESSION['idusuario'],$_SESSION['idempresa'])).'</i></div>
                        <h3 style="font-size:11pt; font-weight:thin; width:550px; letter-spacing:0.5pt; margin:0; padding:0;"><strong>Departamento</strong> &nbsp; </h3><div style="background-color:#F6F6F6; text-align:right; border-bottom:1px solid #ccc; margin-bottom:2pt; padding-bottom:1pt;"><i>'.utf8_encode(consultas::printdeppues('dep',$_SESSION['idusuario'],$_SESSION['idempresa'])).'</i></div>
                        <h3 style="font-size:11pt; font-weight:thin; width:550px; letter-spacing:0.5pt; margin:0; padding:0;"><strong>Observaciones</strong> &nbsp; </h3><div style="background-color:#F6F6F6; text-align:right; border-bottom:1px solid #ccc; margin-bottom:2pt; padding-bottom:1pt;"><i>&nbsp;</i></div><br/>
                        <table cellpadding="8" cellspacing="4" style="border-radius: 3mm / 3mm; background-color:#EEEEEE; color:#2F2F2F; text-align:justify;">
                            <tbody style="font-weight:300;">
                                <tr>
                                    <td>
                                        <div class="col-sm-12">
                                            <div id="ttbPM" class="total-box">
                                                <span id="medioP" style="line-height: 14pt; text-align:justify;">Los días que el colaborador tome deberán de ser a partir del nacimiento de su hijo(a) y tendrán
                                                    una vigencia de 30 días para ser utilizados.
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="clearfix">&nbsp;</div>
                <div class="col-md-12" style="margin-top:4pt;">
                <table cellspacing="4" style="width:100%; font-family:freesans;">
                    <tbody>
                        <tr><td style="text-align:center; background-clip: border-box; border-bottom:2px solid black;">
                                <div class="col-sm-12 no-padding" style="padding-right:0; padding-left:0;">
                                    <div class="sub-total-box" style="width:3.5cm; max-width:3.5cm;">
                                        <h6 style="margin-bottom:1pt; padding-bottom:1pt; font-size:10pt;">Fecha de Solicitud</h6>
                                        <h5 style="margin:0; padding:0; font font-weight:normal; font-size:9pt;">'.trim(date('d')."-".trim(tools::obtenermes(date('m')))."-".date('Y')).'</h5>
                                    </div>
                                </div>
                            </td>
                            <td style="border-bottom:2px solid black; text-align:center;">
                                <div class="col-sm-12 no-padding" style="padding-right:0; padding-left:0;">
                                    <div class="sub-total-box" style="width:3.5cm; max-width:3.5cm; text-align:center;">
                                        <h6 style="margin-bottom:1pt; padding-bottom:1pt; font-size:10pt;">Numero de dias</h6>
                                        <h5 style="text-align:center; margin:0; padding:0; font-weight:normal; font-size:9pt;"><center>5</center></h5>
                                    </div>
                                </div>
                            </td>
                            <td style="text-align:center; border-bottom:2px solid black;">
                                <div class="col-sm-12 no-padding" style="padding-right:0; padding-left:0;">
                                <div class="sub-total-box" style="width:3.5cm; max-width:3.5cm; text-align:center;">
                                    <h6 style="margin-bottom:; padding-bottom:0; font-size:10pt;">Fecha de Salida</h6>
                                    <h5 style="text-align:center; margin:0; padding:0;">&nbsp;</h5>
                                </div>
                            </td>
                            <td style="text-align:center; border-bottom:2px solid black;">
                                <div class="col-sm-12 no-padding" style="padding-right:0; padding-left:0;">
                                <div class="sub-total-box" style="width:3.5cm; max-width:3.5cm; text-align:center;">
                                    <h6 style="margin-bottom:0; padding-bottom:0; font-size:10pt;">Fecha de Regreso</h6>
                                    <h5 style="text-align:center; margin:0; padding:0;">&nbsp;</h5>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                    </div>
                </div>
                <div class="clearfix">&nbsp;</div>
                <div id="firmas" class="col-xs-12" style="margin-bottom:7pt;">
                    <div class="col-sm-12">
                        <div id="ttbPF" class="total-box" style="background-color: #EEEEEE !important; padding: 1em; text-align:justify;">
                            <span id="finalP" style="padding:1em; line-height: 24pt; color:#2F2F2F;">El colaborador deberá entregar a Capital Humano en un periodo de 5 días posteriores al nacimiento de su hijo(a) COPIA del
                                acta de alumbramiento para que los días con permiso de goce de sueldo séan aplicados.
                            </span>
                        </div>
                    </div>
                </div>
                <div class="clearfix" style="margin-bottom:42pt; margin-top:32pt;">&nbsp;</div>
                <table border="0" cellpadding="8" cellspacing="3" style="margin-top:48pt; padding-top:45pt; width:100%; font-size:10pt; font-family:\'Trebuchet MS\';">
                    <tbody>
                        <tr>
                            <td style="text-align:center;">
                                <div class="col-sm-4 col-xs-4 sign" style="width:100%; border-color: #000; border-top: 1pt solid #000;">
                                    <h5 class="text-left" style="letter-spacing:1.4pt;"><strong>FIRMA COLABORADOR</strong></h6>
                                </div>
                            </td>
                            <td style="text-align:center;">
                                <div class="col-sm-4 col-xs-4 sign" style="width:100%; border-color: #000; border-top: 1pt solid #000;">
                                    <h5 class="text-center" style="letter-spacing:1.4pt;"><strong>FIRMA JEFE INMEDIATO</strong></h6>
                                </div>
                            </td>
                            <td style="text-align:center;">
                                <div class="col-sm-4 col-xs-4 sign" style="width:100%; border-color: #000; border-top: 1pt solid #000;">
                                    <h5 class="text-right" style="letter-spacing:1.4pt;"><strong>FIRMA CAPITAL HUMANO</strong></h6>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id="bbP" class="bottom-bar" style="width: 100%; line-height:25pt; height: 24pt; margin-top:107.4pt; padding-top:4.5pt; background-color:#009947; position: absolute; right:0; bottom:0; left:0; text-align: center;"></div>
            </div>
        </div></body></html>';
    }else if(base64_decode($dataG) == "mat"){
        $html = '<html><head></head><body><div class="invoice-wrapper" id="back">
        <div class="invoice-top">
            <div class="row">
                <div class="col-sm-12">
                    <div class="invoice-top-left">
                        <h2 style="font-family:\'Trebuchet MS\'; font-weight:300; float:left; padding-top:7pt; margin-bottom:0; vertical-align: bottom;">PERMISO DE MATRIMONIO</h2>
                        <img style="float:right; margin-top:-20pt; margin-bottom: 10pt;" src="../img/LogoPortalAM.png" width="136" height="33">
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="invoice-top-right" style="text-align: justify; line-height: 24pt; margin-bottom:5pt;">
                        <span style="margin-top: 36pt; text-align:justify;">
                            <strong id="leym">Dentro de la ley mexicana</strong> no obliga a las empresas a brindar vacaciones a los trabajadores al contraer matrimonio, sin embargo
                            la empresa s&iacute; lo considera y brinda dos d&iacute;as libres. De acuerdo al Reglamento interno de trabajo.
                        </span>
                    </div>
                    <h4 id="reiterar" class="specs"><em id="enfasis">Este permiso esta sujeto a la autorizacion del Jefe inmediato y a las necesidades.</em></h4>
                </div>
            </div>
        </div>
        <div class="invoice-bottom">
            <div class="row" style="padding-bottom:10pt;">
                <div class="col-xs-12" style="margin-top:3pt;">
                    <div class="task-table-wrapper">
                        <h3 style="font-size:11pt; font-weight:thin; width:550px; letter-spacing:0.5pt; margin:0; padding:0;"><strong>Nombre</strong> &nbsp; </h3><div style="background-color:#F6F6F6; text-align:right; border-bottom:1px solid #ccc; margin-bottom:2pt; padding-bottom:1pt;"><i>'.utf8_encode($_SESSION['nameuser']).'</i></div>
                        <h3 style="font-size:11pt; font-weight:thin; width:550px; letter-spacing:0.5pt; margin:0; padding:0;"><strong>Puesto</strong> &nbsp; </h3><div style="background-color:#F6F6F6; text-align:right; border-bottom:1px solid #ccc; margin-bottom:2pt; padding-bottom:1pt;"><i>'.utf8_encode(consultas::printdeppues('pue',$_SESSION['idusuario'],$_SESSION['idempresa'])).'</i></div>
                        <h3 style="font-size:11pt; font-weight:thin; width:550px; letter-spacing:0.5pt; margin:0; padding:0;"><strong>Departamento</strong> &nbsp; </h3><div style="background-color:#F6F6F6; text-align:right; border-bottom:1px solid #ccc; margin-bottom:2pt; padding-bottom:1pt;"><i>'.utf8_encode(consultas::printdeppues('dep',$_SESSION['idusuario'],$_SESSION['idempresa'])).'</i></div>
                        <h3 style="font-size:11pt; font-weight:thin; width:550px; letter-spacing:0.5pt; margin:0; padding:0;"><strong>Observaciones</strong> &nbsp; </h3><div style="background-color:#F6F6F6; text-align:right; border-bottom:1px solid #ccc; margin-bottom:2pt; padding-bottom:1pt;"><i>&nbsp;</i></div><br/>
                        <table cellpadding="8" cellspacing="4" style="margin-top:12pt; margin-bottom:12pt; border-radius: 3mm / 3mm; background-color:#EEEEEE; color:#1e1e1e; text-align:justify;">
                            <tbody style="font-weight:300;">
                                <tr>
                                    <td>
                                        <div class="col-sm-12">
                                            <div id="ttbPM" class="total-box">
                                                <span id="medioM" style="line-height:24pt; letter-spacing:0.5pt; font-family:freesans; font-size:11pt;">El colaborador deber&aacute; entregar a Capital Humano en un periodo de 5 d&iacute;as posterior a la boda COPIA del acta de matrimonio para
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
                <div class="clearfix" style="margin-top:10pt; margin-bottom:10pt;">&nbsp;</div>
                <div class="col-md-12" style="margin-top:14pt;">
                <table cellspacing="4" style="width:100%; font-family:freesans;">
                    <tbody>
                        <tr><td style="text-align:center; background-clip: border-box; border-bottom:2px solid black;">
                                <div class="col-sm-12 no-padding" style="padding-right:0; padding-left:0;">
                                    <div class="sub-total-box" style="width:3.5cm; max-width:3.5cm;">
                                        <h6 style="margin-bottom:1pt; padding-bottom:1pt; font-size:10pt;">Fecha de Solicitud</h6>
                                        <h5 style="margin:0; padding:0; font font-weight:normal; font-size:9pt;">'.trim(date('d')."-".trim(tools::obtenermes(date('m')))."-".date('Y')).'</h5>
                                    </div>
                                </div>
                            </td>
                            <td style="border-bottom:2px solid black; text-align:center;">
                                <div class="col-sm-12 no-padding" style="padding-right:0; padding-left:0;">
                                    <div class="sub-total-box" style="width:3.5cm; max-width:3.5cm; text-align:center;">
                                        <h6 style="margin-bottom:1pt; padding-bottom:1pt; font-size:10pt;">Numero de dias</h6>
                                        <h5 style="text-align:center; margin:0; padding:0; font-weight:normal; font-size:9pt;"><center>2</center></h5>
                                    </div>
                                </div>
                            </td>
                            <td style="text-align:center; border-bottom:2px solid black;">
                                <div class="col-sm-12 no-padding" style="padding-right:0; padding-left:0;">
                                <div class="sub-total-box" style="width:3.5cm; max-width:3.5cm; text-align:center;">
                                    <h6 style="margin-bottom:; padding-bottom:0; font-size:10pt;">Fecha de Salida</h6>
                                    <h5 style="text-align:center; margin:0; padding:0;">&nbsp;</h5>
                                </div>
                            </td>
                            <td style="text-align:center; border-bottom:2px solid black;">
                                <div class="col-sm-12 no-padding" style="padding-right:0; padding-left:0;">
                                <div class="sub-total-box" style="width:3.5cm; max-width:3.5cm; text-align:center;">
                                    <h6 style="margin-bottom:0; padding-bottom:0; font-size:10pt;">Fecha de Regreso</h6>
                                    <h5 style="text-align:center; margin:0; padding:0;">&nbsp;</h5>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                    </div>
                </div>
                <div class="clearfix" style="margin-bottom:3pt; margin-top:3pt;">&nbsp;</div>
                <div id="firmas" class="col-xs-12" style="margin-bottom:10pt;">
                    <div class="col-sm-12">
                        <div id="ttbPF" class="total-box" style="padding: 1em; text-align:justify;">
                            <span id="finalP" style="padding:1em; line-height: 24pt; color:#2F2F2F;">&nbsp;</span>
                        </div>
                    </div>
                </div>
                <div class="clearfix" style="margin-bottom:32pt; margin-top:22pt;">&nbsp;</div>
                <table border="0" cellpadding="8" cellspacing="3" style="margin-top:40pt; padding-top:37pt; width:100%; font-size:10pt; font-family:\'Trebuchet MS\';">
                    <tbody>
                        <tr>
                            <td style="text-align:center;">
                                <div class="col-sm-4 col-xs-4 sign" style="width:100%; border-color: #000; border-top: 1pt solid #000;">
                                    <h5 class="text-left" style="letter-spacing:1.4pt;"><strong>FIRMA COLABORADOR</strong></h6>
                                </div>
                            </td>
                            <td style="text-align:center;">
                                <div class="col-sm-4 col-xs-4 sign" style="width:100%; border-color: #000; border-top: 1pt solid #000;">
                                    <h5 class="text-center" style="letter-spacing:1.4pt;"><strong>FIRMA JEFE INMEDIATO</strong></h6>
                                </div>
                            </td>
                            <td style="text-align:center;">
                                <div class="col-sm-4 col-xs-4 sign" style="width:100%; border-color: #000; border-top: 1pt solid #000;">
                                    <h5 class="text-right" style="letter-spacing:1.4pt;"><strong>FIRMA CAPITAL HUMANO</strong></h6>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id="bbP" class="bottom-bar" style="width: 100%; line-height:25pt; height: 24pt; margin-top:90.1pt; padding-top:8.1pt; background-color:#009947; position: absolute; right:0; bottom:0; left:0; text-align: center;"></div>
            </div>
        </div></body></html>';
    }
    GeneraFormatos('', '', $html, 'Paternidad', 'P', 7.5, 7.5, 5.9, 4.6, $dataG);
}else {
    header('Location: ..\..\index.php');
} ?>