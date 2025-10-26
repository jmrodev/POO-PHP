<?php
    require_once(SERVER_PATH."/libs/smarty-5.4.2/libs/Smarty.class.php");

    class Vista {

        protected $smarty;

        public function __construct()
        {
            $this->smarty = new Smarty\Smarty; 
            $this->smarty->setTemplateDir('./templates')
                 ->setCompileDir('./templates_c');
            $this->smarty->assign('BASE_URL', BASE_URL);

        }

        function error($message) {
            die($message);
        }

    }