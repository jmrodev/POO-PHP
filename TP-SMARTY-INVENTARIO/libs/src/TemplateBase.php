<?php

namespace Smarty;

abstract class TemplateBase extends Data
{
    public $cache_id = null;
    public $compile_id = null;
    public $caching = \Smarty\Smarty::CACHING_OFF;
    public $compile_check = \Smarty\Smarty::COMPILECHECK_ON;
    public $cache_lifetime = 3600;
    public $tplFunctions = [];
    private $debug;
    public function registerObject($object_name, $object, $allowed_methods_properties = [], $format = true, $block_methods = [])
    {
        $smarty = $this->getSmarty();
        if (!empty($allowed_methods_properties)) {
            foreach ((array)$allowed_methods_properties as $method) {
                if (!is_callable([$object, $method]) && !property_exists($object, $method)) {
                    throw new Exception("Undefined method or property '$method' in registered object");
                }
            }
        } if (!empty($block_methods)) {
            foreach ((array)$block_methods as $method) {
                if (!is_callable([$object, $method])) {
                    throw new Exception("Undefined method '$method' in registered object");
                }
            }
        } $smarty->registered_objects[$object_name] = [$object, (array)$allowed_methods_properties, (bool)$format, (array)$block_methods];
        return $this;
    } public function unregisterObject($object_name)
    {
        $smarty = $this->getSmarty();
        if (isset($smarty->registered_objects[$object_name])) {
            unset($smarty->registered_objects[$object_name]);
        } return $this;
    } public function getCompileCheck(): int
    {
        return $this->compile_check;
    } public function setCompileCheck($compile_check)
    {
        $this->compile_check = (int)$compile_check;
    } public function setCaching($caching)
    {
        $this->caching = (int)$caching;
    } public function setCacheLifetime($cache_lifetime)
    {
        $this->cache_lifetime = $cache_lifetime;
    } public function setCompileId($compile_id)
    {
        $this->compile_id = $compile_id;
    } public function setCacheId($cache_id)
    {
        $this->cache_id = $cache_id;
    } public function createData(?Data $parent = null, $name = null)
    {
        $smarty = $this->getSmarty();
        $dataObj = new Data($parent, $smarty, $name);
        if ($smarty->debugging) {
            $smarty->getDebug()->register_data($dataObj);
        } return $dataObj;
    } public function getDebugTemplate()
    {
        $smarty = $this->getSmarty();
        return $smarty->debug_tpl;
    } public function getDebug(): Debug
    {
        if (!isset($this->debug)) {
            $this->debug = new \Smarty\Debug();
        } return $this->debug;
    } public function getRegisteredObject($object_name)
    {
        $smarty = $this->getSmarty();
        if (!isset($smarty->registered_objects[$object_name])) {
            throw new Exception("'$object_name' is not a registered object");
        } if (!is_object($smarty->registered_objects[$object_name][0])) {
            throw new Exception("registered '$object_name' is not an object");
        } return $smarty->registered_objects[$object_name][0];
    } public function getLiterals()
    {
        $smarty = $this->getSmarty();
        return (array)$smarty->literals;
    } public function addLiterals($literals = null)
    {
        if (isset($literals)) {
            $this->_setLiterals($this->getSmarty(), (array)$literals);
        } return $this;
    } public function setLiterals($literals = null)
    {
        $smarty = $this->getSmarty();
        $smarty->literals = [];
        if (!empty($literals)) {
            $this->_setLiterals($smarty, (array)$literals);
        } return $this;
    } private function _setLiterals(Smarty $smarty, $literals)
    {
        $literals = array_combine($literals, $literals);
        $error = isset($literals[$smarty->getLeftDelimiter()]) ? [$smarty->getLeftDelimiter()] : [];
        $error = isset($literals[$smarty->getRightDelimiter()]) ? $error[] = $smarty->getRightDelimiter() : $error;
        if (!empty($error)) {
            throw new Exception('User defined literal(s) "' . $error . '" may not be identical with left or right delimiter');
        } $smarty->literals = array_merge((array)$smarty->literals, (array)$literals);
    } public function registerClass($class_name, $class_impl)
    {
        $smarty = $this->getSmarty();
        if (!class_exists($class_impl)) {
            throw new Exception("Undefined class '$class_impl' in register template class");
        } $smarty->registered_classes[$class_name] = $class_impl;
        return $this;
    } public function registerDefaultConfigHandler($callback)
    {
        $smarty = $this->getSmarty();
        if (is_callable($callback)) {
            $smarty->default_config_handler_func = $callback;
        } else {
            throw new Exception('Default config handler not callable');
        } return $this;
    } public function registerDefaultTemplateHandler($callback)
    {
        $smarty = $this->getSmarty();
        if (is_callable($callback)) {
            $smarty->default_template_handler_func = $callback;
        } else {
            throw new Exception('Default template handler not callable');
        } return $this;
    } public function registerResource($name, \Smarty\Resource\BasePlugin $resource_handler)
    {
        $smarty = $this->getSmarty();
        $smarty->registered_resources[$name] = $resource_handler;
        return $this;
    } public function unregisterResource($type)
    {
        $smarty = $this->getSmarty();
        if (isset($smarty->registered_resources[$type])) {
            unset($smarty->registered_resources[$type]);
        } return $this;
    } public function setDebugTemplate($tpl_name)
    {
        $smarty = $this->getSmarty();
        if (!is_readable($tpl_name)) {
            throw new Exception("Unknown file '{$tpl_name}'");
        } $smarty->debug_tpl = $tpl_name;
        return $this;
    }
}
