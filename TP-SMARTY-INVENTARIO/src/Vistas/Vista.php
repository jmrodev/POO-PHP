<?php
class Vista {

        protected $smarty;

        public function __construct()
        {
            $this->smarty = new \Smarty\Smarty; 
            $this->smarty->setTemplateDir(SERVER_PATH . '/templates')
                 ->setCompileDir(SERVER_PATH . '/templates_c');
            $this->smarty->assign('BASE_URL', BASE_URL);

        }

        function error($message) {
            die($message);
        }

    }
