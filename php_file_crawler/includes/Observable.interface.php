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

require_once( 'php_file_crawler/includes/Observer.interface.php' );

/**
 * Observable interface, using the Observer design pattern
 * 
 * @package    php-file-crawler
 * @subpackage includes
 */
interface Observable {
	/**
	 * Attach an Observer 
	 * Required function for the Observer pattern
	 */
	public function attach( Observer $observer );
	/**
	 * Detach an Observer
	 * Required function for the Observer pattern
	 */
	public function detach( Observer $observer );
	/**
	 * Notify all attached Observers
	 * Required function for the Observer pattern
	 */
	public function notify();
}
