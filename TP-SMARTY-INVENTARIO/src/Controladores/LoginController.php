<?php

class LoginController
{
    private $personaRepository;
    private $smarty;
    private $pdo;

    public function __construct()
    {
        // Database connection setup
        $config = [
            'servername' => '127.0.0.1',
            'username' => 'root',
            'password' => 'jmro1975',
            'dbname' => 'inventarioRepuestos'
        ];

        try {
            $this->pdo = new PDO("mysql:host=" . $config['servername'] . ";dbname=" . $config['dbname'] . ";charset=utf8mb4", $config['username'], $config['password']);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error de conexión a la base de datos: " . $e->getMessage());
        }

        $this->personaRepository = new PersonaRepository($this->pdo);

        // Smarty setup
        $this->smarty = new \Smarty\Smarty();
        $this->smarty->setTemplateDir('/templates');
        $this->smarty->setCompileDir('/templates_c');
    }

    public function showLoginForm($message = null)
    {
        $this->smarty->assign('message', $message);
        $this->smarty->display('login.tpl');
    }

    public function authenticate()
    {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $persona = $this->personaRepository->findByUsername($username);

        if ($persona && password_verify($password, $persona->getPassword())) {
            session_start();
            $_SESSION['user_id'] = $persona->getId();
            $_SESSION['username'] = $persona->getUsername();
            $_SESSION['role'] = $persona->getRole();

            if ($persona->getRole() === 'admin') {
                header('Location: /home'); // Redirect admin to home or admin dashboard
            } else {
                header('Location: /home'); // Redirect client to home or client dashboard
            }
            exit();
        } else {
            $this->showLoginForm("Usuario o contraseña incorrectos.");
        }
    }

    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();
        header('Location: /login');
        exit();
    }
}
