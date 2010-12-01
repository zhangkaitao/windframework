<?php
/**
 * @author Qiong Wu <papa0924@gmail.com> 2010-11-12
 * @link http://www.phpwind.com
 * @copyright Copyright &copy; 2003-2110 phpwind.com
 * @license 
 */
L::import('WIND:component.container.WindModule');

/**
 * the last known user to change this file in the repository  <$LastChangedBy: xiaoxia.xuxx $>
 * @author Qiong Wu <papa0924@gmail.com>
 * @version $Id: WindActionForm.php 314 2010-11-26 09:24:29Z xiaoxia.xuxx $ 
 * @package 
 */
abstract class WindActionForm extends WindModule {
	protected $_isValidate = false;
	protected static $instance = array();
	
	/**
	 * 验证方法，调用该方法完成所有验证操作
	 * 执行在继承WindActionForm类的actionForm中，所有以validate结尾的函数
	 */
	public function validation() {
		$object = new ReflectionClass(get_class($this));
		$validationMethods = $object->getMethods(ReflectionMethod::IS_PUBLIC);
		foreach ($validationMethods as $_value) {
			if (strtolower(substr($_value->name, -8)) == 'validate')
				call_user_func(array($this, $_value->name));
		}
	}
	
	public function addError() {
		
	}
	
	/**
	 * 设置属性值
	 * @param array $_params
	 */
	public function setProperties($_params) {
	   if (!$_params) return false;
	   foreach ($_params as $_key => $_value) {
	   	   $this->$_key = $_value;
	   }
	   return true;
	}
	
	/**
	 * 是否开启验证
	 * @return string
	 */
	public function getIsValidation() {
		return $this->_isValidate;
	}
	
	public function getInstance($class) {
		if (isset(self::$instance[$class])) return self::$instance[$class];
		throw new WindException('The class \'' . $class . '\' don\'t been instance!');
	}
	public function save() {
		self::$instance[get_class($this)] = $this;
	}
}