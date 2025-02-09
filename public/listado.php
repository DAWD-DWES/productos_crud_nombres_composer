<?php
session_start();

require_once '../vendor/autoload.php';
require_once '../src/error_handler.php';

use App\DAO\ProductoDAO;
use App\BD\BD;

$bd = BD::getConexion();

$productoDAO = new ProductoDAO($bd);

try {
    $productos = $productoDAO->recuperaTodo();
} catch (PDOException $ex) {
    error_log("Error al recuperar información de productos " . $ex->getMessage());
    $productos = [];
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
        <title>CRUD Productos</title>
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
        <h3 class="text-center mt-2 fw-bold">Gestión de Productos</h3>
        <div class="container mt-3">
            <a href="crear.php?pet_crear"  class="btn btn-success mt-2 mb-2 <?= (!$usuario ? 'disabled' : '') ?>">Crear</a>
            <table class="table table-striped table-dark">
                <thead>
                    <tr class="text-center">
                        <th scope="col">Detalle</th>
                        <th scope="col">Codigo</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos as $producto): ?>
                        <tr class='text-center'>
                            <th scope="row">
                                <a class="btn btn-warning me-2" href="detalle.php?pet_detalle&id=<?= $producto->getId() ?>">Detalle</a>
                            </th>
                            <td><?= $producto->getId() ?></td>
                            <td><?= htmlspecialchars($producto->getNombre(), ENT_NOQUOTES, 'UTF-8') ?></td>
                            <td>
                                <a class="btn btn-warning me-2 <?= (!$usuario ? 'disabled' : '') ?>"
                                   href="modificar.php?pet_modificar&id=<?= $producto->getId() ?>">Actualizar</a>
                                   <?php if ($usuario): ?>
                                    <form action="borrar.php" method='POST' class="d-inline">
                                        <input type="hidden" name="id" value="<?= $producto->getId() ?>"> <!-- mandamos el código del producto a borrar -->
                                        <input type="submit" onclick="return confirm('¿Borrar Producto?')" class="btn btn-danger" value="Borrar" name="borrar">
                                    </form>
                                <?php endif ?>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </body>
</html>

