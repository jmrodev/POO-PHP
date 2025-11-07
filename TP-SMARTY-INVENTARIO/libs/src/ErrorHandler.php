<?php

namespace Smarty;

class ErrorHandler
{
    public $allowUndefinedProperties = true;
    public $allowUndefinedArrayKeys = true;
    public $allowDereferencingNonObjects = true;
    private $previousErrorHandler = null;
    public function activate()
    {
        $this->previousErrorHandler = set_error_handler([$this, 'handleError']);
    } public function deactivate()
    {
        restore_error_handler();
        $this->previousErrorHandler = null;
    } public function handleError($errno, $errstr, $errfile, $errline, $errcontext = [])
    {
        if ($this->allowUndefinedProperties && preg_match('/^(Undefined property)/', $errstr)) {
            return;
        } if ($this->allowUndefinedArrayKeys && preg_match('/^(Undefined index|Undefined array key|Trying to access array offset on)/', $errstr)) {
            return;
        } if ($this->allowDereferencingNonObjects && preg_match('/^Attempt to read property ".+?" on/', $errstr)) {
            return;
        } return $this->previousErrorHandler ? call_user_func($this->previousErrorHandler, $errno, $errstr, $errfile, $errline, $errcontext) : false;
    }
}
