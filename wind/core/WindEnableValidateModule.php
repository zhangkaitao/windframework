<?php
/**
 * @author Qiong Wu <papa0924@gmail.com> 2011-1-7
 * @link http://www.phpwind.com
 * @copyright Copyright &copy; 2003-2110 phpwind.com
 * @license 
 */
L::import('WIND:core.WindModule');
/**
 * 启用了自动验证器的WindModule基类
 * 注入：验证器/异常处理器
 * the last known user to change this file in the repository  <$LastChangedBy$>
 * @author Qiong Wu <papa0924@gmail.com>
 * @version $Id$ 
 * @package 
 */
abstract class WindEnableValidateModule extends WindModule
{
    protected $_validatorClass = 'WIND:component.utility.WindValidator';
    private $_validator = null;
    private $_errors = array();
    private $_defaultMessage = 'the field validate fail.';

    
    /**
     * @return the $_errors
     */
    public function getErrors ()
    {
        return $this->_errors;
    }
    /**
     * 返回验证规则
     * 
     * validator : required/not-required
     * @return multitype:multitype:string  
     */
    protected function validateRules ()
    {
        return array();
    }
    /**
     * 组装测试规则
     * @param string $field	| 验证字段名称
     * @param string $validator | 验证方法
     * @param array $args | 参数
     * @param string $default	| 默认值
     * @param string $message	| 错误信息
     * @return array
     */
    protected function buildValidateRule ($field, $validator, $args = array(), $default = null, $message = '')
    {
        return array('field' => $field, 'validator' => $validator, 
        			 'args' => (array) $args, 'default' => $default, 
        			 'message' => ($message ? $message : $this->_defaultMessage));
    }
    /**
     * 验证方法
     * 
     * @param array|WindModule $input
     */
    public function validate (&$input)
    {
        if (is_array($input))
            $this->validateArray($input);
        elseif (is_object($input))
            $this->validateObject($input);
    }
    /**
     * 验证数组类型的输入
     * @param array $input
     */
    private function validateArray (&$input)
    {
        $rules = $this->validateRules();
        foreach ((array) $rules as $rule) {
            $_input = isset($input[$rule['field']]) ? $input[$rule['field']] : '';
            $arg = (array) $rule['args'];
            array_unshift($arg, $_input);
            if (call_user_func_array(array($this->getValidator(), $rule['validator']), $arg) !== false) continue;
            if ($rule['default'] === null) {
                $this->_errors[$_input] = $rule['message']; 
                continue;
            }
            $input[$rule['field']] = $rule['default'];
        }
    }
    /**
     * 验证对象类型的输入
     * @param object $input 传入需要验证的数据
     */
    private function validateObject (&$input)
    {
        $rules = $this->validateRules();
        foreach ((array) $rules as $rule) {
            $key = $rule['field'];
            $_input = $input->$key ? $input->$key : '';
            $arg = (array) $rule['args'];
            array_unshift($arg, $_input);
            if (call_user_func_array(array($this->getValidator(), $rule['validator']), $arg) !== false) continue;
            if ($rule['default'] === null) {
                $this->_errors[$_input] = $rule['message']; 
                continue;
            }
            $input->$key = $rule['default'];
        }
    }
    /**
     * @param WindValidator $validator
     */
    protected function setValidator ($validator)
    {
        $this->_validator = $validator;
    }
    /**
     * 返回验证器
     * @return WindValidator 
     */
    protected function getValidator ()
    {
        if ($this->_validator === null) {
            $_className = L::import($this->_validatorClass);
            L::import('WIND:core.factory.WindFactory');
            $this->_validator = WindFactory::createInstance($_className);
            if ($this->_validator === null)
                throw new WindException('validator', 
                WindException::ERROR_RETURN_TYPE_ERROR);
        }
        return $this->_validator;
    }
}