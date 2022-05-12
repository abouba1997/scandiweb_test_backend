<?php

use Sangtech\Scandiweb\Core\Application;
use Sangtech\Scandiweb\Core\Database;

/**
 * Class Migration (second migration)
 * 
 * @author Aboubacar Sangare <abouba.sang@outlook.com>
 * @package Sangtech\Scandiweb\Core
 */

class m002_second_migration
{
    private Database $db;

    public function __construct()
    {
        $this->db = Application::getDB();
    }

    public function up(): string
    {
        $db_sql_product = 'INSERT INTO product (product_sku, product_name, product_price, product_type) VALUES
        ("JVC200123", "Acme DISC", "1.00", "DVD-disc"),
        ("JVC200123", "Acme DISC", "1.00", "DVD-disc"),
        ("JVC200123", "Acme DISC", "1.00", "DVD-disc"),
        ("JVC200123", "Acme DISC", "1.00", "DVD-disc"),
        ("GGWP0007", "War and Peace", "20.00", "Book"),
        ("GGWP0007", "War and Peace", "20.00", "Book"),
        ("GGWP0007", "War and Peace", "20.00", "Book"),
        ("GGWP0007", "War and Peace", "20.00", "Book"),
        ("TR120555", "Chair", "40.00", "Furniture"),
        ("TR120555", "Chair", "40.00", "Furniture"),
        ("TR120555", "Chair", "40.00", "Furniture"),
        ("TR120555", "Chair", "40.00", "Furniture");';

        $db_sql_dvd = 'INSERT INTO dvd (dvd_id_fk, dvd_size) VALUES
            (1, 700),
            (2, 700),
            (3, 700),
            (4, 700);';

        $db_sql_book = 'INSERT INTO book (book_id_fk, book_weight) VALUES
            (5, 2),
            (6, 2),
            (7, 2),
            (8, 2);';

        $db_sql_furniture = 'INSERT INTO furniture (furniture_id_fk, furniture_height, furniture_width, furniture_length) VALUES
            (9, 24, 45, 15),
            (10, 24, 45, 15),
            (11, 24, 45, 15),
            (12, 24, 45, 15);';

        // Begin Database transaction
        $this->db->beginTransaction();

        try {
            $this->db->exec($db_sql_product);

            $this->db->exec($db_sql_dvd);

            $this->db->exec($db_sql_book);

            $this->db->exec($db_sql_furniture);

            $this->db->commit();
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollback();
            }
            throw $e;
        }
        return "Up migration successfully";
    }

    public function down(): string
    {
        $sql_tables_truncate = 'TRUNCATE TABLE product;';
        $this->db->exec($sql_tables_truncate);
        return "Down migration successfully";
    }
}
