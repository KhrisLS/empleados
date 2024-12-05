<?php
set_error_handler("errores");

function errores($errno, $errstr){
    //Control de errores
    echo "<p><strong>ERROR:</strong> $errstr</p><br>";
    die();
}

?>

