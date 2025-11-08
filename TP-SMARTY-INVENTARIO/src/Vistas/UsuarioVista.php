<?php

class UsuarioVista extends Vista
{
    public function showUsuarios($usuarios)
    {
        $this->smarty->assign('usuarios', $usuarios);
        $this->smarty->display('usuarios.tpl');
    }

    public function displayForm($message = "", $isSuccess = true, $usuario = null)
    {
        $this->smarty->assign('message', $message);
        $this->smarty->assign('isSuccess', $isSuccess);
        $this->smarty->assign('usuario', $usuario);
        $this->smarty->display('form_usuario.tpl');
    }

    public function displayConfirmDelete($usuario)
    {
        $this->smarty->assign('usuario', $usuario);
        $this->smarty->display('confirm_delete_usuario.tpl');
    }
}
