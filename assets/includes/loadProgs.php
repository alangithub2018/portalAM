<?php include_once "funciones.php"; $fini = $_POST['in']; $ending = $_POST['fn'];  echo consultas::getServices('period', $fini, $ending); ?>