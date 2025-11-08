<?php
    require_once("bd.php");

    class NosotrosModel {

        private $db;

        public function __construct()
        {
            global $db;

            $this->db = $db;
        }

        public function getAll() {
            return $this->db['nosotros'];
        }

        public function get($index) {

            if ($index < count($this->db['nosotros'])) {
                return $this->db['nosotros'][$index];
            }            

            return null;
        }

    }