<?php

session_start();

require_once '../vendor/autoload.php';
require_once '../src/error_handler.php';

use App\DAO\UsuarioDAO;
use App\BD\BD;


define('ERROR_MESSAGE', "Credenciales Incorrectas");

$bd = BD::getConexion();

$usuarioDAO = new UsuarioDAO($bd);

if (filter_has_var(INPUT_GET, 'logout')) {
    session_unset();
    session_destroy();
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
    );
} elseif (isset($_SESSION['usuario'])) {
    header('Location:./listado.php');
} elseif (filter_has_var(INPUT_POST, 'login')) {
    $nombre = trim(filter_input(INPUT_POST, 'usuario'));
    $nombreErr = strlen($nombre) === 0;
    $pwd = trim(filter_input(INPUT_POST, 'pass'));
    $pwdErr = strlen($pwd) === 0;
    $errorLoginForm = $nombreErr || $pwdErr;
    if (!$errorLoginForm) {
        $usuario = $usuarioDAO->recuperaPorCredencial($nombre, $pwd);
        $errorCredenciales = is_null($usuario);
        if (!$errorCredenciales) {
            $_SESSION['usuario'] = $nombre;
            header('Location:listado.php');
        }
    }
} elseif (isset($_POST['invitado'])) {
    header('Location:listado.php');
}
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Bootstrap CDN -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" 
              integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
        <title>Login</title>
    </head>
    <body style="background:silver;">
        <div class="container mt-5">
            <div class="d-flex justify-content-center mt-5 h-100">
                <div class="mt-5 card" style="width: 20rem;">
                    <div class="card-header">
                        <h3>Login</h3>
                    </div>
                    <div class="card-body">
                        <form name='login' class="p-3" method='POST' action='<?= $_SERVER['PHP_SELF']; ?>'>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" class="<?= 'form-control ' . ((isset($errorLoginForm) && (empty($nombreUsuario))) ? 'is-invalid' : ''); ?>" placeholder="usuario" name='usuario' >
                                <div class="invalid-feedback">
                                    <p>Introduce el usuario</p>
                                </div>
                            </div>
                            <div class="input-group mb-3">                 
                                <span class="input-group-text"><i class="bi bi-key"></i></span>
                                <input type="password" class="<?= 'form-control ' . ((isset($errorLoginForm) && (empty($pass))) ? 'is-invalid' : ''); ?>" placeholder="contraseña" name='pass' >
                                <div class="invalid-feedback">
                                    <p>Introduce el password</p>
                                </div>
                            </div>
                            <?php if (isset($errorCredenciales) && $errorCredenciales): ?>
                                <div class="alert alert-danger" role="alert">
                                    <h1><?= ERROR_MESSAGE ?></h1>
                                </div>
                            <?php endif ?>
                            <input type="submit" value="Acceso como Invitado" class="btn btn-info" name='invitado'>
                            <input type="submit" value="Login" class="btn float-end btn-success" name='login'>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>