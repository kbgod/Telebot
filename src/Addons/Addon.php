<?php
namespace Addons;

use Core\Database;

class Addon {

    protected $db;

    public function __construct() {
        $this->db = new Database('127.0.0.1', 'telebot', 'root', '');
    }

}