<?php include_once "funciones.php"; 
if(isset($_POST['idv']) and !empty($_POST['idv'])){ 
    $verificalo=$_POST['idv']; global $conexion; 
    if($conexion){
        $sql_verifica="SELECT * FROM `com_usuarios` WHERE `nss` = '".$verificalo."' AND estatus = '1'";
        $rs_verifica=mysqli_query($conexion,$sql_verifica); 
        $rows_verifica=mysqli_num_rows($rs_verifica); 
        if($rows_verifica>0){echo "1";}else{echo "0";}
    }
}else{header('Location: ..\..\comedor.php');}?>