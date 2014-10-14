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

/**
 * Observer interface, using the Observer design pattern.
 * 
 * @package    php-file-crawler
 * @subpackage includes
 */
interface Observer {
	/**
	 * Required function for the Observer pattern
	 */
	public function update( Observed $observed );
}
