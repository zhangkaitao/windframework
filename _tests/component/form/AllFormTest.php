<?php
/**
 * @author xiaoxia xu <x_824@sina.com> 2011-1-13
 * @link http://www.phpwind.com
 * @copyright Copyright &copy; 2003-2110 phpwind.com
 * @license 
 */

include ('component/form/WindActionFormTest.php');
include ('component/form/WindFormFilterTest.php');

class AllFormTest {
	public static function main() {
		PHPUnit_TextUI_TestRunner::run(self::suite());
	}
	
	public static function suite() {
		$suite = new PHPUnit_Framework_TestSuite('AllFormTest_Suite');
		$suite->addTestSuite('WindActionFormTest');
		$suite->addTestSuite('WindFormFilterTest');
		return $suite;
	}
}