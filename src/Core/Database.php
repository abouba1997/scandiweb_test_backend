<?php

namespace Sangtech\Scandiweb\Core;

use PDO;
use PDOException;

/**
 * Class Database
 * 
 * @author Aboubacar Sangare <abouba.sang@outlook.com>
 * @package Sangtech\Scandiweb\Core
 */

class Database
{
    private PDO $pdo;

    public function __construct()
    {
        $dsn = getenv('DATABASE_DSN');
        $user = getenv('DATABASE_USER');
        $password =  getenv('DATABASE_PASSWORD');

        try {
            $this->pdo = new PDO($dsn, $user, $password, array(
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ));
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $th) {
            throw new PDOException($th->getMessage(), (int)$th->getCode());
        }
    }

    public function __call(string $name, array $arguments)
    {
        return call_user_func_array([$this->pdo, $name], $arguments);
    }

    private function makeMigrationsTable(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS migrations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)
                ENGINE=INNODB;";

        $this->pdo->exec($sql);
    }

    private function findAppliedMigrations(): array
    {
        $sql = "SELECT migration FROM migrations";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_COLUMN);
    }

    private function saveMigrations(array $migrations): void
    {
        $elements = implode(",", array_map(fn ($m) => "('$m')", $migrations));
        $sql = "INSERT INTO migrations (migration) VALUES $elements";

        $statement = $this->pdo->prepare($sql);
        $statement->execute();
    }

    public function applyMigrations(): void
    {
        $this->makeMigrationsTable();
        $migrations = $this->findAppliedMigrations();

        $files = scandir(dirname(__DIR__) . '/Migrations');
        $toApplyMigration = array_diff($files, $migrations);

        $newMigrations = array();

        foreach ($toApplyMigration as $migration) {
            if ($migration === '.' || $migration === '..') {
                continue;
            }

            require_once dirname(__DIR__) . '/Migrations//' . $migration;
            $className = pathinfo($migration, PATHINFO_FILENAME);

            $instance = new $className($this);
            $this->log("Applying migration $migration");
            $this->log($instance->up());
            $this->log("Applied migration $migration") . PHP_EOL;

            $newMigrations[] = $migration;
        }

        if (!empty($newMigrations)) {
            $this->saveMigrations($newMigrations);
        } else {
            $this->log("All migrations are applied");
        }
    }

    private function log(string $message): void
    {
        echo "[" . date('Y-m-d H:i:s') . '] - ' . $message . PHP_EOL;
    }
}
