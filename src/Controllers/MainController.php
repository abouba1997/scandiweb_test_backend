<?php

namespace Sangtech\Scandiweb\Controllers;

use Exception;
use Sangtech\Scandiweb\Core\Application;
use Sangtech\Scandiweb\Core\Request;
use Sangtech\Scandiweb\Core\Response;
use Sangtech\Scandiweb\Models\BookModel;
use Sangtech\Scandiweb\Models\DvdModel;
use Sangtech\Scandiweb\Models\FurnitureModel;
use Sangtech\Scandiweb\Models\ProductModel;

/**
 * Class MainController
 * 
 * @author Aboubacar Sangare <abouba.sang@outlook.com>
 * @package Sangtech\Scandiweb\Core
 */


class MainController
{
    public function index(Request $request, Response $response)
    {
        return $response->send("Hello world");
    }

    public function products(Request $request, Response $response)
    {
        $sql = "SELECT product.*, dvd.dvd_size, book.book_weight, furniture.furniture_height, furniture.furniture_width, furniture.furniture_length FROM `product` LEFT JOIN `dvd` ON `product`.`product_id`=`dvd`.`dvd_id_fk` LEFT JOIN `book` ON `product`.`product_id`=`book`.`book_id_fk` LEFT JOIN `furniture` ON `product`.`product_id`=`furniture`.`furniture_id_fk`";

        $statement = Application::getDB()->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll();
        
        return $response->send($result);
    }

    public function create(Request $request, Response $response)
    {
        $body = $request->body();

        $productModel = new ProductModel();
        $product = null;

        $productType = $body['productType'];
        if ($productType === "DVD") {
            $product = new DvdModel();
        } else if ($productType === "Book") {
            $product = new BookModel();
        } else if ($productType === "Furniture") {
            $product = new FurnitureModel();
        }

        // Begin database transaction
        Application::getDB()->beginTransaction();
        try {
            $productModelId = $productModel->create($body);
            $productId = $product->create($body, $productModelId);
            Application::getDB()->commit();
        } catch (Exception $e) {
            if (Application::getDB()->inTransaction()) {
                Application::getDB()->rollBack();
            }
            throw $e;
        }

        $product_name = strtolower($productType);

        // Getting the last added product
        $sql = "SELECT product.*," . $product_name . ".* FROM product 
        LEFT JOIN " . $product_name . " ON product.product_id = ". $product_name . "." . $product_name . "_id_fk
        WHERE product.product_id=$productId";

        $statement = Application::getDB()->prepare($sql);
        $statement->execute();
        $result = $statement->fetch();

        return $response->send($result);
    }

    public function delete(Request $request, Response $response)
    {
        $body = $request->body();
        $productModel = new ProductModel();
        $productModel->delete($body);
        return $response->send($body);
    }
}
