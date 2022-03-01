<?php

namespace Jara\Core\Database;

use Jara\App\Configs\Config;

class Connect
{
    private $dsn;
    private $pdo;

    public function __construct()
    {
    }

    public function connect()
    {
        /**
         * Try to connect to the database
         */
        try {

            $this->dsn = 'mysql:host=' . Config::$db_host . ':' . Config::$db_port . ';dbname=' . Config::$db_name;
            $this->pdo = new \PDO($this->dsn, Config::$db_username, Config::$db_password);

            /**
             * set the PDO error mode to exception
             */
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
            $this->pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, true);

            return $this->pdo;
        } catch (\PDOException $e) {
            if (Config::$app_debug) {
                echo "Connection failed: " . $e->getMessage();
            } else {
                echo 'Something went wrong please retry sometimes later';
            }
            die();
        }
    }
}
