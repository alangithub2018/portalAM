<?php include_once "funciones.php"; $fz = $_POST['ix']; $de = $_POST['dx']; $fe = $_POST['fx']; $ae = $_POST['ax']; modificaciones::updServ($fz, utf8_decode($de), $fe, utf8_decode($ae)); ?>