<?php include_once "funciones.php"; ini_set("memory_limit", "-1");
    function GeneraPDFLocal($encabezado, $tpie, $cuerpo, $salida, $orientacion, $mglft, $mgrght, $mgitop, $mgbottom, $solicitud){
        $mpdf = new mPDF('utf-8', array(215.9,279.4) , 0, '', 7.5, 7.5, 0, 15, 6, 4.5, $orientacion);
        $mpdf->cacheTables = true; $mpdf->simpleTables = true; $mpdf->packTableData = true;
        $mpdf->SetHTMLHeader($encabezado);
        $mpdf->setAutoTopMargin = 'stretch';
        $mpdf->autoMarginPadding = 14.5;
        if(!$solicitud){$mpdf->SetHTMLFooter('<table width="100%" style="vertical-align: bottom; border-bottom: 1px solid #000000; font-family: freesans; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;"><tr><td width="33%"><span style="font-weight: bold; font-style: italic;">{DATE j-M-Y}</span></td><td width="33%" align="center" style="font-weight: bold; font-style: italic;">'.$tpie.'</td><td width="33%" style="text-align: right; ">{PAGENO}/{nbpg}</td></tr></table>');
        }else{ $mpdf->SetHTMLFooter(''); }
        $mpdf->WriteHTML($cuerpo); $mpdf->Output($salida. '.pdf', 'D');
    }
        if (isset($_GET['fMa'],$_GET['pLa'],$_GET['mAn'])) {
            //$contenido = consultas::getCumpleanios($_GET['pLa'], $_GET['mAn']);
            $jsonString = tools::encrypt_decrypt('decrypt', file_get_contents(__DIR__.'/json/cumpleanios.json'));
            $data = json_decode(base64_decode($jsonString), true);
            
            // Obtener una lista de columnas
            foreach ($data as $clave => $fila) {
                $nomb[$clave] = $fila['nombre'];
                $dept[$clave] = $fila['departamento'];
                $dya[$clave] = $fila['dia'];
            }

            // Ordenar los datos con volumen descendiente, edición ascendiente
            // Agregar $datos como el último parámetro, para ordenar por la clave común
            array_multisort($dya, SORT_ASC, $nomb, SORT_ASC, $data);
            
            foreach ($data as $i => $values) {
                $contenidoC .= "<tr>";
                foreach ($values as $key => $value) {
                    $contenidoC .= "<td>".$value."</td>";
                }
                $contenidoC .= "</tr>";
            }
            $nameImage = $_GET['fMa'];
            $htmlE ="<html><head></head><body style='background-image:url(../img/cumpleanios/$nameImage.jpg); background-repeat:no-repeat; width:814px; height:1054px; background-position: center;'>";
            $htmlE.='<style>@page{}</style><table width="100%" cellspacing="2" cellpadding="1" style="font-size:0.80em;"><tr><td>&nbsp;</td><td style="text-align:right;">&nbsp;</td></tr></table>';
            $htmlE.='<table align="center" width="99.5%" cellpadding="2" cellspacing="1" style="font-size:0.80em;"><thead><tr><th align="left">NOMBRE</th><th align="left">DEPARTAMENTO</th><th align="left">DIA</th></tr></thead><tbody>'.$contenidoC.'</tbody></table></body></html>';
            GeneraPDFLocal('', '', $htmlE, 'Cumpleanios-'. tools::obtenermes(intval($_GET['mAn'])).'-'.$_GET['pLa'], 'P', 7.5, 7.5, 5.9, 9.5, TRUE);
        } ?>