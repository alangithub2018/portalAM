<?php include_once "funciones.php"; ini_set("memory_limit", "-1");
    $empress = base64_decode($_GET['evlent']); $mybase = htmlspecialchars(consultas::retnmempr($empress)); $typerpt = base64_decode($_GET['trpt']);
    if (isset($_GET['evlrpt'])) { $gntpyrpt = base64_decode($_GET['evlrpt']); } 
    if (isset($_GET['first']) and isset($_GET['end'])) { $dtin = base64_decode($_GET['first']); $dfin = base64_decode($_GET['end']); }
    $htmlE = '<table width="100%" cellspacing="0" cellpadding="0" style="font-size:0.75em; font-family:freesans;">';
    $title = '<table width="100%" cellspacing="0" cellpadding="0" style="font-size:0.68em; font-family:freesans;"><tr><th width="75" align="left"><h2>' . $mybase . '</h2></th><th align="right" width="25" style="font-weight:normal; font-size:1.2em;">' . date('Y-m-d H:i:s') . '</th></tr>'; 
    switch ($typerpt) {
        case '1':
            $cns_gnvcs = "SELECT U.usuario AS empleado, U.nombre AS nomemp, periodo, dias, tomados, pendientes, vigencia FROM vac_periodos P INNER JOIN com_usuarios U ON P.usuario = U.id WHERE pendientes > 0 and periodo <= '" . $gntpyrpt . "' And U.empresa = '" . $empress . "' And U.estatus = 1 ORDER BY P.periodo Asc, U.nombre;";
            $title .= '<tr><td style="font-size:1.2em;">REPORTE DE VACACIONES AL PERIODO ' . $gntpyrpt . '</td><td>&nbsp;</td></tr></table><table cellspacing="0" cellpadding="0" style="font-family:freesans; color:#FFFFFF; font-weight:bold; font-size:1em; background-color:seagreen;"><tr><td width="9%" style="text-align:left;">EMPLEADO</td><td width="35%" style="text-align:left;">NOMBRE</td><td width="10%" align="center">PERIODO</td><td width="10%" align="center">DIAS</td><td width="10%" align="center">TOMADOS</td><td width="10%" align="center">PENDIENTES</td><td width="10%" align="center">VIGENCIA</td></tr></table>';
            $rs_vcspdf = mysqli_query($conexion, $cns_gnvcs); $ttl_vcspdf = mysqli_num_rows($rs_vcspdf);
            if ($ttl_vcspdf > 0) {
                while ($rw_vcspdf = mysqli_fetch_assoc($rs_vcspdf)) {
                    $empleado = substr($rw_vcspdf['empleado'], 2);
                    $htmlE .= '<tr><td width="10%">' . $empleado . '</td><td width="40%">' . utf8_encode($rw_vcspdf['nomemp']) . '</td><td width="10%">' . $rw_vcspdf['periodo'] . '</td><td width="10%">' . $rw_vcspdf['dias'] . '</td><td width="10%">' . $rw_vcspdf['tomados'] . '</td><td width="10%">' . $rw_vcspdf['pendientes'] . '</td><td width="10%">' . $rw_vcspdf['vigencia'] . '</td></tr>';
                }
                $htmlE .= '</table>';
                $sal = $mybase.'_Al_Periodo_'.$gntpyrpt;
                reportes::GeneraPDF($title, 'empleados con vacaciones pendientes', $htmlE, $sal, 'P', 6, 6, 17.5, 4.5, FALSE);
            } else {
                echo "<script languaje=\"javascript\">alert('No hay registros de vacaciones en el sistema!'); window.close();</script>";
            }
            break;
        case '2':
            $cns_gnvcs = "SELECT S.folio, U.nombre AS nomemp, P.periodo AS periodo, S.estatus, S.vacaciones AS lds, S.fecha AS registro FROM `vac_solicitud` S INNER JOIN com_usuarios U ON S.usuario = U.id INNER JOIN com_empresas E ON U.empresa = E.codigo INNER JOIN vac_periodos P ON S.periodo = P.id WHERE SUBSTRING(U.usuario,3) = '" . $gntpyrpt . "' AND U.empresa = '" . $empress . "' ORDER BY folio DESC";
            $datauser = consultas::getdatauser($gntpyrpt, $empress, FALSE, 'n');
            if (!empty($datauser)) {
                $nameing = explode(",", $datauser);
                $nameuser = utf8_decode($nameing[1]); $inguser = $nameing[2]; $emplead = $nameing[3]; $dateinguser = explode("-", $inguser);
                $title .= '<tr><td><span style="font-weight:normal; font-size:1.11em;">' . $gntpyrpt . ' - ' . utf8_encode($nameuser) . ' - ' . $dateinguser[2] . '/' . tools::reducemes(tools::obtenermes($dateinguser[1])) . '/' . $dateinguser[0] . '</span></td><td>&nbsp;</td></tr></table><table width="100%" cellspacing="0" cellpadding="1" style="font-family:freesans; color:#FFFFFF; font-weight:bold; border:1px solid seagreen; font-size:0.71em; background-color:seagreen;"><tr><td width="9%">FOLIO</td><td align="center" width="8%">PERIODO</td><td align="center" width="8%">ESTATUS</td><td align="center" width="5%">DIAS</td><td width="55%">VACACIONES</td><td align="center" width="15%">REGISTRO</td></tr></table>';
            }
            $rs_vcspdf = mysqli_query($conexion, $cns_gnvcs); $ttl_vcspdf = mysqli_num_rows($rs_vcspdf);
            if ($ttl_vcspdf > 0) {
                while ($rw_vcspdf = mysqli_fetch_assoc($rs_vcspdf)) {
                    $misdts = explode(",", $rw_vcspdf['lds']);
                    $narray = [];
                    foreach ($misdts as $fmdts) {
                        $nfwdts = explode("-", $fmdts);
                        $nformat = $nfwdts[2] . '-' . tools::reducemes(tools::obtenermes($nfwdts[1])) . '-' . $nfwdts[0];
                        array_push($narray, $nformat);
                    }$narreg = implode("<strong>|</strong>", $narray);
                    $dias = count($narray);
                    $htmlE .= '<tr><td width="9%">' . $rw_vcspdf['folio'] . '</td><td align="center" width="8%">' . $rw_vcspdf['periodo'] . '</td><td align="center" width="8%">' . $rw_vcspdf['estatus'] . '</td><td align="center" width="5%">' . $dias . '</td><td width="55%">' . $narreg . '</td><td align="center" width="15%">' . $rw_vcspdf['registro'] . '</td></tr>';
                }
                $htmlE .= '</table>';
                $sal = $emplead . '-' . $mybase;
                reportes::GeneraPDF($title, 'solicitudes x usuario', $htmlE, $sal, 'P', 6, 6, 18, 4.5, FALSE);
            } else {
                echo "<script languaje=\"javascript\">alert('No hay registros de vacaciones en el sistema!'); window.close();</script>";
            }
            break;
        case '3':
            $cns_gnvcs = "SELECT S.folio, U.Usuario As empleado, U.Nombre As nomemp, P.periodo, Min(V.Fecha) As salida, Count(*) As dias, S.fecha AS registro FROM vac_vacaciones V, vac_solicitud S, com_usuarios U, vac_periodos P WHERE V.Fecha Between '" . $dtin . "' And '" . $dfin . "' And S.Id = V.Solicitud And U.Id = S.Usuario And P.id = S.periodo And U.empresa = '" . $empress . "' And S.estatus = 'A' Group By S.Folio, U.Usuario, U.Nombre, P.Periodo ORDER by S.folio DESC";
            $myinidt = explode("-", $dtin); $myfindt = explode("-", $dfin);
            $title .= '<tr><td><h4 style="font-weight:normal; font-size:1.15em;">Del ' . $myinidt[2] . ' de ' . tools::obtenermes($myinidt[1]) . ' del ' . $myinidt[0] . ' al ' . $myfindt[2] . ' de ' . tools::obtenermes($myfindt[1]) . ' del ' . $myfindt[0] . '</h4></td><td>&nbsp;</td></tr></table><table width="100%" cellspacing="0" cellpadding="0" style="font-family:freesans; color:#FFFFFF; font-weight:bold; border:1px solid seagreen; font-size:0.71em; background-color:seagreen;"><tr><th align="left" width="10%">FOLIO</th><th width="9%" align="center">EMPLEADO</th><th align="left" width="40%">NOMBRE</th><th align="center" width="9%">PERIODO</th><th align="center" width="9%">SALIDA</th><th align="center" width="9%">DIAS</th><th width="14%">REGISTRO</th></tr></table>';
            $rs_vcspdf = mysqli_query($conexion, $cns_gnvcs);  $ttl_vcspdf = mysqli_num_rows($rs_vcspdf);
            if ($ttl_vcspdf > 0) {
                while ($rw_vcspdf = mysqli_fetch_assoc($rs_vcspdf)) {
                    $empleado = substr($rw_vcspdf['empleado'], 2);
                    $htmlE .='<tr><td width="10%">' . $rw_vcspdf['folio'] . '</td><td width="9%" style="text-align:center">' . $empleado . '</td><td width="40%">' . utf8_encode($rw_vcspdf['nomemp']) . '</td><td align="center" width="9%">' . $rw_vcspdf['periodo'] . '</td><td align="center" width="9%">' . $rw_vcspdf['salida'] . '</td><td align="center" width="9%">' . $rw_vcspdf['dias'] . '</td><td width="14%">' . $rw_vcspdf['registro'] . '</td></tr>';
                }
                $htmlE .= '</table>';
                $sal = $mybase . '_' . $dtin . '_' . $dfin;
                reportes::GeneraPDF($title, 'vacaciones al corte de fecha', $htmlE, $sal, 'P', 6, 6, 17, 4.5, FALSE);
            } else {
                echo "<script languaje=\"javascript\">alert('No hay registros de vacaciones en el sistema!'); window.close();</script>";
            }
            break;
    }?>