<?php include_once "funciones.php"; $user = $_POST['us']; $dpt = $_POST['dp']; $are = $_POST['ar']; $cat = $_POST['ct']; $pro = $_POST['pr']; $fesl = $_POST['ds']; $rsv = $_POST['rs']; $fval = $_POST['dv']; $pver = $_POST['vf']; $solap = $_POST['sa']; $trn = $_POST['ty']; $enUpd = $_POST['ca']; $lstus = $_POST['st']; altas::sveSp($trn,$enUpd,$user,$dpt,$are,$cat,$pro,$fesl,$rsv,$fval,$pver,$solap,$lstus); ?>