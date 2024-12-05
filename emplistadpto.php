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
    <title>Ej4</title>
</head>
<body>
    <p>EMPLEADOS DE UN DEPARTAMENTO</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
        <label for="cod_dpto">Departamento </label>
        <select name="cod_dpto" id="cod_dpto">
            <?php
                listaDptos();
            ?>
        </select>
        <br><br>
        <button type="submit">Ver empleados</button>
    </form>

    <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $codDpto = test_input($_POST['cod_dpto']);

            if(empty($codDpto)) {
                trigger_error("No se ha seleccionado un departamento.", E_USER_ERROR);
            }
            
            listaEmpleadosPorDpto($codDpto);
        }
    ?>

</body>
</html>