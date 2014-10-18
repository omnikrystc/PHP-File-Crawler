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

/**
 * unit tests for includes\FileInfoFilterBase class
 */
class FileInfoFilterBaseTest extends \PHPUnit_Framework_TestCase {
	/**
	 * the object we are testing
	 * @var \php_file_crawler\includes\FileInfoFilterBase $filter
	 */
	private $filter;

	/**
	 * setup
	 */
	public function setup() {
		$this->filter = new \php_file_crawler\includes\FileInfoFilterBase();
	}

	/**
	 * tear down
	 */
	public function tearDown() {
	}

	/**
	 * the time properties all store ints. For testing they all need to use
	 * unique values to test if they are mixing up. Using randoms for this.
	 * @return int
	 */
	private function getRandomTime() {
		return (time() - rand(1, 99999));
	}

	/**
	 * generic setter/getter tester
	 * @param string $set_func setter function name
	 * @param string $get_func getter function name
	 * @param [mixed] $value value to set
	 * @param [mixed] $result value to test against get
	 */
	private function doSetGetTest( $set_func, $get_func, $value, $result ) {
		$this->filter->$set_func( $value );
		$getter = $this->filter->$get_func();
		$this->assertEquals( $getter, $result );
	}

	/**
	 * helper to do a full test of one of the time setter/getter pairs
	 * @param string $set_func setter function name
	 * @param string $get_func getter function name
	 */
	private function doTimeTest( $set_func, $get_func ) {
		$value = $this->getRandomTime();
		$this->doSetGetTest( $set_func, $get_func, $value, $value );
		// sets a positive int or 0 (FALSE) otherwise
		$this->doSetGetTest( $set_func, $get_func, null, 0 );
		$this->doSetGetTest( $set_func, $get_func, -100, 0 );
		$this->doSetGetTest( $set_func, $get_func, FALSE, 0 );
	}

	/**
	 * test set/get of $atime_before
	 */
	public function testATimeBefore() {
		$this->doTimeTest('setATimeBefore', 'getATimeBefore');
	}

	/**
	 * test set/get of $atime_after
	 */
	public function testATimeAfter() {
		$this->doTimeTest('setATimeAfter', 'getATimeAfter');
	}

	/**
	 * test set/get of $ctime_before
	 */
	public function testCTimeBefore() {
		$this->doTimeTest('setCTimeBefore', 'getCTimeBefore');
	}

	/**
	 * test set/get of $ctime_after
	 */
	public function testCTimeAfter() {
		$this->doTimeTest('setCTimeAfter', 'getCTimeAfter');
	}

	/**
	 * test set/get of $mtime_before
	 */
	public function testMTimeBefore() {
		$this->doTimeTest('setMTimeBefore', 'getMTimeBefore');
	}

	/**
	 * test set/get of $mtime_after
	 */
	public function testMTimeAfter() {
		$this->doTimeTest('setMTimeAfter', 'getMTimeAfter');
	}

	/**
	 * test set/get of $is_link
	 */
	public function testIsLink() {
		$this->doSetGetTest( 'setIsLink', 'getIsLink', TRUE, TRUE );
		$this->doSetGetTest( 'setIsLink', 'getIsLink', FALSE, FALSE );
		// won't set the value so remains previous value
		$this->doSetGetTest( 'setIsLink', 'getIsLink', 'notbool', FALSE );
		$this->doSetGetTest( 'setIsLink', 'getIsLink', null, null );
		// won't set the value so remains previous value
		$this->doSetGetTest( 'setIsLink', 'getIsLink', 'notbool', null );
	}

	/**
	 * test set/get/add/remove of $regexes
	 */
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
