<?php
/**
 * PHP-File-Crawler
 * 
 * @author     Thomas Robertson <tom@omnikrys.com>
 * @version    1.0
 * @package    php-file-crawler
 * @link       https://github.com/omnikrystc/PHP-File-Crawler
 */
namespace php_file_crawler\includes;

require_once( 'php_file_crawler/includes/Observed.interface.php' );
require_once( 'php_file_crawler/includes/Observer.interface.php' );

/**
 * Observer interface, using the Observer design pattern.
 * 
 * @package    php-file-crawler
 * @subpackage includes
 */
abstract class SimpleObserver implements Observer {
	/**
	 * Array of strings for output after run
	 *  
	 * @var array
	 */
	protected $results;

	/**
	 * Handle the construction here, DRY
	 */	
	public function __construct( Observable $observable ) {
		$observable->attach( $this ); 
		$this->results = array();
	}

	/**
	 * Required function for the Observer pattern, DRY
	 */
	public function update( Observed $observed ) {
		$this->doUpdate( $observed );
	}
	
	/**
	 * Add a new result to the results array
	 * @param string $result
	 */	
	protected function addResult( $result ) {
		if ( ! in_array( $result, $this->results ) ) {
			$this->results[] = $result;
		}
	}
	
	/**
	 * Returns an array of strings (filled by doUpdate presumably)
	 * @return array of strings
	 */	
	public function getList() {
		return $this->results;
	}

	abstract protected function doUpdate( Observed $observed );

}