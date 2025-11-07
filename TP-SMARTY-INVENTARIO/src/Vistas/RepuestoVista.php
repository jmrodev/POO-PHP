<?php

class RepuestoVista extends Vista
{
    public function showRepuestos($repuestos)
    {
        $this->smarty->assign('repuestos', $repuestos);
        $this->smarty->display('repuestos.tpl');
    }

    public function displayForm($message = "", $isSuccess = true, $repuesto = null)
    {
        $this->smarty->assign('message', $message);
        $this->smarty->assign('isSuccess', $isSuccess);
        $this->smarty->assign('repuesto', $repuesto);
        $this->smarty->display('form_repuesto.tpl');
    }

    public function displayConfirmDelete($repuesto)
    {
        $this->smarty->assign('repuesto', $repuesto);
        $this->smarty->display('confirm_delete_repuesto.tpl');
    }

    public function displayDetail($repuesto)
    {
        $this->smarty->assign('repuesto', $repuesto);
        $this->smarty->display('repuesto_detail.tpl');
    }
}
