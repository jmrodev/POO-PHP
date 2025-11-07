<?php

namespace Smarty;

class Data
{
    public const SCOPE_LOCAL = 1;
    public const SCOPE_PARENT = 2;
    public const SCOPE_TPL_ROOT = 4;
    public const SCOPE_ROOT = 8;
    public const SCOPE_SMARTY = 16;
    public const SCOPE_GLOBAL = 32;
    protected $smarty = null;
    public $tpl_vars = [];
    public $parent = null;
    public $config_vars = [];
    private $_var_stack = [];
    private $_config_stack = [];
    protected $defaultScope = self::SCOPE_LOCAL;
    public function __construct($_parent = null, $smarty = null, $name = null)
    {
        $this->smarty = $smarty;
        if (is_object($_parent)) {
            $this->parent = $_parent;
        } elseif (is_array($_parent)) {
            foreach ($_parent as $_key => $_val) {
                $this->assign($_key, $_val);
            }
        } elseif ($_parent !== null) {
            throw new Exception('Wrong type for template variables');
        }
    } public function assign($tpl_var, $value = null, $nocache = false, $scope = null)
    {
        if (is_array($tpl_var)) {
            foreach ($tpl_var as $_key => $_val) {
                $this->assign($_key, $_val, $nocache, $scope);
            } return $this;
        } switch ($scope ?? $this->getDefaultScope()) {
            case self::SCOPE_GLOBAL: case self::SCOPE_SMARTY: $this->getSmarty()->assign($tpl_var, $value);
                break;
            case self::SCOPE_TPL_ROOT: $ptr = $this;
                while (isset($ptr->parent) && ($ptr->parent instanceof Template)) {
                    $ptr = $ptr->parent;
                } $ptr->assign($tpl_var, $value);
                break;
            case self::SCOPE_ROOT: $ptr = $this;
                while (isset($ptr->parent) && !($ptr->parent instanceof Smarty)) {
                    $ptr = $ptr->parent;
                } $ptr->assign($tpl_var, $value);
                break;
            case self::SCOPE_PARENT: if ($this->parent) {
                $this->parent->assign($tpl_var, $value);
            } else {
                $this->assign($tpl_var, $value);
            } break;
            case self::SCOPE_LOCAL: default: if (isset($this->tpl_vars[$tpl_var])) {
                $this->tpl_vars[$tpl_var]->setValue($value);
                if ($nocache) {
                    $this->tpl_vars[$tpl_var]->setNocache(true);
                }
            } else {
                $this->tpl_vars[$tpl_var] = new Variable($value, $nocache);
            }
        } return $this;
    } public function append($tpl_var, $value = null, $merge = false, $nocache = false)
    {
        if (is_array($tpl_var)) {
            foreach ($tpl_var as $_key => $_val) {
                $this->append($_key, $_val, $merge, $nocache);
            }
        } else {
            $newValue = $this->getValue($tpl_var) ?? [];
            if (!is_array($newValue)) {
                $newValue = (array) $newValue;
            } if ($merge && is_array($value)) {
                foreach ($value as $_mkey => $_mval) {
                    $newValue[$_mkey] = $_mval;
                }
            } else {
                $newValue[] = $value;
            } $this->assign($tpl_var, $newValue, $nocache);
        } return $this;
    } public function assignGlobal($varName, $value = null, $nocache = false)
    {
        trigger_error(__METHOD__ . " is deprecated. Use \\Smarty\\Smarty::assign() to assign a variable " . " at the Smarty level.", E_USER_DEPRECATED);
        return $this->getSmarty()->assign($varName, $value, $nocache);
    } public function getTemplateVars($varName = null, $searchParents = true)
    {
        if (isset($varName)) {
            return $this->getValue($varName, $searchParents);
        } return array_merge($this->parent && $searchParents ? $this->parent->getTemplateVars() : [], array_map(function (Variable $var) { return $var->getValue(); }, $this->tpl_vars));
    } public function _getVariable($varName, $searchParents = true, $errorEnable = true)
    {
        trigger_error('Using ::_getVariable() to is deprecated and will be ' . 'removed in a future release. Use getVariable() instead.', E_USER_DEPRECATED);
        return $this->getVariable($varName, $searchParents, $errorEnable);
    } public function getVariable($varName, $searchParents = true, $errorEnable = true)
    {
        if (isset($this->tpl_vars[$varName])) {
            return $this->tpl_vars[$varName];
        } if ($searchParents && $this->parent) {
            return $this->parent->getVariable($varName, $searchParents, $errorEnable);
        } if ($errorEnable && $this->getSmarty()->error_unassigned) {
            $x = $$varName;
        } return new UndefinedVariable();
    } public function setVariable($varName, Variable $variableObject)
    {
        $this->tpl_vars[$varName] = $variableObject;
    } public function hasVariable($varName): bool
    {
        return !($this->getVariable($varName, true, false) instanceof UndefinedVariable);
    } public function getValue($varName, $searchParents = true)
    {
        $variable = $this->getVariable($varName, $searchParents);
        return isset($variable) ? $variable->getValue() : null;
    } public function assignConfigVars($new_config_vars, array $sections = [])
    {
        foreach ($new_config_vars['vars'] as $variable => $value) {
            if ($this->getSmarty()->config_overwrite || !isset($this->config_vars[$variable])) {
                $this->config_vars[$variable] = $value;
            } else {
                $this->config_vars[$variable] = array_merge((array)$this->config_vars[$variable], (array)$value);
            }
        } foreach ($sections as $tpl_section) {
            if (isset($new_config_vars['sections'][$tpl_section])) {
                foreach ($new_config_vars['sections'][$tpl_section]['vars'] as $variable => $value) {
                    if ($this->getSmarty()->config_overwrite || !isset($this->config_vars[$variable])) {
                        $this->config_vars[$variable] = $value;
                    } else {
                        $this->config_vars[$variable] = array_merge((array)$this->config_vars[$variable], (array)$value);
                    }
                }
            }
        }
    } public function getSmarty()
    {
        return $this->smarty;
    } public function clearAssign($tpl_var)
    {
        if (is_array($tpl_var)) {
            foreach ($tpl_var as $curr_var) {
                unset($this->tpl_vars[ $curr_var ]);
            }
        } else {
            unset($this->tpl_vars[ $tpl_var ]);
        } return $this;
    } public function clearAllAssign()
    {
        $this->tpl_vars = [];
        return $this;
    } public function clearConfig($name = null)
    {
        if (isset($name)) {
            unset($this->config_vars[ $name ]);
        } else {
            $this->config_vars = [];
        } return $this;
    } public function getConfigVariable($varName)
    {
        if (isset($this->config_vars[$varName])) {
            return $this->config_vars[$varName];
        } $returnValue = $this->parent ? $this->parent->getConfigVariable($varName) : null;
        if ($returnValue === null && $this->getSmarty()->error_unassigned) {
            throw new Exception("Undefined variable $varName");
        } return $returnValue;
    } public function hasConfigVariable($varName): bool
    {
        try {
            return $this->getConfigVariable($varName) !== null;
        } catch (Exception $e) {
            return false;
        }
    } public function getConfigVars($varname = null)
    {
        if (isset($varname)) {
            return $this->getConfigVariable($varname);
        } return array_merge($this->parent ? $this->parent->getConfigVars() : [], $this->config_vars);
    } public function configLoad($config_file, $sections = null)
    {
        $template = $this->getSmarty()->doCreateTemplate($config_file, null, null, $this, null, null, true);
        $template->caching = Smarty::CACHING_OFF;
        $template->assign('sections', (array) $sections ?? []);
        $template->fetch();
        return $this;
    } protected function setDefaultScope(int $scope)
    {
        $this->defaultScope = $scope;
    } public function getDefaultScope(): int
    {
        return $this->defaultScope;
    } public function getParent()
    {
        return $this->parent;
    } public function setParent($parent): void
    {
        $this->parent = $parent;
    } public function pushStack(): void
    {
        $stackList = [];
        foreach ($this->tpl_vars as $name => $variable) {
            $stackList[$name] = clone $variable;
        } $this->_var_stack[] = $this->tpl_vars;
        $this->tpl_vars = $stackList;
        $this->_config_stack[] = $this->config_vars;
    } public function popStack(): void
    {
        $this->tpl_vars = array_pop($this->_var_stack);
        $this->config_vars = array_pop($this->_config_stack);
    }
}
