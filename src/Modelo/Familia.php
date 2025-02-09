<?php

namespace App\Modelo;


class Familia {

    private string $cod;
    private string $nombre;

    public function __construct(string $cod = null, string $nombre = null) {
        if (func_num_args() > 0) {
            $this->cod = $cod;
            $this->$nombre = $nombre;
        }
    }

    public function getCod(): string {
        return $this->cod;
    }

    public function getNombre(): string {
        return $this->nombre;
    }

    public function setCod(string $cod): void {
        $this->cod = $cod;
    }

    public function setNombre(string $nombre): void {
        $this->nombre = $nombre;
    }
}
