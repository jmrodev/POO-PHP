<?php

class VentaVista extends Vista
{
    public function showVentas($ventas)
    {
        $this->smarty->assign('ventas', $ventas);
        $this->smarty->display('ventas.tpl');
    }

    public function displayForm($message = "", $isSuccess = true, $venta = null, $repuestos = [], $usuarios = [])
    {
        $this->smarty->assign('message', $message);
        $this->smarty->assign('isSuccess', $isSuccess);
        $this->smarty->assign('venta', $venta);
        $this->smarty->assign('repuestos', $repuestos);
        $this->smarty->assign('usuarios', $usuarios);
        $this->smarty->display('form_venta.tpl');
    }

    public function displayConfirmDelete($venta)
    {
        $this->smarty->assign('venta', $venta);
        $this->smarty->display('confirm_delete_venta.tpl');
    }
}
