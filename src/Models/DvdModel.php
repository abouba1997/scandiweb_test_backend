<?php

namespace Sangtech\Scandiweb\Models;

use Sangtech\Scandiweb\Core\Model;

/**
 * Class DvdModel
 * 
 * @author Aboubacar Sangare <abouba.sang@outlook.com>
 * @package Sangtech\Scandiweb\Models
 */

class DvdModel extends Model
{
    public function create(array $data, int $productId): int
    {
        $sql = "INSERT INTO dvd (dvd_id_fk, dvd_size) VALUES (:productId,:size)";
        $statement = $this->db()->prepare($sql);
        $statement->execute([
            'productId' => $productId,
            'size' => $data["size"]
        ]);

        $lastId = (int)$this->db()->lastInsertId();
        $newStmt = $this->db()->prepare("SELECT dvd.dvd_id_fk FROM dvd WHERE dvd.dvd_id=$lastId");
        $newStmt->execute();
        $lastInserted = $newStmt->fetch();
        return (int) $lastInserted["dvd_id_fk"];
    }
}
