<?php include_once "funciones.php";$employ=$_POST['epl'];$company=$_POST['cmp'];$idreturn=consultas::getdatauser($employ,$company,FALSE,'n');if(!empty($idreturn)){$idresponse=explode(",",$idreturn);actpersvac($idresponse[0]);$datartn=printmyvacs($idresponse[0],$_SESSION['periodo'],TRUE); echo $datartn."*".$idresponse[0]."*".$idresponse[1]."*".$idresponse[2]."*".$idresponse[3];}?>