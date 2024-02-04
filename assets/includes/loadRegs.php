<?php include_once "funciones.php"; 
$usrFil = $_POST['uf']; $fein = $_POST['fi']; $fefi = $_POST['ff']; $lflts = $_POST['tf']; $lpage = $_POST['pn'];
$totl_regs = trim(str_replace("|","",consultas::getSpt('', false, false, 'tblRegsSpt',$fein,$fefi,$usrFil,true,false,'','')));
$totpgregs = ceil((intval($totl_regs))/$lflts);
if($totpgregs<$lpage){
    echo consultas::getSpt('', false, false, 'tblRegsSpt',$fein,$fefi,$usrFil,false,true,'1',$lflts).'|'.$totl_regs;
}else{
    echo consultas::getSpt('', false, false, 'tblRegsSpt',$fein,$fefi,$usrFil,false,true,$lpage,$lflts).'|'.$totl_regs;
} ?>