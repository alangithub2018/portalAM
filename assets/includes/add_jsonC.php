<?php include_once "funciones.php";
    if (isset($_POST['di'],$_POST['de'],$_POST['no'])) {
        $dia = $_POST['di']; $dep = $_POST['de']; $nameC = $_POST['no'];
        $jsonString = tools::encrypt_decrypt('decrypt', file_get_contents(__DIR__.'/json/cumpleanios.json'));
        $data = json_decode(base64_decode($jsonString), true);
        $r = 1;
        while(array_key_exists($r."f", $data)){
            $r++;
        }
        $myKey = $r."f";
        
        $data[$myKey] = array('nombre' => $nameC, 'departamento' => $dep, 'dia' => $dia);
                
        $fp = fopen(__DIR__.'/json/cumpleanios.json', 'w+');
        $myDtU = json_encode($data); $myDtU = base64_encode($myDtU);
        fwrite($fp, tools::encrypt_decrypt('encrypt', $myDtU));
        fclose($fp);
        foreach ($data as $clave => $fila) {
            $nomb[$clave] = $fila['nombre'];
            $dept[$clave] = $fila['departamento'];
            $dya[$clave] = $fila['dia'];
        }
        array_multisort($dya, SORT_ASC, $nomb, SORT_ASC, $data); $x = 0;
        foreach ($data as $i => $values) {
           $contenidoN .= "<tr>";
           foreach ($values as $key => $value) {
               if($key=='nombre'){
                   $contenidoN .= '<td><div class="input-group"><input id="'.$i.'" disabled="disabled" type=text class="form-control IedtCum" spellcheck="true" value="'. $value . '" /><span class="input-group-btn"><button id="'.$i.'_'.$x.'" class="btn btn-default edtCum"><i class="fa fa-pencil"></i></button></span></div></td>';
               }else{
                    $contenidoN .= "<td>" . $value . "</td>";
               }
               $x++;
           }
            $contenidoN .= "</tr>";
        }
        echo trim($contenidoN);
    }
?>