<?php include_once "assets/includes/funciones.php";
require_once("Mail.php");
require_once("Mail/mime.php");
function generanewpass() {
            $disponibles = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
            $longitud = 8;
            $nwclave = "";
            for ($i = 0; $i < $longitud; $i++) {
                $nwclave.=substr($disponibles, rand(1, strlen($disponibles)), 1);
            }return $nwclave;
        }

        function enviarmail($nombreenv, $userenviar, $emailenviar, $passuser, $vnss, $vingreso) {
            global $conexion;
            if (strlen($passuser) == 40) {
                $passuser = generanewpass();
                $sql_updnwclave = "UPDATE com_usuarios SET clave = '" . $passuser . "' WHERE nss = '" . $vnss . "'";
                mysqli_query($conexion, $sql_updnwclave);
            }$from = 'Comedor A.M. <public@am.com.mx>';
            $subject = "Recuperacion de contraseña  - Sistema de Comedor";
            $subject = utf8_decode($subject);
            $content_type = 'MIME-Version: 1.0' . "\r\n";
            $content_type = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $body = "Estimado " . $nombreenv . " Agradecemos tu participacion en el nuevo comedor los datos siguientes <br> corresponden a tu informacion de acceso al sistema. <br> <strong>Usuario: </strong><u>" . $userenviar . "</u><br><strong>Clave: </strong><u>" . $passuser . "</u><br>Atentamente: Plataforma A.M.";
            $to = $emailenviar;
            $headers = array('From' => $from, 'To' => $to, 'Subject' => $subject, 'Content-Type' => $content_type);
            $mime = new Mail_mime();
            $mime->setHTMLBody($body);
            $body = $mime->get();
            $headers = $mime->headers($headers);
            $host = 'smtp.am.com.mx';
            $username = 'public@am.com.mx';
            $password = 'Pass2015#';
            $smtp = @Mail::factory('smtp', array('host' => $host, 'auth' => true, 'username' => $username, 'password' => $password));
            @$smtp->send($to, $headers, $body);
        }
if (isset($_POST['us']) and !empty($_POST['us']) and isset($_POST['em']) and !empty($_POST['em'])){
    $nss = $_POST['us']; $ingreso = $_POST['em']; global $conexion; global $conexion_sql;
    if ($conexion) {
        $sql_emaillocal = "SELECT * FROM com_usuarios WHERE nss = '" . $nss . "' AND ingreso = '" . $ingreso . "'";
        $rs_emaillocal = mysqli_query($conexion, $sql_emaillocal);
        $tots_emaillocal = mysqli_num_rows($rs_emaillocal);
        if ($tots_emaillocal > 0) {
            $rw_emaillocal = mysqli_fetch_assoc($rs_emaillocal);
            $nombuser = $rw_emaillocal['nombre'];
            $userofsystem = $rw_emaillocal['usuario'];
            $emailsystem = $rw_emaillocal['email'];
            $claveuser = $rw_emaillocal['clave'];
            if (empty($emailsystem)) {
                if ($conexion_sql) {
                    $sql_datamail = "Select D.email From Empleados E INNER JOIN Datosemps D ON D.Codigo = E.Codigo And D.empresa = E.empresa Where E.Afiliacion = '" . substr($nss, 0, 10) . "' And E.digvera = '" . substr($nss, 10, 1) . "'";
                    $stmt_datamail = odbc_exec($conexion_sql, $sql_datamail);
                    if ($stmt_datamail === false) {
                        throw new ErrorExcpetion(odbc_errormsg());
                    } else {
                        if ($row_datamail = odbc_fetch_row($stmt_datamail)) {
                            $emailsystem = odbc_result($stmt_datamail, "email");
                            $sql_updatemail = "UPDATE com_usuarios SET email = '" . $emailsystem . "' WHERE nss = '" . $nss . "'";
                            mysqli_query($conexion, $sql_updatemail);
                            odbc_close($conexion_sql);
                        } else {
                            echo "0";
                        }
                    }
                }
            }enviarmail($nombuser, $userofsystem, $emailsystem, $claveuser, $nss, $ingreso);
            echo " Recuperacion de Acceso al Sistema Exitosa!, te hemos enviado un email con las credenciales de acceso al correo electronico registrado en Nominas.";
        } else {
            echo "0";
        }
    }
}?>