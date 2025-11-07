<?php

namespace Smarty;

class Exception extends \Exception
{
    public function __toString()
    {
        return ' --> Smarty: ' . $this->message . ' <-- ';
    }
}
