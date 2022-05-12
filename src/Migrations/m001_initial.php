<?php

use Sangtech\Scandiweb\Core\Application;
use Sangtech\Scandiweb\Core\Database;

/**
 * Class Migration (initial migration)
 * Creating Database (mvc_scandiweb) with tables (dvd, book, furniture)
 * @author Aboubacar Sangare <abouba.sang@outlook.com>
 * @package Sangtech\Scandiweb\Core
 * 
 * Get executed by migrations.php (manually)
 */

class m001_initial
{
    private Database $db;

    public function __construct()
    {
        $this->db = Application::getDB();
    }

    public function up(): string
    {
        /**
         * Product Table creation sql
         */
        $db_table_product_sql = "CREATE TABLE IF NOT EXISTS `product` (
            `product_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `product_sku` VARCHAR(45) NOT NULL,
            `product_name` VARCHAR(255) NOT NULL,
            `product_price` DECIMAL(10,2) NOT NULL,
            `product_type` VARCHAR(45) NOT NULL) ENGINE=INNODB;";

        /**
         * DVD-disc Table creation sql
         */
        $db_table_dvd_sql = "CREATE TABLE IF NOT EXISTS `dvd` (
            `dvd_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `dvd_id_fk` INT NOT NULL,
            `dvd_size` DECIMAL(10,0) NOT NULL,
            CONSTRAINT `dvd_id_fk_fk`
              FOREIGN KEY (`dvd_id_fk`)
              REFERENCES `product` (`product_id`)
              ON DELETE CASCADE
              ON UPDATE CASCADE) ENGINE=INNODB;";

        /**
         * Book Table creation sql
         */
        $db_table_book_sql = "CREATE TABLE IF NOT EXISTS `book` (
            `book_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `book_id_fk` INT NOT NULL,
            `book_weight` INT NOT NULL,
            CONSTRAINT `book_id_fk_fk`
              FOREIGN KEY (`book_id_fk`)
              REFERENCES `product` (`product_id`)
              ON DELETE CASCADE
              ON UPDATE CASCADE) ENGINE=INNODB;";

        /**
         * Furniture Table creation sql
         */
        $db_table_furniture_sql = "CREATE TABLE IF NOT EXISTS `furniture` (
            `furniture_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `furniture_id_fk` INT NOT NULL,
            `furniture_height` INT NOT NULL,
            `furniture_width` INT NOT NULL,
            `furniture_length` INT NOT NULL,
            CONSTRAINT `furniture_id_fk_fk`
              FOREIGN KEY (`furniture_id_fk`)
              REFERENCES `product` (`product_id`)
              ON DELETE CASCADE
              ON UPDATE CASCADE) ENGINE=INNODB;";

        try {
            $this->db->exec($db_table_product_sql);

            $this->db->exec($db_table_dvd_sql);

            $this->db->exec($db_table_book_sql);

            $this->db->exec($db_table_furniture_sql);
        } catch (Exception $e) {
            throw $e;
        }
        return "Up migration successfully";
    }

    public function down(): string
    {
        $sql = "DROP TABLE IF EXISTS `product`;";
        $this->db->exec($sql);
        return "Down migration successfully";
    }
}
