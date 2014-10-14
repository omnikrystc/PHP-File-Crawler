<?php
/**
 * PHP-File-Crawler
 * 
 * @author     Thomas Robertson <tom@omnikrys.com>
 * @version    1.1
 * @package    php-file-crawler
 * @subpackage includes
 * @link       https://github.com/omnikrystc/PHP-File-Crawler
 */
namespace php_file_crawler\includes;

require_once( 'php_file_crawler/includes/Observed.interface.php' );

/**
 * Observer interface, using the Observer design pattern.
 */
interface Observer {
	/**
	 * Required function for the Observer pattern
	 * @param Observed $observed
	 */
	public function update( Observed $observed );
}
