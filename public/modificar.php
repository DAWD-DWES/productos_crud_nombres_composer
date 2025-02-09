<?php
session_start();

require_once '../vendor/autoload.php';
require_once '../src/error_handler.php';

use App\Modelo\Producto;
use App\DAO\ProductoDAO;
use App\DAO\FamiliaDAO;

use App\BD\BD;

if (!isset($_SESSION['usuario'])) {
    header('Location:index.php');
    die;
}

if (!(filter_has_var(INPUT_GET, 'pet_modificar') || filter_has_var(INPUT_POST, 'modificar'))) {
    header('Location:listado.php');
}

define('NOMBRE_INVALIDO', '**Nombre inválido');
define('NOMBRE_CORTO_INVALIDO', '**Nombre corto inválido');
define('NOMBRE_CORTO_DUPLICADO', '**Nombre corto duplicado');
define('PVP_INVALIDO', '**PVP inválido');
define('DESCRIPCION_INVALIDO', '**Descripción inválida');

define("REGEXP_NOMBRE", "/^[\w\s\-_áéíóúñ.,;:!?'(){}[\]+]{2,100}$/");
define("REGEXP_NOMBRE_CORTO", "/^[\w\s\-_áéíóúñ.,;:!?'(){}[\]]{2,15}$/");
define("REGEXP_DESCRIPCION", "/^[\s\S]*$/");

$bd = BD::getConexion();
$productoDAO = new ProductoDAO($bd);
$familiaDAO = new FamiliaDAO($bd);

$usuario = ($_SESSION['usuario']) ?? false;

