<?php
/**
 * PHP-File-Crawler
 *
 * @author     Thomas Robertson <tom@omnikrys.com>
 * @version    1.0
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

	public function testATimeBefore() {
		$setter = $this->getRandomTime();
		$this->filter->setATimeBefore( $setter );
		$getter = $this->filter->getATimeBefore();
		$this->assertEquals( $getter, $setter );
	}

	public function testATimeAfter() {
		$setter = $this->getRandomTime();
		$this->filter->setATimeAfter( $setter );
		$getter = $this->filter->getATimeAfter();
		$this->assertEquals( $getter, $setter );
	}

	public function testCTimeBefore() {
		$setter = $this->getRandomTime();
		$this->filter->setCTimeBefore( $setter );
		$getter = $this->filter->getCTimeBefore();
		$this->assertEquals( $getter, $setter );
	}

	public function testCTimeAfter() {
		$setter = $this->getRandomTime();
		$this->filter->setCTimeAfter( $setter );
		$getter = $this->filter->getCTimeAfter();
		$this->assertEquals( $getter, $setter );
	}

	public function testMTimeBefore() {
		$setter = $this->getRandomTime();
		$this->filter->setMTimeBefore( $setter );
		$getter = $this->filter->getMTimeBefore();
		$this->assertEquals( $getter, $setter );
	}

	public function testMTimeAfter() {
		$setter = $this->getRandomTime();
		$this->filter->setMTimeAfter( $setter );
		$getter = $this->filter->getMTimeAfter();
		$this->assertEquals( $getter, $setter );
	}

}
