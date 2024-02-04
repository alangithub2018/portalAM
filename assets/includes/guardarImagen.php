<?php include_once "funciones.php"; 
    function optimizar_imagen($origen, $destino, $calidad, $xmax, $ymax) {
      $info = getimagesize($origen);

      if ($info['mime'] == 'image/jpeg'){
	$imagen = imagecreatefromjpeg($origen);
      }else if ($info['mime'] == 'image/gif'){
	$imagen = imagecreatefromgif($origen);    
      }else if ($info['mime'] == 'image/png'){
	$imagen = imagecreatefrompng($origen);
      }
      $x = imagesx($imagen); 
      $y = imagesy($imagen);
      
      $img2 = imagecreatetruecolor($xmax, $ymax);
      imagecopyresized($img2, $imagen, 0, 0, 0, 0, floor($xmax), floor($ymax), $x, $y);
      imagejpeg($img2, $destino, $calidad);
      
      return $destino;
    }
    if(isset($_FILES)){
        if(file_exists("../img/cumpleanios/".str_replace(" ", "_", $_FILES['imagen']['name'])) and file_exists("../img/cumpleanios/previa_". str_replace(" ", "_", $_FILES['imagen']['name']))){
            unlink("../img/cumpleanios/".str_replace(" ", "_", $_FILES['imagen']['name']));
            unlink("../img/cumpleanios/previa_".str_replace(" ", "_", $_FILES['imagen']['name']));
        }else{
            $imagen = optimizar_imagen($_FILES['imagen']['tmp_name'], "../img/cumpleanios/". str_replace(" ", "_", $_FILES['imagen']['name']), 88, 814,1054);
            $imagen2 = optimizar_imagen($_FILES['imagen']['tmp_name'], "../img/cumpleanios/previa_". str_replace(" ", "_", $_FILES['imagen']['name']), 100, 207,262);
            echo "ok";
        }
    }
?>