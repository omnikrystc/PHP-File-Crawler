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

require_once( 'php_file_crawler/includes/Observer.interface.php' );

/**
 * Observable interface, using the Observer design pattern
 */
interface Observable {

	/**
	 * Attach an Observer
	 * @param Observer $observer
	 */
	public function attach( Observer $observer );

	/**
	 * Detach an Observer
	 * @param Observer $observer
	 */
	public function detach( Observer $observer );

	/**
	 * Notify all attached Observers
	 */
	public function notify();

}
