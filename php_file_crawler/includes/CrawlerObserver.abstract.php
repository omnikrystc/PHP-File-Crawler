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
abstract class CrawlerObserver implements Observer {

	/**
	 * Handle the construction here, DRY
	 */	
	public function __construct( Observable $observable ) {
		$observable->attach( $this ); 
	}

	/**
	 * Required function for the Observer pattern, DRY
	 */
	public function update( Observed $observed ) {
		$this->doUpdate( $observed );
	}

	abstract protected function doUpdate( Observed $observed );

}
