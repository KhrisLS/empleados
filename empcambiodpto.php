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
    <title>Ej3</title>
</head>
<body>
    <p>CAMBIO DE DEPARTAMENTO</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
        <label for="dni">Empleado </label>
        <select name="dni" id="dni">
            <?php
                listaEmpleados();
            ?>
        </select>
        <br><br>
        <label for="cod_dpto">Nuevo Departamento </label>
        <select name="cod_dpto" id="cod_dpto">
            <?php
                listaDptos();
            ?>
        </select>
        <br><br>
        <button type="submit">Cambiar</button>
    </form>

    <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $dni = test_input($_POST['dni']);
            $codDpto = test_input($_POST['cod_dpto']);

            if (empty($dni) || empty($codDpto)) {
                trigger_error("Se deben seleccionar ambos campos.", E_USER_ERROR);
            }
            
            cambioDpto($dni, $codDpto);
        }
    ?>

</body>
</html>