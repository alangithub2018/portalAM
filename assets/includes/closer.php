<?php session_start(); include_once "funciones.php";
    unset($_SESSION["ultimoAcceso"]);
    unset($_SESSION);
    session_unset();
    session_regenerate_id();
    session_destroy(); 
 
    // destruir informacion de session en el cliente 
    $session_cookie_params = session_get_cookie_params(); 
    setcookie(session_name(), '', time() - 24 * 3600, $session_cookie_params['path'], $session_cookie_params['domain'], $session_cookie_params['secure'], $session_cookie_params['httponly']);
 
    // Limpiar el array $_SESSION
    $_SESSION = array();
    global $conexion; mysqli_close($conexion);
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    clearstatcache();
    echo "<script languaje=\"javascript\">function nobackbutton(){ window.location.hash=\"no-back-button\"; window.location.hash=\"Again-No-back-button\"; window.onhashchange=function(){window.location.hash=\"no-back-button\";} } nobackbutton(); parent.top.location.replace('/portal/#Again-No-back-button');</script>";
?>