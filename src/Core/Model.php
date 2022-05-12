<?php

namespace Sangtech\Scandiweb\Core;

use Sangtech\Scandiweb\Core\Application;
use Sangtech\Scandiweb\Core\Database;

/**
 * Class ProductModel
 * 
 * @author Aboubacar Sangare <abouba.sang@outlook.com>
 * @package Sangtech\Scandiweb\Core
 */

abstract class Model
{
    private Database $db;

    public function __construct()
    {
        $this->db = Application::getDB();
    }

    public function db(): Database
    {
        return $this->db;
    }
}
