<?php
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function buscarUltimoDpto(){
    $sql = "SELECT cod_dpto FROM dpto2 ORDER BY cod_dpto DESC LIMIT 1";
    $resultado = conexionBD($sql);

    if ($resultado->rowCount() == 0) {
        return 'D000';   
    }

    $linea = $resultado->fetch(PDO::FETCH_ASSOC);
    $ultimoDpto = $linea['cod_dpto'];

    return $ultimoDpto;
}

function generarCodDpto($ultimoDpto){
    
    $codDpto = intval(substr($ultimoDpto, 1)) + 1;
    $nuevoDpto = 'D'.str_pad($codDpto, 3, '0', STR_PAD_LEFT);

    return $nuevoDpto;
}


function crearDpto($dpto, $nombre){

    $sql = "INSERT INTO dpto2 (cod_dpto, nombre) VALUES (:cod_dpto, :nombre)";
    $valores = [':cod_dpto' => $dpto, ':nombre' => $nombre];
    
    $valido = conexionBD($sql, $valores);

    if ($valido) {
        echo "<p>Departamento creado correctamente</p>";
    }
}


function listaDptos(){    
    $sql = "SELECT cod_dpto, nombre FROM dpto2";
    $result = conexionBD($sql);
    
    $datos = $result->fetchAll(PDO::FETCH_ASSOC);
    
    // Opción por defecto
    echo "<option value=''>-- NO ASIGNADO --</option>";

    // Usamos los datos obtenidos
    foreach ($datos as $row) {
        echo "<option value='" . $row["cod_dpto"] . "'>" . 
             $row["cod_dpto"] . " - " . $row["nombre"] . "</option>";
    }
}

function altaEmpleado($dni, $nombre, $salario, $fechaNacimiento, $codDpto){
    $sql = "INSERT INTO emple2 (dni, nombre, salario, fecha_nac) VALUES (:dni, :nombre, :salario, :fecha_nac)";
    $valores = [':dni' => $dni, ':nombre' => $nombre, ':salario' => $salario, ':fecha_nac' => $fechaNacimiento];
    
    $valido = conexionBD($sql, $valores);

    if ($valido) {
        echo "<p>Empleado creado correctamente</p>";

        // Si el departamento es diferente de NO ASIGNADO, añadimos el empleado al departamento
        if ($codDpto!= '') {
            $sql = "INSERT INTO emple_dpto (dni, cod_dpto, fecha_ini, fecha_fin) VALUES (:dni, :cod_dpto, CURRENT_DATE, NULL)";
            $valores = [':dni' => $dni, ':cod_dpto' => $codDpto];
            
            $valido = conexionBD($sql, $valores);

            if ($valido) {
                echo "<p>Empleado asignado correctamente al departamento</p>";
            }
        }
        else {
            echo "<p>No pertenece a ningun departamento</p>";
        }
    }
}

function listaEmpleados(){
    $sql = "SELECT dni, nombre FROM emple2";
    $result = conexionBD($sql);
    
    $datos = $result->fetchAll(PDO::FETCH_ASSOC);
    
    // Opción por defecto
    echo "<option value=''>-- SELECCIONAR --</option>";

    // Usamos los datos obtenidos
    foreach ($datos as $row) {
        echo "<option value='" . $row["dni"] . "'>" . 
             $row["dni"] . " - " . $row["nombre"] . "</option>";
    }
}

function cambioDpto($dni, $codDpto){
    $sql = "UPDATE emple_dpto SET fecha_fin = NOW() WHERE dni = :dni AND fecha_fin IS NULL";
    $valores = [':dni' => $dni];
    
    $valido = conexionBD($sql, $valores);

    if ($valido) {
        $sql = "INSERT INTO emple_dpto (dni, cod_dpto, fecha_ini, fecha_fin) VALUES (:dni, :cod_dpto, NOW(), NULL)";
        $valores = [':dni' => $dni, ':cod_dpto' => $codDpto];
        
        $valido = conexionBD($sql, $valores);

        if ($valido) {
            echo "<p>Empleado asignado correctamente al nuevo departamento</p>";
        }
    }
}

function listaEmpleadosPorDpto($codDpto){
    $sql = "SELECT emple2.dni, emple2.nombre FROM emple2, emple_dpto WHERE emple2.dni = emple_dpto.dni AND emple_dpto.cod_dpto = :cod_dpto AND emple_dpto.fecha_fin IS NULL";
    $valores = [':cod_dpto' => $codDpto];
    
    $result = conexionBD($sql, $valores);
    
    $datos = $result->fetchAll(PDO::FETCH_ASSOC);
    
    // Controlamos si hay empleados en el departamento
    if ($result->rowCount() == 0) {
        echo "<p>No hay empleados en este departamento</p>";
    }else{
        echo "<p>Empleados del departamento ". $codDpto. "</p>";
        // En caso de que haya empleados, los mostramos
        foreach ($datos as $row) {
            echo "<p>" . $row["dni"] . " - " . $row["nombre"] . "</p>";
        }
    }
}

function historicoEmpleadosPorDpto($codDpto){
    $sql = "SELECT DISTINCT emple2.dni, emple2.nombre FROM emple2, emple_dpto WHERE emple2.dni = emple_dpto.dni AND emple_dpto.cod_dpto = :cod_dpto AND emple_dpto.fecha_fin IS NOT NULL";
    $valores = [':cod_dpto' => $codDpto];

    $result = conexionBD($sql, $valores);
    
    $datos = $result->fetchAll(PDO::FETCH_ASSOC);
    
    // Controlamos si hubieron empleados en el departamento
    if ($result->rowCount() == 0) {
        echo "<p>No hay un historico de empleados en este departamento</p>";
    }else{
        echo "<p>Historico de empleados del departamento ". $codDpto. "</p>";
        // En caso de que haya empleados, los mostramos
        foreach ($datos as $row) {
            echo "<p>" . $row["dni"] . " - " . $row["nombre"] . "</p>";
        }
    }
}

function actualizarSalario($dni, $porcentaje){
    // Obtener el salario actual del empleado
    $sql = "SELECT salario FROM emple2 WHERE dni = :dni";
    $valores = [':dni' => $dni];
    $result = conexionBD($sql, $valores);
    
    if ($result->rowCount() > 0) {
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $salarioActual = $row['salario'];

        // Calcular el nuevo salario
        $nuevoSalario = $salarioActual * (1 + $porcentaje / 100);

        // Actualizar el salario en la base de datos
        $sqlUpdate = "UPDATE emple2 SET salario = :nuevoSalario WHERE dni = :dni";
        $valoresUpdate = [':nuevoSalario' => $nuevoSalario, ':dni' => $dni];
        
        $valido = conexionBD($sqlUpdate, $valoresUpdate);
        
        if ($valido) {
            echo "<p>El salario ha sido actualizado correctamente.</p>";
        }
    } 
}

?>