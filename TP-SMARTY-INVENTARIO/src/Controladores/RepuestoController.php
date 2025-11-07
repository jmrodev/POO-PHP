<?php

class RepuestoController extends BaseController
{
    private $repuestoRepository;
    private $repuestoVista;

    public function __construct()
    {
        parent::__construct();
        $this->repuestoRepository = new RepuestoRepository($this->db);
        $this->repuestoVista = $this->loadView('RepuestoVista');
    }

    private function handleImageUpload($currentImage = null)
    {
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['imagen']['tmp_name'];
            $fileContent = file_get_contents($fileTmpPath);
            if ($fileContent !== false) {
                return base64_encode($fileContent);
            }
        }
        return $currentImage; // Return current image if no new one uploaded or upload failed
    }

    public function showAll()
    {
        $repuestos = $this->repuestoRepository->obtenerTodos(); // Use repository method
        $this->repuestoVista->showRepuestos($repuestos);
    }

    public function showFormCreate()
    {
        $this->repuestoVista->displayForm();
    }

    public function create()
    {
        require_once(SERVER_PATH . "/src/Validators/RepuestoValidator.php");
        $validator = new RepuestoValidator();
        $data = ['nombre' => $_POST['nombre'] ?? '', 'precio' => $_POST['precio'] ?? '', 'cantidad' => $_POST['cantidad'] ?? ''];

        if (!$validator->validate($data, $_FILES)) {
            $this->repuestoVista->displayForm(implode(", ", $validator->getErrors()), false);
            return;
        }

        $imagen = $this->handleImageUpload();
        $newRepuesto = new Repuesto(null, $data['nombre'], $data['precio'], $data['cantidad'], $imagen);
        if ($this->repuestoRepository->guardar($newRepuesto)) { // Use repository method
            $this->redirect(BASE_URL . 'repuestos');
        } else {
            $this->repuestoVista->displayForm("Error al crear el repuesto.", false);
        }
    }

    public function showFormEdit($id)
    {
        $repuesto = $this->repuestoRepository->obtenerPorId($id); // Use repository method
        if ($repuesto) {
            $this->repuestoVista->displayForm("", true, $repuesto);
        } else {
            $this->redirect(BASE_URL . 'repuestos');
        }
    }

    public function update()
    {
        require_once(SERVER_PATH . "/src/Validators/RepuestoValidator.php");
        $validator = new RepuestoValidator();
        $data = ['id' => $_POST['id'] ?? null, 'nombre' => $_POST['nombre'] ?? '', 'precio' => $_POST['precio'] ?? '', 'cantidad' => $_POST['cantidad'] ?? ''];

        if (!$validator->validate($data, $_FILES, true)) { // Pass true for isUpdate
            $existingRepuesto = $this->repuestoRepository->obtenerPorId($data['id']);
            $currentImage = $existingRepuesto ? $existingRepuesto->getImagen() : null;
            $imagen = $this->handleImageUpload($currentImage); // Re-handle image to pass to form if validation fails
            $repuesto = new Repuesto($data['id'], $data['nombre'], $data['precio'], $data['cantidad'], $imagen);
            $this->repuestoVista->displayForm(implode(", ", $validator->getErrors()), false, $repuesto);
            return;
        }

        $existingRepuesto = $this->repuestoRepository->obtenerPorId($data['id']);
        $currentImage = $existingRepuesto ? $existingRepuesto->getImagen() : null;
        $imagen = $this->handleImageUpload($currentImage);

        $repuesto = new Repuesto($data['id'], $data['nombre'], $data['precio'], $data['cantidad'], $imagen);
        if ($this->repuestoRepository->guardar($repuesto)) { // Use repository method
            $this->redirect(BASE_URL . 'repuestos');
        } else {
            $this->repuestoVista->displayForm("Error al actualizar el repuesto.", false, $repuesto);
        }
    }

    public function showConfirmDelete($id)
    {
        $repuesto = $this->repuestoRepository->obtenerPorId($id); // Use repository method
        if ($repuesto) {
            // Assuming there's a method to display a confirmation view in RepuestoVista
            $this->repuestoVista->showConfirmDelete($repuesto);
        } else {
            $this->redirect(BASE_URL . 'repuestos');
        }
    }

    public function delete($id)
    {
        if ($this->repuestoRepository->eliminar($id)) { // Use repository method
            $this->redirect(BASE_URL . 'repuestos');
        } else {
            header('Location: ' . BASE_URL . 'repuestos');
            exit();
        }
    }

    public function showDetail($id)
    {
        $repuesto = $this->repuestoRepository->obtenerPorId($id); // Use repository method
        if ($repuesto) {
            $this->repuestoVista->displayDetail($repuesto);
        } else {
            $this->redirect(BASE_URL . 'repuestos');
        }
    }
}
