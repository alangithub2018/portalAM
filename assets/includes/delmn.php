<?php include_once "funciones.php"; $idelim=$_POST['dl']; $sql_verdate="SELECT F.`id` as actualizame, F.`fecha` FROM `com_anotaciones` R INNER JOIN `com_menuporfecha` F ON R.`menuporfecha` = F.`id` WHERE R.`id` = '".$idelim."'";$rs_verdate=mysqli_query($conexion,$sql_verdate);$rw_verdate=mysqli_fetch_assoc($rs_verdate);$actgl=$rw_verdate['actualizame']; if(strtotime($rw_verdate['fecha'])==strtotime(date('Y-m-d'))){if(strtotime(date('H:i'))<strtotime('12:00')){elimmn($idelim,$actgl);}else {echo "Ya no puedes cancelar este menu, ya son las 12:00 pm!";}}else {elimmn($idelim,$actgl);}?>