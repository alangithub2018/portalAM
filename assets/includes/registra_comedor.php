<?php include_once "funciones.php";
if (isset($_POST['us']) and isset($_POST['cl']) and isset($_POST['nu']) and isset($_POST['fi']) and isset($_POST['ns']) and isset($_POST['em']) and isset($_POST['dp']) and ! empty($_POST['us']) and ! empty($_POST['cl']) and ! empty($_POST['nu']) and ! empty($_POST['fi']) and ! empty($_POST['ns']) and ! empty($_POST['em']) and ! empty($_POST['dp'])) {
    $vusuario = $_POST['us'];
    $claveuser = $_POST['cl'];
    $nusuario = $_POST['nu'];
    $fingreso = $_POST['fi'];
    $nssuser = $_POST['ns'];
    $nempresa = $_POST['em'];
    $ndept = $_POST['dp'];
    $emailusr = $_SESSION['usermail'];
    if (substr($vusuario, 0, 1) == '0') {
        $insempresa = substr($vusuario, 1, 1);
    } else {
        $insempresa = substr($vusuario, 0, 2);
    } global $conexion;
    if ($conexion) {
        $sql_insertauser = "INSERT INTO `aplicaciones`.`com_usuarios` (`id`, `nss`, `empresa`, `usuario`, `clave`, `nombre`, `email` , `ingreso`, `tipo`) VALUES (NULL, '" . $nssuser . "', '" . $insempresa . "', '" . $vusuario . "', '" . $claveuser . "', '" . utf8_decode($nusuario) . "', '" . $emailusr . "' , '" . $fingreso . "', '4');";
        $rs_insertauser = mysqli_query($conexion, $sql_insertauser);
        if ($rs_insertauser) {
            echo trim('Usuario registrado exitosamente!');
            unset($_SESSION['usermail']);
        } else {
            echo trim('Error al insertar el usuario!');
            unset($_SESSION['usermail']);
        }
    }
} else {
    header('Location: ..\..\comedor.php');
}?>