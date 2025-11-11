<?php

namespace App\Controladores;

use App\Modelos\Repuesto;
use App\Repositories\RepuestoRepository;
use App\Validators\RepuestoValidator;
use App\Services\AuthService;
use Smarty;

class RepuestoController extends BaseController
{
    private RepuestoRepository $repuestoRepository;

    public function __construct(\Smarty $smarty, RepuestoRepository $repuestoRepository, AuthService $authService)
    {
        parent::__construct($smarty, $authService);
        $this->repuestoRepository = $repuestoRepository;
    }

    private function handleImageUpload(?string $currentImage = null): ?string
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

    public function index(): void
    {
        // AuthMiddleware::requireOnlySupervisor(); // Replaced by router middleware

        $perPage = 10; // Número de repuestos por página
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($currentPage < 1) {
            $currentPage = 1;
        }

        $totalRepuestos = $this->repuestoRepository->contarTodos();
        $totalPages = ceil($totalRepuestos / $perPage);

        $repuestos = $this->repuestoRepository->obtenerPaginado($currentPage, $perPage);
        
        $this->smarty->assign('repuestos', $repuestos);
        $this->smarty->assign('page_title', 'Gestión de Repuestos');
        $this->smarty->assign('currentPage', $currentPage);
        $this->smarty->assign('totalPages', $totalPages);
        $this->smarty->assign('perPage', $perPage);
        $this->smarty->display('repuestos.tpl');
    }

    public function showFormCreate(): void
    {
        // AuthMiddleware::requireOnlySupervisor(); // Replaced by router middleware

        $this->smarty->assign('page_title', 'Añadir Repuesto');
        $this->smarty->assign('form_action', BASE_URL . 'repuestos/create');
        $this->smarty->assign('is_edit', false);
        $this->smarty->assign('repuesto', new Repuesto(null, '', 0, 0, null)); // Assign an empty Repuesto object
        $this->smarty->assign('form_data', []); // Always assign empty form_data
        $this->smarty->display('form_repuesto.tpl');
    }

