<?php

namespace App\Modelo;


class Producto {

    private int $id;
    private string $nombre;
    private string $nombre_corto;
    private float $pvp;
    private string $familia;
    private string $descripcion;

    public function __construct(string $nombre = null, string $nombreCorto = null, string $descripcion = null, float $pvp = null, string $familia = null) {
        if (func_num_args() > 0) {
            $this->nombre = $nombre;
            $this->nombre_corto = $nombreCorto;
            $this->descripcion = $descripcion;
            $this->pvp = $pvp;
            $this->familia = $familia;
        }
    }

    public function getId(): int {
        return $this->id;
    }

    public function getNombre(): string {
        return $this->nombre;
    }

    public function getNombreCorto(): string {
        return $this->nombre_corto;
    }

    public function getPvp(): float {
        return $this->pvp;
    }

    public function getFamilia(): string {
        return $this->familia;
    }

    public function getDescripcion(): string {
        return $this->descripcion;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setNombre(string $nombre): void {
        $this->nombre = $nombre;
    }

    public function setNombreCorto(string $nombreCorto): void {
        $this->nombre_corto = $nombreCorto;
    }

    public function setPvp(float $pvp): void {
        $this->pvp = $pvp;
    }

    public function setFamilia(string $familia): void {
        $this->familia = $familia;
    }

    public function setDescripcion(string $descripcion): void {
        $this->descripcion = $descripcion;
    }
}
