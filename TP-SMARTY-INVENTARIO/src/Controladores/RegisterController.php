<?php

class RegisterController
{
    private PersonaRepository $personaRepository;
    private \Smarty\Smarty $smarty;
    private PDO $pdo;

    public function __construct()
    {
        $config = [
            'servername' => '127.0.0.1',
            'username' => 'root',
            'password' => 'jmro1975',
            'dbname' => 'inventarioRepuestos',
        ];

        try {
            $this->pdo = new PDO("mysql:host=" . $config['servername'] . ";dbname=" . $config['dbname'] . ";charset=utf8mb4", $config['username'], $config['password']);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error de conexión a la base de datos: " . $e->getMessage());
        }

        $this->personaRepository = new PersonaRepository($this->pdo);
        $this->smarty = new \Smarty\Smarty();
        $this->smarty->setTemplateDir(SERVER_PATH . '/templates');
        $this->smarty->setCompileDir(SERVER_PATH . '/templates_c');
        $this->smarty->assign('BASE_URL', BASE_URL);
    }

    public function showRegistrationForm($errors = [])
    {
        $this->smarty->assign('errors', $errors);
        $this->smarty->display('register.tpl');
    }

    public function registerUser()
    {
        $errors = [];

        $nombre = trim($_POST['nombre'] ?? '');
        $dni = trim($_POST['dni'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if (empty($nombre)) {
            $errors[] = "El nombre es obligatorio.";
        }
        if (empty($dni)) {
            $errors[] = "El DNI es obligatorio.";
        } elseif ($this->personaRepository->dniExists($dni)) {
            $errors[] = "El DNI ya está registrado.";
        }
        if (empty($username)) {
            $errors[] = "El nombre de usuario es obligatorio.";
        }
        if ($this->personaRepository->usernameExists($username)) {
            $errors[] = "El nombre de usuario ya está en uso.";
        }
        if (empty($password)) {
            $errors[] = "La contraseña es obligatoria.";
        } elseif (strlen($password) < 6) {
            $errors[] = "La contraseña debe tener al menos 6 caracteres.";
        }
        if ($password !== $confirm_password) {
            $errors[] = "Las contraseñas no coinciden.";
        }

        if (empty($errors)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $cliente = new Cliente(null, $nombre, $username, $hashed_password, $dni);

            if ($this->personaRepository->save($cliente)) {
                header('Location: ' . BASE_URL . 'login');
                exit();
            } else {
                $errors[] = "Error al registrar el cliente. Inténtelo de nuevo.";
            }
        }

        $this->showRegistrationForm($errors);
    }
}
