<?php

require_once(SERVER_PATH.'/config/parametros.php');

abstract class Model {

        protected $db;

        public function __construct()
        {
            global $config;

            $servername = $config['servername']; // Normalmente es 'localhost'
            $username = $config['username'];
            $password = $config['password']; // Normalmente es root o vacio
            $dbname = $config['dbname'];

            try {
                $this->db = new PDO("mysql:host=$servername;dbname=$dbname;charset=UTF8", $username, $password);
            } catch (\Throwable $th) {
                var_dump($th);
            }
        }

        abstract public function getAll();

        abstract public function get($index);

}