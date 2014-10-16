<?php
/**
 * PHP-File-Crawler
 *
 * @author     Thomas Robertson <tom@omnikrys.com>
 * @version    1.4
 * @package    php-file-crawler
 * @subpackage includes
 * @link       https://github.com/omnikrystc/PHP-File-Crawler
 */
namespace php_file_crawler\includes;

require_once( 'php_file_crawler/includes/Observed.interface.php' );
require_once( 'php_file_crawler/includes/Observer.interface.php' );

/**
 * Observer interface, using the Observer design pattern.
 */
abstract class SimpleObserver implements Observer {
	/**
	 * Array of strings for output after run
	 *
	 * @var \SplObjectStorage
	 */
	protected $results;

	/**
	 * Handle the construction here, DRY
	 * @param Observable $observable
	 */
	public function __construct( Observable $observable ) {
		$observable->attach( $this );
		$this->results = new \SplObjectStorage();
	}

	/**
	 * Required function for the Observer pattern, DRY
	 * @param Observed $observed
	 */
	public function update( Observed $observed ) {
		$this->doUpdate( $observed );
	}

	/**
	 * Add a new result to the results array
	 * @param string $result
	 */
	protected function addResult( $result ) {
		$this->results->attach( $result );
	}

	/**
	 * Returns a \SplObjectStorage object
	 * @return \SplObjectStorage
	 */
	public function getResults() {
		return $this->results;
	}

	/**
	 * abstract of doUpdate allowing each class to implement its own
	 * @param Observed $observed
	 */
	abstract protected function doUpdate( Observed $observed );
}
