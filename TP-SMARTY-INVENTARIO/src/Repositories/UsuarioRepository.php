<?php

class UsuarioRepository
{
    private $db;
    private \App\Repositories\PersonaRepository $personaRepository;

    public function __construct(\PDO $pdo)
    {
        $this->db = $pdo;
        $this->personaRepository = new \App\Repositories\PersonaRepository($this->db);
    }

    public function obtenerTodos()
    {
        $stmt = $this->db->query("SELECT id, nombre, username, password, dni FROM personas WHERE role = 'client'");
        $usuariosData = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $usuarios = [];
        foreach ($usuariosData as $data) {
            $usuarios[] = new Usuario($data['id'], $data['nombre'], $data['username'], $data['password'], $data['dni']);
        }
        return $usuarios;
    }

    public function obtenerPorId($id): ?\App\Modelos\Usuario
    {
        $stmt = $this->db->prepare("SELECT id, nombre, username, password, dni FROM personas WHERE id = :id AND role = 'client'");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($data) {
            return new Usuario($data['id'], $data['nombre'], $data['username'], $data['password'], $data['dni']);
        }
        return null;
    }

    public function guardar(\App\Modelos\Usuario $usuario)
    {
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            return $this->personaRepository->save($usuario);
        } catch (PDOException $e) {
            error_log("Error al guardar usuario: " . $e->getMessage());
            echo "Error al guardar usuario: " . $e->getMessage();
            return false;
        } finally {
        }
    }

    public function eliminar($id)
    {
        $stmt = $this->db->prepare("DELETE FROM personas WHERE id = :id AND role = 'client'");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        return $stmt->execute();
    }
}
