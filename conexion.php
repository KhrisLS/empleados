<?php
/*SELECTs - mysql PDO*/
function conexionBD($sql, $valores = array()) {
    $servername = "localhost";
    $username = "root";
    $password = "rootroot";
    $dbname = "empleadosNn";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare($sql);

        //SI LA CONSULTA ES DE TIPO SELECT, DEVOLVER EL RESULTADO
        if(stripos(trim($sql), 'SELECT') === 0 ){
            $stmt->execute($valores);
            return $stmt;
        }

        // Solo iniciamos transacción para INSERT, UPDATE, DELETE
        $conn->beginTransaction();

        // Ejecutamos la consulta
        $stmt->execute($valores);
        
        // Si llegamos aquí sin errores, confirmamos la transacción
        $conn->commit();

        //SI LA CONSULTA ES DE TIPO INSERT, UPDATE, DELETE DEVOLVER TRUE
        return true;
    }
    catch(PDOException $e) {
        // Solo hacemos rollback si hay una transacción activa
        if ($conn && $conn->inTransaction()) {
            $conn->rollBack();
        }
        echo "Error: " . $e->getMessage();
        return false;
    }
    finally {
        // Cerramos la conexión
        $conn = null;
    }
}

?>