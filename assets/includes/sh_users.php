<?php include_once "funciones.php"; $restts = $_POST['est']; $buajx = $_POST['shurs']; $ord_usrs = $_POST['org']; $entr_usrs = $_POST['entr']; echo consultas::prntusers($buajx, $ord_usrs, $entr_usrs, $restts, '0'); ?>