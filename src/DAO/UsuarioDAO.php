<?php

namespace App\DAO;

use App\Modelo\Usuario;
use \PDO;

class UsuarioDAO {

    private PDO $bd;

    function __construct(PDO $bd) {
        $this->bd = $bd;
    }

    function crea(Usuario $usuario): string {
        
    }

    function modifica(Usuario$usuario): void {
        
    }

    function elimina(string $nombre): void {
        
    }

    function recuperaPorCredencial(string $nombre, string $pwd): ?Usuario {
        $pwdHashed = hash('sha256', $pwd);
        $sql = 'select * from usuarios where usuario=:nombre and pass=:pwdHashed';
        $sth = $this->bd->prepare($sql);
        $sth->execute([":nombre" => $nombre, ":pwdHashed" => $pwdHashed]);
        $sth->setFetchMode(PDO::FETCH_CLASS, Usuario::class);
        $usuario = $sth->fetch();
        return ($usuario ?: null);
    }

}
