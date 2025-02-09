<?php

namespace App\DAO;

use App\Modelo\Producto;
use \PDO;

class ProductoDAO {

    private PDO $bd;

    function __construct(PDO $bd) {
        $this->bd = $bd;
    }

    function crea(Producto $producto): int|bool {
        $sql = "insert into productos (nombre, nombre_corto, descripcion, pvp, familia) values (:nombre, :nombre_corto, :descripcion, :pvp, :familia)";
        $stmt = $this->bd->prepare($sql);
        $result = $stmt->execute([":nombre" => $producto->getNombre(), ":nombre_corto" => $producto->getNombreCorto(), ":descripcion" => $producto->getDescripcion(), ":pvp" => $producto->getPvp(), ":familia" => $producto->getFamilia()]);
        return ($result ? $this->bd->lastInsertId() : false);
    }

    function modifica(Producto $producto): bool {
        $sql = "update productos set nombre = :nombre, nombre_corto = :nombre_corto, descripcion = :descripcion, pvp = :pvp, familia = :familia where id = :id";
        $stmt = $this->bd->prepare($sql);
        $result = $stmt->execute([":nombre" => $producto->getNombre(), ":nombre_corto" => $producto->getNombreCorto(), ":descripcion" => $producto->getDescripcion(), ":pvp" => $producto->getPvp(), ":familia" => $producto->getFamilia(), ":id" => $producto->getId()]);
        return $result;
    }

    function elimina(int $id): bool {
        $sql = "delete from productos where id = :id";
        $stmt = $this->bd->prepare($sql);
        $result = $stmt->execute([":id" => $id]);
        return $result;
        
    }

    function recuperaPorId(int $id): ?Producto {
        $sql = "select * from productos where id = :id";
        $stmt = $this->bd->prepare($sql);
        $stmt->execute([':id' => $id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, Producto::class);
        $producto = $stmt->fetch();
        return ($producto ?: null);
    }

    function recuperaTodo() {
        $sql = "select * from productos order by nombre";
        $sth = $this->bd->prepare($sql);
        $sth->execute();
        $sth->setFetchMode(PDO::FETCH_CLASS, Producto::class);
        $productos = $sth->fetchAll();
        return $productos;
    }
}
