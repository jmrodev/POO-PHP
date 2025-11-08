<?php
    require_once('Model.php');
    
    class NoticiasModel extends Model {

        public function getAll() {
            $sql = "SELECT * FROM noticia";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();

            $resultado = $stmt->fetchAll(PDO::FETCH_OBJ);

            return $resultado;
        }

        public function get($index) {

            $sql = "SELECT * FROM noticia WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$index]);

            $resultado = $stmt->fetchAll(PDO::FETCH_OBJ);

            if (count($resultado) > 0) {
                return $resultado[0];
            }            

            return null;
        }

    }