<?php set_time_limit(0); include_once "funciones.php"; ini_set("memory_limit", "-1");
    $title = '<table width="100%" cellspacing="0" cellpadding="0" style="font-size:0.68em; font-family:freesans;"><tr><th width="75" align="left"><h2>EMPLEADOS REGISTRADOS EN COMEDOR</h2></th><th align="right" width="25" style="font-weight:normal; font-size:1.2em;">' . date('Y-m-d H:i:s') . '</th></tr></table><table width="100%" cellspacing="0" cellpadding="0" style="font-family:freesans; color:#FFFFFF; font-weight:bold; font-size:0.8em; background-color:seagreen;"><tr><td width="5%">NO.</td><td width="30%">NOMBRE</td><td width="27%">MENU</td><td width="28%">EMPRESA</td><td width="10%">FECHA</td></tr></table>'; 
    $htmlE = '<table width="100%" cellspacing="1" cellpadding="0" style="font-size:0.78em; font-family:freesans;">';
    if (isset($_GET['ref']) and ! empty($_GET['ref'])) {
        $refmnfch = base64_decode($_GET['ref']); $fechamn = obtenerfch($refmnfch);
        $busquedahoy = "SELECT E.codigo , U.empresa, U.id AS iduser, M.id AS idmenu, F.menu, F.id, R.menuporfecha, U.`usuario` AS empleado, U.`nombre` AS nomusuario, M.`nombre` AS nommenu, E.`descripcion` AS empresa, F.`fecha` AS fechamenu, R.`registro` AS anotado FROM `com_anotaciones` R INNER JOIN `com_menuporfecha` F ON R.`menuporfecha` = F.`id` INNER JOIN `com_menus` M ON F.`menu` = M.`id` INNER JOIN `com_usuarios` U ON R.`usuario` = U.`id` INNER JOIN `com_empresas` E ON U.`empresa` = E.`codigo` AND F.`fecha` = '" . $fechamn . "' ORDER BY U.nombre, E.descripcion";
    } elseif (isset($_GET['rgini']) and ! empty($_GET['rgini']) and isset($_GET['rgfin']) and ! empty($_GET['rgfin'])) {
        $vfchini = base64_decode($_GET['rgini']); $vfchfin = base64_decode($_GET['rgfin']);
        $busquedahoy = "SELECT E.codigo , U.empresa, U.id AS iduser, M.id AS idmenu, F.menu, F.id, R.menuporfecha, U.`usuario` AS empleado, U.`nombre` AS nomusuario, M.`nombre` AS nommenu, E.`descripcion` AS empresa, F.`fecha` AS fechamenu, R.`registro` AS anotado FROM `com_anotaciones` R INNER JOIN `com_menuporfecha` F ON R.`menuporfecha` = F.`id` INNER JOIN `com_menus` M ON F.`menu` = M.`id` INNER JOIN `com_usuarios` U ON R.`usuario` = U.`id` INNER JOIN `com_empresas` E ON U.`empresa` = E.`codigo` AND F.`fecha` BETWEEN CAST('" . $vfchini . "' AS DATE) AND CAST('" . $vfchfin . "' AS DATE) ORDER BY U.nombre, E.descripcion";
    } else { $busquedahoy = "SELECT E.codigo , U.empresa, U.id AS iduser, M.id AS idmenu, F.menu, F.id, R.menuporfecha, U.`usuario` AS empleado, U.`nombre` AS nomusuario, M.`nombre` AS nommenu, E.`descripcion` AS empresa, F.`fecha` AS fechamenu, R.`registro` AS anotado FROM `com_anotaciones` R INNER JOIN `com_menuporfecha` F ON R.`menuporfecha` = F.`id` INNER JOIN `com_menus` M ON F.`menu` = M.`id` INNER JOIN `com_usuarios` U ON R.`usuario` = U.`id` INNER JOIN `com_empresas` E ON U.`empresa` = E.`codigo` AND F.`fecha` = CURDATE() ORDER BY U.nombre, E.descripcion";
    }$sql_anotados = $busquedahoy; $rs_anotados = mysqli_query($conexion, $sql_anotados); $tot_anotados = mysqli_num_rows($rs_anotados);
    if ($tot_anotados > 0) {
        while ($rows_anotados = mysqli_fetch_assoc($rs_anotados)) {
            $nfecha = explode("-", $rows_anotados['fechamenu']);
            $htmlE .= '<tr><td width="5%">' . substr($rows_anotados['empleado'], 2) . '</td><td width="30%">' . utf8_encode($rows_anotados['nomusuario']) . '</td><td width="27%">' . utf8_encode($rows_anotados['nommenu']) . '</td><td width="28%">' . utf8_encode($rows_anotados['empresa']) . '</td><td width="10%">';
            $htmlE .= $nfecha[2] . '/' . tools::reducemes(tools::obtenermes($nfecha[1])) . '/' . $nfecha[0];
            $htmlE .= '</td></tr>';
        }$htmlE .= '<tr><td colspan="2">Anotados: <strong>' . $tot_anotados . '</strong></td><td>&nbsp;</td>';
        if (isset($_GET['ref']) and ! empty($_GET['ref'])) {
            $dato_get = base64_decode($_GET['ref']); $fchtot = obtenerfch($dato_get);
            $tots = totales("SELECT SUM(`precio`) as total FROM `com_anotaciones` R INNER JOIN `com_menuporfecha` F ON R.`menuporfecha` = F.`id` AND F.`fecha` = '" . $fchtot . "';");
        } elseif (isset($_GET['rgini']) and ! empty($_GET['rgini']) and isset($_GET['rgfin']) and ! empty($_GET['rgfin'])) {
            $vtotini = base64_decode($_GET['rgini']); $vtotfin = base64_decode($_GET['rgfin']);
            $tots = totales("SELECT SUM(`precio`) as total FROM `com_anotaciones` R INNER JOIN `com_menuporfecha` F ON R.`menuporfecha` = F.`id` AND F.`fecha` BETWEEN CAST('" . $vtotini . "' AS DATE) AND CAST('" . $vtotfin . "' AS DATE);");
        } else {
            $tots = totales("SELECT SUM(`precio`) as total FROM `com_anotaciones` R INNER JOIN `com_menuporfecha` F ON R.`menuporfecha` = F.`id` AND F.`fecha` = CURDATE();");
        }$htmlE .= $tots; $htmlE .= '</table>'; $sal = 'ANOTADOS_' . date('Y-m-d') . '_' . date('H-i-s');
        reportes::GeneraPDF($title, 'Comedor AM', $htmlE, $sal, 'L', 6, 6, 13.5, 4.5, FALSE);
    } else {
        if ($tot_anotados == 0) {
            echo "<script languaje=\"javascript\">alert('No hay registros en las fechas que has ingresado, revisa las fechas del menu!'); window.close();</script>";
        }
}mysqli_free_result($rs_anotados); ?>