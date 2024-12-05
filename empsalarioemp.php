<?php
include ("conexion.php");
include ("errores.php");
include ("funciones.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ej6</title>
</head>
<body>
    <p>MODIFICAR SALARIO DE EMPLEADO</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
        <label for="dni">Empleado </label>
        <select name="dni" id="dni">
            <?php
                listaEmpleados();
            ?>
        </select>
        <br><br>
        <label for="porcentaje">Porcentaje a cambiar </label>
        <input type="text" name="porcentaje" id="porcentaje">
        <br><br>
        <button type="submit">Modificar</button>
        <button type="reset">Borrar</button>
    </form>

    <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $dni = test_input($_POST['dni']);
            $porcentaje = test_input($_POST['porcentaje']);

            if(empty($dni)){
                trigger_error("No se ha seleccionado un empleado.", E_USER_ERROR);
            }
            
            if(empty($porcentaje)){
                trigger_error("No se ha rellenado el campo porcentaje.", E_USER_ERROR);
            }
            
            actualizarSalario($dni, $porcentaje);
        }
    ?>

</body>
</html>