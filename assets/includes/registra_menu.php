<?php include_once "funciones.php"; if($conexion){$idmen=$_POST['idm']; $diamenu=$_POST['dm']; $fechadia=$_POST['fd']; $cantmenu=$_POST['cm'];$preciomenu=$_POST['pr']; $semana=$_POST['sm']; $mes=$_POST['ms']; $lanio = $_POST['an']; $sql_insertamenu="INSERT INTO `aplicaciones`.`com_menuporfecha` (`id`, `menu`, `fecha`, `cantidad`, `apartados`, `disponibles`, `precio`, `dia`, `semana`) VALUES (NULL, '".$idmen."', '".$lanio."-".$mes."-".$fechadia."', '".$cantmenu."', '0', '".$cantmenu."', '".$preciomenu."', '".$diamenu."', '".$semana."');"; $rs_insertamenu=mysqli_query($conexion,$sql_insertamenu);if($rs_insertamenu){echo trim('Menu agregado con exito!'); }else {echo trim('Error al agregar el menu!');}}?>