<?php

class LoginVista extends Vista {
    public function displayLoginForm($message = null) {
        $this->smarty->assign('message', $message);
        $this->smarty->display('login.tpl');
    }
}

?>
