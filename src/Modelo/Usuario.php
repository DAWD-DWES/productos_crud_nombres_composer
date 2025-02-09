<?php

namespace App\Modelo;

class Usuario {

    private int $id;
    private string $usuario;
    private string $pass;

    public function __construct(string $usuario = null, string $pass = null) {
        if (func_num_args() > 0) {
            $this->usuario = $usuario;
            $this->pass = $pass;
        }
    }

    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id) {
        $this->id = $id;
    }

    public function getUsuario(): string {
        return $this->usuario;
    }

    public function setUsuario(string $usuario) {
        $this->usuario = $usuario;
    }

    public function getPass(): string {
        return $this->pass;
    }

    public function setPass(string $pass) {
        $this->pass = $pass;
    }
}
