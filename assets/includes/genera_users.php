<?php include_once "funciones.php"; ini_set("memory_limit", "-1");
        if (isset($_GET['eusrs']) and ! empty($_GET['eusrs']) and isset($_GET['ousrs']) and ! empty($_GET['ousrs']) and isset($_GET['stts']) and ! empty($_GET['stts'])) {
            $dtempusrs = base64_decode($_GET['eusrs']); $estss = base64_decode($_GET['stts']); $orden = base64_decode($_GET['ousrs']);
            $dtsts = base64_decode($_GET['stts']) == '1' ? 'ACTIVOS' : 'INACTIVOS';
            $title = '<table width="100%" cellspacing="0" cellpadding="0" style="font-size:0.68em; font-family:freesans;"><tr><th width="75" align="left"><h2>' . consultas::retnmempr(base64_decode($_GET["eusrs"])) . '</h2></th><th align="right" width="25" style="font-weight:normal; font-size:1.2em;">' . date('Y-m-d H:i:s') . '</th></tr><tr><td style="font-weight:normal; font-size:1.1em;">USUARIOS ' . $dtsts . ' EN PORTAL AM</td><td>&nbsp;</td></tr></table><table width="100%" cellspacing="0" cellpadding="0" style="font-family:freesans; color:#FFFFFF; font-weight:bold; font-size:0.8em; background-color:seagreen;"><tr><th width="5%" style="text-align:left;">No.</th><th width="11%" style="text-align:left;">NSS</th><th width="41%" style="text-align:left;">NOMBRE</th><th width="33%" style="text-align:left;">EMAIL</th><th width="10%" style="text-align:left;">INGRESO</th></tr></table>';
            $data_usrs = trim(consultas::prntusers('', $orden, $dtempusrs, $estss, '1'));
            if ($data_usrs != '') {
                $htmlusr = '<table width="100%" cellspacing="0" cellpadding="1" style="font-size:0.7098em; font-family:freesans;">'.$data_usrs.'</table>';
                reportes::GeneraPDF($title, 'Usuarios de Portal AM', $htmlusr, 'USUARIOS_' . date('Y-m-d_H-i-s'), 'P', 6, 6, 16.8, 4.5, FALSE);
            } else { echo "<script languaje=\"javascript\">alert('No hay registros con los filtros ingresados!'); window.close();</script>"; }
        } else { header('Location: ..\..\index.php'); }
?>