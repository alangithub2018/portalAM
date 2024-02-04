<?php include_once "funciones.php"; ini_set("memory_limit", "-1");
    if (isset($_POST['fm'],$_POST['pl'],$_POST['lm'])) {
        $contenido = consultas::getCumpleanios($_POST['pl'], $_POST['lm'], true);
        echo $contenido;
    } 
?>