    public function create(): void
    {
        // AuthMiddleware::requireOnlySupervisor(); // Replaced by router middleware

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $validator = new RepuestoValidator();
            $data = [
                'nombre' => $_POST['nombre'] ?? '',
                'precio' => $_POST['precio'] ?? '',
                'cantidad' => $_POST['cantidad'] ?? '',
            ];

            // Create a Repuesto object with submitted data for re-populating the form
            $repuestoWithSubmittedData = new Repuesto(null, $data['nombre'], $data['precio'], $data['cantidad'], null);

            if (!$validator->validate($data, $_FILES)) {
                $this->smarty->assign('error_message', implode(", ", $validator->getErrors()));
                $this->smarty->assign('form_data', $data);
                $this->smarty->assign('page_title', 'Añadir Repuesto');
                $this->smarty->assign('form_action', BASE_URL . 'repuestos/create');
                $this->smarty->assign('is_edit', false);
                $this->smarty->assign('repuesto', $repuestoWithSubmittedData);
                $this->smarty->display('form_repuesto.tpl');
                return;
            }

            $imagen = $this->handleImageUpload();
            $newRepuesto = new Repuesto(null, $data['nombre'], $data['precio'], $data['cantidad'], $imagen);

            if ($this->repuestoRepository->guardar($newRepuesto)) {
                $this->redirect(BASE_URL . 'repuestos');
            } else {
                $this->smarty->assign('error_message', 'Error al crear el repuesto.');
                $this->smarty->assign('form_data', $data);
                $this->smarty->assign('page_title', 'Añadir Repuesto');
                $this->smarty->assign('form_action', BASE_URL . 'repuestos/create');
                $this->smarty->assign('is_edit', false);
                $this->smarty->assign('repuesto', $repuestoWithSubmittedData);
                $this->smarty->display('form_repuesto.tpl');
            }
        }
    }

    public function showFormEdit(int $id): void
    {
        // AuthMiddleware::requireOnlySupervisor(); // Replaced by router middleware

        $repuesto = $this->repuestoRepository->obtenerPorId($id);
        if (!$repuesto || !($repuesto instanceof Repuesto)) {
            $this->redirect(BASE_URL . 'repuestos');
            return;
        }

        $this->smarty->assign('page_title', 'Editar Repuesto');
        $this->smarty->assign('form_action', BASE_URL . 'repuestos/update');
        $this->smarty->assign('is_edit', true);
        $this->smarty->assign('repuesto', $repuesto);
        $this->smarty->assign('form_data', []); // Always assign empty form_data
        $this->smarty->display('form_repuesto.tpl');
    }

    public function update(): void
    {
        // AuthMiddleware::requireOnlySupervisor(); // Replaced by router middleware

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $validator = new RepuestoValidator();
            $data = [
                'id' => $_POST['id'] ?? null,
                'nombre' => $_POST['nombre'] ?? '',
                'precio' => $_POST['precio'] ?? '',
                'cantidad' => $_POST['cantidad'] ?? '',
            ];

            $existingRepuesto = $this->repuestoRepository->obtenerPorId($data['id']);
            if (!$existingRepuesto || !($existingRepuesto instanceof Repuesto)) {
                $this->redirect(BASE_URL . 'repuestos');
                return;
            }

            // Create a Repuesto object with submitted data for re-populating the form
            $repuestoWithSubmittedData = new Repuesto($data['id'], $data['nombre'], $data['precio'], $data['cantidad'], $existingRepuesto->getImagen());

            if (!$validator->validate($data, $_FILES, true)) { // Pass true for isUpdate
                $this->smarty->assign('error_message', implode(", ", $validator->getErrors()));
                $this->smarty->assign('form_data', $data);
                $this->smarty->assign('page_title', 'Editar Repuesto');
                $this->smarty->assign('form_action', BASE_URL . 'repuestos/update');
                $this->smarty->assign('is_edit', true);
                $this->smarty->assign('repuesto', $repuestoWithSubmittedData);
                $this->smarty->display('form_repuesto.tpl');
                return;
            }

            $currentImage = $existingRepuesto->getImagen();
            $imagen = $this->handleImageUpload($currentImage);

            $repuestoToUpdate = new Repuesto(
                $data['id'],
                $data['nombre'],
                $data['precio'],
                $data['cantidad'],
                $imagen
            );

            if ($this->repuestoRepository->guardar($repuestoToUpdate)) {
                $this->redirect(BASE_URL . 'repuestos');
            } else {
                $this->smarty->assign('error_message', 'Error al actualizar el repuesto.');
                $this->smarty->assign('form_data', $data);
                $this->smarty->assign('page_title', 'Editar Repuesto');
                $this->smarty->assign('form_action', BASE_URL . 'repuestos/update');
                $this->smarty->assign('is_edit', true);
                $this->smarty->assign('repuesto', $repuestoWithSubmittedData);
                $this->smarty->display('form_repuesto.tpl');
            }
        }
    }

    public function showConfirmDelete(int $id): void
    {
        // AuthMiddleware::requireOnlySupervisor(); // Replaced by router middleware

        $repuesto = $this->repuestoRepository->obtenerPorId($id);
        if (!$repuesto || !($repuesto instanceof Repuesto)) {
            $this->redirect(BASE_URL . 'repuestos');
            return;
        }

        $this->smarty->assign('page_title', 'Confirmar Eliminación de Repuesto');
        $this->smarty->assign('repuesto', $repuesto);
        $this->smarty->assign('form_data', []); // Always assign empty form_data
        $this->smarty->display('confirm_delete_repuesto.tpl');
    }

    public function delete(int $id): void
    {
        // AuthMiddleware::requireOnlySupervisor(); // Replaced by router middleware

        if ($this->repuestoRepository->eliminar($id)) {
            $this->redirect(BASE_URL . 'repuestos');
        } else {
            $this->redirect(BASE_URL . 'repuestos');
        }
    }

    public function showDetail(int $id): void
    {
        // AuthMiddleware::requireOnlySupervisor(); // Replaced by router middleware

        $repuesto = $this->repuestoRepository->obtenerPorId($id);
        if (!$repuesto || !($repuesto instanceof Repuesto)) {
            $this->redirect(BASE_URL . 'repuestos');
            return;
        }

        $this->smarty->assign('page_title', 'Detalle de Repuesto');
        $this->smarty->assign('repuesto', $repuesto);
        $this->smarty->display('repuesto_detail.tpl');
    }
}