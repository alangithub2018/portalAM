<?php 
    include_once "funciones.php";
    $id_myentrp = $_POST['vlep'];//codigo empresa
    $str_flsl = $_POST['idsl'];//estatus de solicitud
    $str_shslvc = $_POST['shdt'];//busqueda like
    $str_prdsld = $_POST['shprd'];//periodos a buscar
    $npage = $_POST['npg'];
    $tfils = $_POST['tfl'];
    $rowstot = trim(str_replace("*","",printsolsvacrh($str_flsl, $str_prdsld, $str_shslvc, $id_myentrp, TRUE, FALSE,'','')));
    $totpages = ceil((intval($rowstot))/$tfils);
    if($totpages<$npage){
        echo printsolsvacrh($str_flsl, $str_prdsld, $str_shslvc, $id_myentrp, FALSE, TRUE, '1', $tfils).'*'.$rowstot;
    }else{
        echo printsolsvacrh($str_flsl, $str_prdsld, $str_shslvc, $id_myentrp, FALSE, TRUE, $npage, $tfils).'*'.$rowstot;   
    }?>