<?php include_once "funciones.php";date_default_timezone_set('America/Mexico_City');$idmenu=$_POST['idm'];$insempresa=$_SESSION['idempresa'];$nameusuario=$_SESSION['nameuser'];$insusuario=$_SESSION['idusuario'];if($conexion){$sql_obtdtmn="SELECT * FROM com_menuporfecha WHERE id = '".$idmenu."'";$rs_obtdtmn=mysqli_query($conexion,$sql_obtdtmn);$rows_obtdtmn=mysqli_fetch_assoc($rs_obtdtmn);$vinsusuario=substr($insusuario,2,strlen($insusuario));if($rows_obtdtmn['disponibles']!=0){$sql_insanotacion="INSERT INTO `aplicaciones`.`com_anotaciones` (`id`, `usuario`, `menuporfecha`, `registro`) VALUES (NULL, '".$_SESSION['idloguser']."', '".$idmenu."', now());";$rs_insanotacion=mysqli_query($conexion,$sql_insanotacion); if($rs_insanotacion){ $rdate = explode("-", $rows_obtdtmn['fecha']); echo "Ya estas registrado en el menu del dia ".tools::retornadia($rows_obtdtmn['dia'])." ".$rdate[2]."/".tools::reducemes(tools::obtenermes($rdate[1]))."/".$rdate[0]; $sumar=true;actapdspsystem($rows_obtdtmn['apartados'],$rows_obtdtmn['disponibles'],$idmenu,$sumar,'');}}else {echo "Ya no hay espacios disponibles en este menu, o es despues de la 1:00pm!!";}}?>