if (filter_has_var(INPUT_POST, 'modificar')) {
//recogemos los datos del formulario
    $nombre = ucwords(trim(filter_input(INPUT_POST, 'nombre', FILTER_UNSAFE_RAW)));
    $nombreErr = filter_var($nombre, FILTER_VALIDATE_REGEXP,
                    ['options' => ['regexp' => REGEXP_NOMBRE]]) === false;
    $nombreCorto = strtoupper(trim(filter_input(INPUT_POST, 'nombre_corto', FILTER_UNSAFE_RAW)));
    $nombreCortoErr = filter_var($nombreCorto, FILTER_VALIDATE_REGEXP,
                    ['options' => ['regexp' => REGEXP_NOMBRE_CORTO]]) === false;
    $pvp = filter_input(INPUT_POST, 'pvp', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $pvpErr = filter_var($pvp, FILTER_VALIDATE_FLOAT, ["options" => ["min_range" => 0]]) === false;
    $descripcion = trim(filter_input(INPUT_POST, 'descripcion', FILTER_UNSAFE_RAW));
    $descripcionErr = filter_var($descripcion, FILTER_VALIDATE_REGEXP,
                    ['options' => ['regexp' => REGEXP_DESCRIPCION]]) === false;
    $familiaCodigo = filter_input(INPUT_POST, 'familia_codigo', FILTER_UNSAFE_RAW);
    $id = filter_input(INPUT_POST, 'id', FILTER_UNSAFE_RAW);
    $error = array_sum(compact(["nombreErr", "nombreCortoErr", "pvpErr", "descripcionErr"])) > 0;
    if (!$error) {
        $producto = new Producto($nombre, $nombreCorto, $descripcion, $pvp, $familiaCodigo);
        $producto->setId($id);
        try {
            $productoDAO->modifica($producto);
            $productoModificado = true;
        } catch (PDOException $ex) {
            error_log("Error al modificar el producto " . $ex->getMessage());
            if ($ex->getcode() == 23000) {
                $errorDuplicadoNombreCorto = true;
            } else {
                $productoModificado = false;
            }
        }
    }
} else {
    $id = filter_input(INPUT_GET, 'id');
    try {
        $producto = $productoDAO->recuperaPorId($id);
    } catch (PDOException $ex) {
        error_log("Error al recuperar información de producto " . $ex->getMessage());
        $productoEncontrado = false;
    }
}

if (!($productoModificado ?? false)) {
    try {
        $familias = $familiaDAO->recuperaTodo();
    } catch (PDOException $ex) {
        error_log("Error al recuperar información de familias " . $ex->getMessage());
        $familias = [];
    }
}
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
        <title>Modificar Producto</title>
    </head>
    <body class="bg-info">
        <div class="d-flex justify-content-end m-3 align-items-baseline">
            <i class="bi-person-fill fs-2 me-3"></i>
            <p class="me-5 bg-transparent text-white border border-white rounded px-5"><?= $usuario ?> </p>
            <a href='index.php?logout' class='btn btn-danger me-2'>Salir</a>
        </div>
        <h3 class="text-center mt-2 fw-bold">Modificar Producto</h3>
        <div class="container mt-3">
            <?php if ($productoModificado ?? false): ?>
                <h3 class="text-center mt-2 fw-bold">Producto modificado con éxito</h3>
                <a href="index.php" class="btn btn-warning">Volver</a>
            <?php elseif (!isset($errorDuplicadoNombreCorto) && !($productoModificado ?? true)): ?>
                <h3 class="text-center mt-2 fw-bold">Ha habido un problema para modificar el producto</h3>
            <?php else: ?>
                <form method="POST" action="<?= "{$_SERVER['PHP_SELF']}" ?>">
                    <div class="row g-3">
                        <div class="col-md-6 align-items-center mb-3">
                            <input type="hidden" name="id" value="<?= $id ?>" >
                            <label for="nombre">Nombre: </label>
                            <input type="text" class="form-control <?= (isset($nombreErr) ? (($nombreErr) ? "is-invalid" : "is-valid") : "") ?>" 
                                   id="nombre" placeholder="Nombre" name="nombre"
                                   value="<?= htmlspecialchars($nombre ?? $producto->getNombre() ?? '', ENT_NOQUOTES, 'UTF-8') ?>" >
                            <div class="invalid-feedback">
                                <p><?= NOMBRE_INVALIDO ?></p>
                            </div>
                        </div>
                        <div class="col-md-6 align-items-center mb-3">
                            <label for="nombre_corto">Nombre Corto</label>
                            <input type="text" class="form-control <?= (isset($nombreCortoErr) ? (($nombreCortoErr || ($errorDuplicadoNombreCorto ?? false)) ? "is-invalid" : "is-valid") : "") ?>"
                                   id="nombre_corto" placeholder="Nombre corto" name="nombre_corto"
                                   value = "<?= htmlspecialchars($nombreCorto ?? $producto->getNombreCorto() ?? '', ENT_NOQUOTES, 'UTF-8') ?>" >
                            <div class="invalid-feedback">
                                <p><?=
                                    match (true) {
                                        $nombreCortoErr ?? false => NOMBRE_CORTO_INVALIDO,
                                        $errorDuplicadoNombreCorto ?? false => NOMBRE_CORTO_DUPLICADO,
                                        default => ''
                                    }
                                    ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6 align-items-center mb-3">
                            <label for="pvp">Precio (€)</label>
                            <input type="text" class="form-control <?= (isset($pvpErr) ? (($pvpErr) ? "is-invalid" : "is-valid") : "") ?>"
                                   id="pvp" placeholder="PVP" name="pvp"
                                   value="<?= htmlspecialchars($pvp ?? $producto->getPvp() ?? '', ENT_NOQUOTES, 'UTF-8') ?>" >
                            <div class="invalid-feedback">
                                <p><?= PVP_INVALIDO ?></p>
                            </div>
                        </div>
                        <div class="col-md-6 align-items-center mb-3">
                            <label for="familia">Familia</label>
                            <select class="form-control" name="familia_codigo">
                                <?php foreach ($familias as $familia): ?>
                                    <option value='<?= $familia->getCod() ?>' <?= ($familia->getCod() == (isset($producto) ? $producto->getFamilia() : $familiaCodigo)) ? "selected" : "" ?>>
                                        <?= $familia->getNombre() ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-9 align-items-center mb-3">
                            <label for="descripcion">Descripción</label>
                            <textarea class="form-control <?= (isset($descripcionErr) ? (($descripcionErr) ? "is-invalid" : "is-valid") : "") ?>"
                                      id="descripcion" name="descripcion" placeholder="Descripción" rows="12" >
                                          <?= htmlspecialchars($descripcion ?? $producto->getDescripcion() ?? '', ENT_NOQUOTES, 'UTF-8') ?>
                            </textarea>
                            <div class="invalid-feedback">
                                <p><?= DESCRIPCION_INVALIDO ?></p>
                            </div>
                        </div>
                    </div>
                    <input type="submit" class="btn btn-primary m-3" name="modificar" value="Modificar">
                    <input type="submit" class="btn btn-outline btn-warning" formaction="listado.php" value="Volver" >
                </form>
            <?php endif ?>
        </div>
    </body>
</html>