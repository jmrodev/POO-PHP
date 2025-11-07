<?php

class ClienteRepository
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
        $clientesData = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $clientes = [];
        foreach ($clientesData as $data) {
            $clientes[] = new Cliente($data['id'], $data['nombre'], $data['username'], $data['password'], $data['dni']);
        }
        return $clientes;
    }

    public function obtenerPorId($id): ?\App\Modelos\Cliente
    {
        $stmt = $this->db->prepare("SELECT id, nombre, username, password, dni FROM personas WHERE id = :id AND role = 'client'");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($data) {
            return new Cliente($data['id'], $data['nombre'], $data['username'], $data['password'], $data['dni']);
        }
        return null;
    }

    public function guardar(\App\Modelos\Cliente $cliente)
    {
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            return $this->personaRepository->save($cliente);
        } catch (PDOException $e) {
            error_log("Error al guardar cliente: " . $e->getMessage());
            echo "Error al guardar cliente: " . $e->getMessage();
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
