<?php

namespace App\DAO;

use App\Modelo\Familia;
use \PDO;

/**
 * Clase FamiliaDAO
 */

class FamiliaDAO {

    private PDO $bd;

    function __construct(PDO $bd) {
        $this->bd = $bd;
    }

    function crea(Familia $familia): void {
        
    }

    function modifica(Familia $familia): void {
        
    }

    function elimina(int $id): void {
        
    }

    function recuperaPorId(int $id): Familia {
        
    }
    
    /**
     * Recupera todas las familias de la base de datos
     * @return array
     */

    function recuperaTodo(): array {
        $sql = "select * from familias order by nombre";
        $stmt = $this->bd->prepare($sql);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, Familia::class);
        $familias = $stmt->fetchAll();
        $stmt->closeCursor();
        return $familias;
    }

}
