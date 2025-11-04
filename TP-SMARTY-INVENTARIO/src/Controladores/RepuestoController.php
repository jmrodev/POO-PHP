<?php
require_once(SERVER_PATH."/src/Modelos/Repuesto.php");
require_once(SERVER_PATH."/src/Vistas/RepuestoVista.php");

class RepuestoController {
    private $repuestoModel;
    private $repuestoVista;

    // No longer needed as images are stored in DB
    // const IMG_UPLOAD_DIR = SERVER_PATH . "/db/img/";

    public function __construct() {
        $this->repuestoModel = new Repuesto();
        $this->repuestoVista = new RepuestoVista();
    }

    private function handleImageUpload($currentImage = null) {
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['imagen']['tmp_name'];
            $fileContent = file_get_contents($fileTmpPath);
            if ($fileContent !== false) {
                return base64_encode($fileContent);
            }
        }
        return $currentImage; // Return current image if no new one uploaded or upload failed
    }

    public function showAll() {
        $repuestos = $this->repuestoModel->obtenerTodos();
        $this->repuestoVista->showRepuestos($repuestos);
    }

    public function showFormCreate() {
        $this->repuestoVista->displayForm();
    }

    public function create() {
        $nombre = $_POST['nombre'] ?? '';
        $precio = $_POST['precio'] ?? '';
        $cantidad = $_POST['cantidad'] ?? '';
        $imagen = $this->handleImageUpload();

        if (empty($nombre) || empty($precio) || empty($cantidad)) {
            $this->repuestoVista->displayForm("Todos los campos son obligatorios.", false);
            return;
        }

        $newRepuesto = new Repuesto(null, $nombre, $precio, $cantidad, $imagen);
        if ($newRepuesto->guardar()) {
            header('Location: ' . BASE_URL . 'repuestos');
            exit();
        } else {
            $this->repuestoVista->displayForm("Error al crear el repuesto.", false);
        }
    }

    public function showFormEdit($id) {
        $repuesto = $this->repuestoModel->obtenerPorId($id);
        if ($repuesto) {
            $this->repuestoVista->displayForm("", true, $repuesto);
        } else {
            header('Location: ' . BASE_URL . 'repuestos');
            exit();
        }
    }

    public function update() {
        $id = $_POST['id'] ?? null;
        $nombre = $_POST['nombre'] ?? '';
        $precio = $_POST['precio'] ?? '';
        $cantidad = $_POST['cantidad'] ?? '';

        if (empty($id) || empty($nombre) || empty($precio) || empty($cantidad)) {
            $this->repuestoVista->displayForm("Todos los campos son obligatorios.", false);
            return;
        }

        $existingRepuesto = $this->repuestoModel->obtenerPorId($id);
        $currentImage = $existingRepuesto ? $existingRepuesto->getImagen() : null;
        $imagen = $this->handleImageUpload($currentImage);

        $repuesto = new Repuesto($id, $nombre, $precio, $cantidad, $imagen);
        if ($repuesto->guardar()) {
            header('Location: ' . BASE_URL . 'repuestos');
            exit();
        } else {
            $this->repuestoVista->displayForm("Error al actualizar el repuesto.", false, $repuesto);
        }
    }

    public function showConfirmDelete($id) {
        $repuesto = $this->repuestoModel->obtenerPorId($id);
        if ($repuesto) {
            $this->repuestoVista->displayConfirmDelete($repuesto);
        } else {
            header('Location: ' . BASE_URL . 'repuestos');
            exit();
        }
    }

    public function delete($id) {
        $repuesto = new Repuesto($id);
        if ($repuesto->eliminar()) {
            header('Location: ' . BASE_URL . 'repuestos');
            exit();
        } else {
            // Optionally display an error message on the repuestos list page
            header('Location: ' . BASE_URL . 'repuestos');
            exit();
        }
    }

    public function showDetail($id) {
        $repuesto = $this->repuestoModel->obtenerPorId($id);
        if ($repuesto) {
            $this->repuestoVista->displayDetail($repuesto);
        } else {
            header('Location: ' . BASE_URL . 'repuestos');
            exit();
        }
    }
}
?>