<?php
    require_once("bd.php");

    class NoticiasModel {

        private $db;

        public function __construct()
        {
            global $db;

            $this->db = $db;
        }

        public function getAll() {
            return $this->db['noticias'];
        }

        public function get($index) {

            foreach ($this->db['noticias'] as $noticia) {
                if ($noticia['id'] == $index) {
                    return $noticia;
                }
            }

            return null;
        }



    }