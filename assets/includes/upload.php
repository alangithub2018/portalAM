<?php include_once "funciones.php";
header('Content-Type: application/json');
if(isset($_FILES['file-es']['name'])){
$carpetaAdjunta="../img/sliders/";
$Imagenes=count($_FILES['file-es']['name']);
    for($i=0;$i<$Imagenes;$i++){
        $nombreArchivo=$_FILES['file-es']['name'][$i];
        $nombreTemporal=$_FILES['file-es']['tmp_name'][$i];
        $rutaArchivo=$carpetaAdjunta.$nombreArchivo;
        move_uploaded_file($nombreTemporal,$rutaArchivo);
        $infoImagenesSubidas[$i]=array("caption"=>"$nombreArchivo","height"=>"120px","url"=>"assets/includes/borrar.php","key"=>$nombreArchivo);
        $rutareal="assets/img/sliders/".$nombreArchivo;
        $ImagenesSubidas[$i]="<img style='width:auto;height:auto;max-width:100%;max-height:100%;' src='$rutareal' class='file-preview-image'>";
        $arr=array("file_id"=>0,"overwriteInitial"=>true,"initialPreviewConfig"=>$infoImagenesSubidas,"initialPreview"=>$ImagenesSubidas);
        echo json_encode($arr);
    }
}else{
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
        echo "El servidor no recibió el archivo, problema del script JS!!";
    }else{
        header('Location: ../../index.php');
    }
}?>