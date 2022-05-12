<?php

namespace Sangtech\Scandiweb\Models;

use Sangtech\Scandiweb\Core\Model;

/**
 * Class BookModel
 * 
 * @author Aboubacar Sangare <abouba.sang@outlook.com>
 * @package Sangtech\Scandiweb\Models
 */

class BookModel extends Model
{
    public function create(array $data, int $productId): int
    {
        $sql = "INSERT INTO book (book_id_fk, book_weight) VALUES (:productId,:book_weight)";
        $statement = $this->db()->prepare($sql);
        $statement->execute([
            'productId' => $productId,
            'book_weight' => $data["weight"]
        ]);

        $lastId = (int)$this->db()->lastInsertId();
        $newStmt = $this->db()->prepare("SELECT book.book_id_fk FROM book WHERE book.book_id=$lastId");
        $newStmt->execute();
        $lastInserted = $newStmt->fetch();
        return (int) $lastInserted["book_id_fk"];
    }
}
