<?php include_once "funciones.php";
    if (isset($_POST['ns'],$_POST['nm'])) {
        $numS = $_POST['ns']; $nomC = $_POST['nm'];
        $jsonString = tools::encrypt_decrypt('decrypt', file_get_contents(__DIR__.'/json/cumpleanios.json'));
        $data = json_decode(base64_decode($jsonString), true);
        $data[$numS]['nombre'] = $nomC;
        
        $fp = fopen(__DIR__.'/json/cumpleanios.json', 'w+');
        $myDtU = json_encode($data); $myDtU = base64_encode($myDtU);
        fwrite($fp, tools::encrypt_decrypt('encrypt', $myDtU));
        fclose($fp);
        echo "1";
    }
?>