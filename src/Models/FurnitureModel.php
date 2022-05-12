<?php

namespace Sangtech\Scandiweb\Models;

use Sangtech\Scandiweb\Core\Model;

/**
 * Class FurnitureModel
 * 
 * @author Aboubacar Sangare <abouba.sang@outlook.com>
 * @package Sangtech\Scandiweb\Models
 */

class FurnitureModel extends Model
{
    public function create(array $data, int $productId): int
    {
        $sql = "INSERT INTO furniture (furniture_id_fk, furniture_height, furniture_width, furniture_length) 
        VALUES (:productId,:furniture_height,:furniture_width,:furniture_length)";
        $statement = $this->db()->prepare($sql);
        $statement->execute([
            'productId' => $productId,
            'furniture_height' => $data["height"],
            'furniture_width' => $data["width"],
            'furniture_length' => $data["length"]
        ]);

        $lastId = (int)$this->db()->lastInsertId();
        $newStmt = $this->db()->prepare("SELECT furniture.furniture_id_fk FROM furniture WHERE furniture.furniture_id=$lastId");
        $newStmt->execute();
        $lastInserted = $newStmt->fetch();
        return (int) $lastInserted["furniture_id_fk"];
    }
}
