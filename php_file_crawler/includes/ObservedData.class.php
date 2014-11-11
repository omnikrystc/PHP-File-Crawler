<?php
/**
 * PHP-File-Crawler
 *
 * @author     Thomas Robertson <tom@omnikrys.com>
 * @version    1.2
 * @package    php-file-crawler
 * @subpackage includes
 * @link       https://github.com/omnikrystc/PHP-File-Crawler
 */
namespace php_file_crawler\includes;

require_once( 'php_file_crawler/includes/ObservedTraits.trait.php' );

/**
 * Simple implementation of our Observed interface so I can just pass it to
 * our Observers instead of them accessing the Observer directly
 */
class ObservedData implements  Observed {
	use ObservedTraits;

	/**
	 * the constructor just initializes everything
	 */
	public function __construct() {
		$this->reset( true );
	}

	/**
	 * reset function (depth optional)
	 * @param boolean $reset_depth defaults to FALSE
	 */
	public function reset( $reset_depth = false ) {
		if( $reset_depth ) {
			$this->depth = 0;
		}
		$this->directory = null;
		$this->file_info = null;
		$this->status = null;
	}

	/**
	 * setter for $file_info
	 * @param \SplFileInfo $file_info
	 */
	public function setFileInfo( \SplFileInfo $file_info ) {
		$this->file_info = $file_info;
	}

	/**
	 * clear current $file_info
	 */
	public function clearFileInfo() {
		$this->file_info = null;
	}
	
	/**
	 * is the class property $file_info set and valid
	 * @return boolean
	 */
	public function isFileInfoValid() {
		if ( is_null( $this->file_info ) ) {
			return false;
		}
		return true;
	}

	/**
	 * setter for the $status
	 * @param string $status
	 */
	public function setStatus( $status ) {
		$this->status = $status;
	}

	/**
	 * setter for the $directory
	 * @param string $directory
	 */
	public function setDirectory( $directory ) {
		$this->directory = $directory;
	}

	/**
	 * setter for the $depth
	 * @param integer $depth
	 */
	public function setDepth( $depth ) {
		$this->depth = $depth;
	}

	/**
	 * ++ the $depth
	 * @return the new depth
	 */
	public function increaseDepth() {
		return ++$this->depth;
	}

	/**
	 * -- the $depth
	 * @return the new depth
	 */
	public function decreaseDepth() {
		return --$this->depth;
	}
}

