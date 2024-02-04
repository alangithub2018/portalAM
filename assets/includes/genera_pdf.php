<?php include_once "funciones.php"; ini_set("memory_limit", "-1");
        if (isset($_GET['dti']) and ! empty($_GET['dti']) and isset($_GET['dtf']) and ! empty($_GET['dtf']) and isset($_GET['dte']) and ! empty($_GET['dte'])) {
            $cempresa = base64_decode($_GET['dte']); $finicial = base64_decode($_GET['dti']);
            $datefinal = base64_decode($_GET['dtf']); $dateemp = base64_decode($_GET['dtnem']);
            $rptini = explode("-", $finicial); $rptfin = explode("-", $datefinal);
            $title = '<table width="100%" cellspacing="0" cellpadding="0" style="font-size:0.68em; font-family:freesans;"><tr><th width="75" align="left"><h2>' . strtoupper(utf8_encode($dateemp)) . '</h2></th><th align="right" width="25" style="font-weight:normal; font-size:1.17em;">' . date('Y-m-d H:i:s') . '</th></tr><tr><td style="font-size:1.2em;">CONSUMO DE EMPLEADOS EN COMEDOR</td><td align="right" style="font-size:1.2em;">' . $rptini[2] . '-' . strtoupper(tools::obtenermes($rptini[1])) . '-' . $rptini[0] . ' / ' . $rptfin[2] . '-' . strtoupper(tools::obtenermes($rptfin[1])) . '-' . $rptfin[0] . '</td></tr></table><table width="100%" cellspacing="0" cellpadding="0" style="font-family:freesans; color:#FFFFFF; font-weight:bold; font-size:0.8em; background-color:seagreen;"><tr><th align="left" width="12%">NO.</th><th align="left" width="43%">NOMBRE</th><th align="center" width="20%">CANTIDAD</th><th align="center" width="25%">IMPORTE</th></tr></table>';
            $htmlE = '<table width="100%" cellspacing="1" cellpadding="1" style="font-size:0.78em; font-family:freesans;">';
            $query_pdf = "SELECT SUBSTRING(U.`usuario`, 3) AS empleado, U.`nombre` AS nombre, COUNT(`precio`) AS cantidad, SUM(`precio`) AS importe, F.fecha FROM `com_anotaciones` R INNER JOIN `com_menuporfecha` F ON R.`menuporfecha` = F.`id` INNER JOIN `com_usuarios` U ON R.usuario = U.id WHERE U.`empresa` = '" . $cempresa . "' AND F.`fecha` BETWEEN CAST('" . $finicial . "' AS DATE) AND CAST('" . $datefinal . "' AS DATE) GROUP BY U.`nombre`";
            $rs_pdf = mysqli_query($conexion, $query_pdf);
            $sal='COMEDOR_' . date('Y-m-d') . '_' . date('H-i-s');
            $suma_cant = 0; $suma_importe = 0;
            while ($rw_pdf = mysqli_fetch_assoc($rs_pdf)) {
                $htmlE .= '<tr><td width="12%" style="font-size:1em;">' . $rw_pdf['empleado'] . '</td><td width="43%" style="font-size:1em;">' . utf8_encode($rw_pdf['nombre']) . "</td><td style=\"text-align: center; font-size:1em;\" width=\"20%\">" . $rw_pdf['cantidad'] . "</td><td style=\"text-align: center; font-size:1em;\" width=\"25%\">" . $rw_pdf['importe'] . "</td></tr>";
                $suma_cant = $rw_pdf['cantidad'] + $suma_cant; $suma_importe = $rw_pdf['importe'] + $suma_importe;
            }$htmlE .= '<tr><td colspan="2" align="right"><strong>TOTALES:</strong></td><td style="text-align: center;"><strong>' . $suma_cant . '</strong></td><td style="text-align: center;"><strong>$' . $suma_importe . '</strong></td></tr>';
            $htmlE .= '</table>';
            $total_pdf = mysqli_num_rows($rs_pdf);
            if ($total_pdf == 0) {
                echo "<script languaje=\"javascript\">alert('No hay registros en las fechas que has ingresado, revisa las fechas del menu!'); window.close();</script>";
            } else {
                reportes::GeneraPDF($title, 'reporte de consumos', $htmlE, 'COMEDOR_' . date('Y-m-d') . '_' . date('H-i-s'), 'P', 6, 6, 17, 4.5, FALSE);
            }mysqli_free_result($rs_pdf);
        } ?>