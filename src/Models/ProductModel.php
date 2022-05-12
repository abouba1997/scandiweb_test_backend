<?php

namespace Sangtech\Scandiweb\Models;

use Sangtech\Scandiweb\Core\Model;

/**
 * Class ProductModel
 * 
 * @author Aboubacar Sangare <abouba.sang@outlook.com>
 * @package Sangtech\Scandiweb\Core
 */

class ProductModel extends Model
{
    public function create(array $data): int
    {
        $sql = "INSERT INTO product (product_sku, product_name, product_price, product_type)
        VALUES (:product_sku,:product_name,:product_price,:product_type)";

        $statement = $this->db()->prepare($sql);

        $statement->execute([
            'product_sku' => $data["sku"],
            'product_name' => $data["name"],
            'product_price' => $data["price"],
            'product_type' => $data["productType"]
        ]);

        return (int) $this->db()->lastInsertId();
    }

    public function delete(array $data): string
    {
        $sql = "DELETE FROM product WHERE product.product_id=:id";
        $statement = $this->db()->prepare($sql);

        foreach($data as $value) {
            $statement->execute([':id' => $value]);
        }

        return "Deleting products successfully.";
    }
}
