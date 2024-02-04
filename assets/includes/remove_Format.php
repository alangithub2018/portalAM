<?php include_once "funciones.php";
    if (isset($_POST['ft'],$_POST['sp'])) {
        $arrFls = array(str_replace("assets", "..", $_POST['ft']),str_replace("assets", "..", $_POST['sp']));
        
        foreach ($arrFls as $flv){
            if(file_exists($flv)){
                @unlink($flv);
            }
        }
        if(!file_exists($arrFls[0]) and ! file_exists($arrFls[1])){
            echo "Formato eliminado exitosamente!!";
        }
    }
?>