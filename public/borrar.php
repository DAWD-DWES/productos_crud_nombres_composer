<?php

require_once '../vendor/autoload.php';
require_once '../src/error_handler.php';

use App\DAO\ProductoDAO;
use App\BD\BD;


session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location:index.php');
    die;
}
if (!filter_has_var(INPUT_POST, 'borrar')) {
    header('Location:listado.php');
    die;
}


$bd = BD::getConexion();

$id = filter_input(INPUT_POST, 'id', FILTER_UNSAFE_RAW);

$productoDao = new ProductoDAO($bd);

try {
    $productoBorrado = $productoDao->elimina($id);
} catch (PDOException $ex) {
    error_log("Error al borrar el producto" . $ex->getMessage());
    $productoBorrado = false;
}

$usuario = ($_SESSION['usuario']) ?? false;
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <!-- css para usar Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" 
              integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
        <title>Borrar Producto</title>
    </head>
    <body class="bg-info">
        <div class="d-flex justify-content-end m-3 align-items-baseline">
            <i class="bi-person-fill fs-2 me-3"></i>
            <p class="me-5 bg-transparent text-white border border-white rounded px-5"><?= $usuario ?> </p>
            <a href='index.php?logout' class='btn btn-danger me-2'>Salir</a>
        </div>
        <h3 class="text-center mt-2 fw-bold">Borrar Producto</h3>
        <div class="container mt-3">
            <h3 class="text-center mt-2 fw-bold">
                <?= ($productoBorrado) ? "Producto borrado con éxito" : "Ha habido un problema para borrar el producto" ?>
            </h3>
            <a href="index.php" class="btn btn-warning">Volver</a>
        </div>
    </body>
</html>