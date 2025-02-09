<?php
session_start();

require_once '../vendor/autoload.php';
require_once '../src/error_handler.php';

use App\DAO\ProductoDAO;
use App\BD\BD;

if (!filter_has_var(INPUT_GET, 'pet_detalle')) {
    header('Location:listado.php');
    die;
}

$bd = BD::getConexion();

$productoDAO = new ProductoDAO($bd);

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

try {
    $producto = $productoDAO->recuperaPorId($id);
} catch (PDOException $ex) {
    die("Error al recuperar el producto " . $ex->getMessage());
    $productoEncontrado = false;
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
        <title>Detalle</title>
    </head>
    <body class="bg-info">
        <div class="d-flex justify-content-end m-3 align-items-baseline">
            <i class="bi-person-fill fs-2 me-3"></i>
            <p class="me-5 bg-transparent text-white border border-white rounded px-5"><?= $usuario ?: 'invitado' ?></p>
            <?php if ($usuario): ?>
                <a href='index.php?logout' class='btn btn-danger mr-2'>Salir</a>
            <?php else: ?>
                <a href='index.php' class='btn btn-primary mr-2'>Login</a>
            <?php endif ?>
        </div>
        <h3 class="text-center mt-2 fw-bold">Detalle Producto</h3>
        <div class="container mt-3">
            <?php if (!($productoEncontrado ?? true)): ?>
                <h3 class="text-center mt-2 fw-bold">Producto no encontrado</h3>
            <?php else: ?>
                <div class="card text-white bg-info mt-5 mx-auto">
                    <div class="card-header text-center text-weight-bold">
                        <?= $producto->getNombre() ?>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title text-center"><?= "Codigo: {$producto->getId()}" ?></h5>
                        <p class="card-text"><b>Nombre: </b><?= htmlspecialchars($producto->getNombre(), ENT_NOQUOTES, 'UTF-8') ?></p>
                        <p class="card-text"><b>Nombre Corto: </b> <?= htmlspecialchars($producto->getNombreCorto(), ENT_NOQUOTES, 'UTF-8') ?></p>
                        <p class="card-text"><b>Codigo Familia: </b><?= htmlspecialchars($producto->getFamilia(), ENT_NOQUOTES, 'UTF-8') ?></p>
                        <p class="card-text"><b>PVP (€): </b><?= htmlspecialchars($producto->getPvp(), ENT_NOQUOTES, 'UTF-8') ?></p>
                        <p class="card-text"><b>Descripción: </b><?= htmlspecialchars($producto->getDescripcion(), ENT_NOQUOTES, 'UTF-8') ?></p>
                    </div>
                </div>
            <?php endif ?>
            <div class="container mt-5 text-center">
                <a href="listado.php" class="btn btn-warning">Volver</a>
            </div>
        </div>
    </body>
</html>
