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
    <title>Ej2</title>
</head>
<body>
    <p>DAR DE ALTA EMPLEADO</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
        <label for="dni">DNI </label>
        <input type="text" name="dni" id="dni"><br><br>
        <label for="nombre">Nombre </label>
        <input type="text" name="nombre" id="nombre"><br><br>
        <label for="salario">Salario </label>
        <input type="text" name="salario" id="salario"><br><br>
        <label for="fechaNacimiento">Fecha de Nacimiento </label>
        <input type="date" name="fechaNacimiento" id="fechaNacimiento"><br><br>
        <label for="cod_dpto">Departamento </label>
        <select name="cod_dpto" id="cod_dpto">
            <?php
                listaDptos();
            ?>
        </select>
        <br><br>
        <button type="submit">Crear</button>
        <button type="reset">Borrar</button>
    </form>

    <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $dni = test_input($_POST['dni']);
            $nombre = test_input($_POST['nombre']);
            $salario = test_input($_POST['salario']);
            $fechaNacimiento = test_input($_POST['fechaNacimiento']);
            $codDpto = test_input($_POST['cod_dpto']);

            if (empty($dni) || empty($nombre) || empty($salario) || empty($fechaNacimiento)) {
                trigger_error("Se deben rellenar todos los campos.", E_USER_ERROR);
            }
            
            altaEmpleado($dni, $nombre, $salario, $fechaNacimiento, $codDpto);
        }
    ?>

</body>
</html>