<?php include_once "funciones.php"; 
if(isset($_POST['key'])){
$carpetaAdjunta="../img/sliders/"; $body=explode("=",@file_get_contents('php://input')); $narchi = str_replace("+", " ", $body[1]); unlink($carpetaAdjunta.$narchi); echo 0;
}else{
    header('Location: ../../index.php');
}
?>