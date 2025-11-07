<?php

class ClienteVista extends Vista
{
    public function showClientes($clientes)
    {
        $this->smarty->assign('clientes', $clientes);
        $this->smarty->display('clientes.tpl');
    }

    public function displayForm($message = "", $isSuccess = true, $cliente = null)
    {
        $this->smarty->assign('message', $message);
        $this->smarty->assign('isSuccess', $isSuccess);
        $this->smarty->assign('cliente', $cliente);
        $this->smarty->display('form_cliente.tpl');
    }

    public function displayConfirmDelete($cliente)
    {
        $this->smarty->assign('cliente', $cliente);
        $this->smarty->display('confirm_delete_cliente.tpl');
    }
}
