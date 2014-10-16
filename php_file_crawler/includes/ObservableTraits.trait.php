<?php
/**
 * PHP-File-Crawler
 *
 * @author     Thomas Robertson <tom@omnikrys.com>
 * @version    1.0
 * @package    php-file-crawler
 * @subpackage includes
 * @link       https://github.com/omnikrystc/PHP-File-Crawler
 */
namespace php_file_crawler\includes;

trait ObservableTraits {
	/**
	 * The observers to this observable
	 * @var \SplObjectStorage
	 */
	private $observers;

	/**
	 * Attach an Observer
	 * @param includes\Observer $observer the observer to attach
	 */
	public function attach( Observer $observer ) {
		$this->observers->attach( $observer );
	}

	/**
	 * Detach an Observer
	 * @param includes\Observer $observer the observer to detach
	 */
	public function detach( Observer $observer ) {
		$this->observers->detach( $observer );
	}

	/**
	 * Notify the Observer(s)
	 */
	public function notify() {
		foreach ( $this->observers as $observer ) {
			$observer->update( $this );
		}
	}

}
