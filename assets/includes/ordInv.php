<?php include_once "funciones.php"; $columna = $_POST['cl']; $orden = $_POST['od']; consultas::getInventario($columna,$orden, false, ''); ?>