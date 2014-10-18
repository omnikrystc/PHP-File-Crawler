<?php
/**
 * PHP-File-Crawler
 *
 * @author     Thomas Robertson <tom@omnikrys.com>
 * @version    1.1
 * @package    php-file-crawler
 * @subpackage unit-tests
 * @link       https://github.com/omnikrystc/PHP-File-Crawler
 */
namespace php_file_crawler\unit_tests;

require_once( 'includes/FileInfoFilterBase.class.php' );

class FileInfoFilterBaseTest extends \PHPUnit_Framework_TestCase {
	private $filter;

	public function setup() {
		$this->filter = new \php_file_crawler\includes\FileInfoFilterBase();
	}

	public function tearDown() {
	}

	private function getRandomTime() {
		return (time() - rand(1, 99999));
	}

	private function doSetGetTest( $set_func, $get_func, $value, $result ) {
		$this->filter->$set_func( $value );
		$getter = $this->filter->$get_func();
		$this->assertEquals( $getter, $result );
	}

	private function doTimeTest( $set_func, $get_func ) {
		$value = $this->getRandomTime();
		$this->doSetGetTest( $set_func, $get_func, $value, $value );
		// sets a positive int or 0 (FALSE) otherwise
		$this->doSetGetTest( $set_func, $get_func, null, 0 );
		$this->doSetGetTest( $set_func, $get_func, -100, 0 );
		$this->doSetGetTest( $set_func, $get_func, FALSE, 0 );
	}

	public function testATimeBefore() {
		$this->doTimeTest('setATimeBefore', 'getATimeBefore');
	}

	public function testATimeAfter() {
		$this->doTimeTest('setATimeAfter', 'getATimeAfter');
	}

	public function testCTimeBefore() {
		$this->doTimeTest('setCTimeBefore', 'getCTimeBefore');
	}

	public function testCTimeAfter() {
		$this->doTimeTest('setCTimeAfter', 'getCTimeAfter');
	}

	public function testMTimeBefore() {
		$this->doTimeTest('setMTimeBefore', 'getMTimeBefore');
	}

	public function testMTimeAfter() {
		$this->doTimeTest('setMTimeAfter', 'getMTimeAfter');
	}

	public function testIsLink() {
		$this->doSetGetTest( 'setIsLink', 'getIsLink', TRUE, TRUE );
		$this->doSetGetTest( 'setIsLink', 'getIsLink', FALSE, FALSE );
		// won't set the value so remains previous value
		$this->doSetGetTest( 'setIsLink', 'getIsLink', 'notbool', FALSE );
		$this->doSetGetTest( 'setIsLink', 'getIsLink', null, null );
		// won't set the value so remains previous value
		$this->doSetGetTest( 'setIsLink', 'getIsLink', 'notbool', null );
	}


	public function testRegExes() {
		$regex1 = '/\.test1/i';
		$regex2 = '/\.test2/i';
		$regex3 = '/\.test3/i';
		$setter = array( $regex1, $regex2, $regex3 );
		$this->doSetGetTest( 'setRegExes', 'getRegExes', $setter, $setter );
		// removing a regex manually
		$this->filter->removeRegEx( $regex1 );
		$this->assertEquals( count( $this->filter->getRegExes() ), 2 );
		// trying to remove one that isn't there
		$this->filter->removeRegEx( 'notthere' );
		$this->assertEquals( count( $this->filter->getRegExes() ), 2 );
		// trying to add one that is already there
		$this->filter->addRegEx( $regex2 );
		$this->assertEquals( count( $this->filter->getRegExes() ), 2 );
		// setting to other than array
		$this->filter->setRegExes( null );
		$this->assertEquals( count( $this->filter->getRegExes() ), 2 );
		// setting to empty
		$this->filter->setRegExes( array() );
		$this->assertEquals( count( $this->filter->getRegExes() ), 0 );
		// adding them back
		$this->filter->addRegEx( $regex1 );
		$this->assertEquals( count( $this->filter->getRegExes() ), 1 );
		// adding them back
		$this->filter->addRegEx( $regex2 );
		$this->assertEquals( count( $this->filter->getRegExes() ), 2 );
		// adding them back
		$this->filter->addRegEx( $regex3 );
		$this->assertEquals( count( $this->filter->getRegExes() ), 3 );
	}

}
