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

/**
 * reusable implementation of Observed interface
 */
trait ObservedTraits {

	/**
	 * depth
	 * @var integer
	 */
	private $depth;

	/**
	 * directory
	 * @var integer
	 */
	private $directory;

	/**
	 * file info
	 * @var SplFileInfo
	 */
	private $file_info;

	/**
	 * status (one of the Observed::STATUS_* variables)
	 * @var string
	 */
	private $status;

	/**
	 * getter for $directory
	 * @return string
	 */
	public function getDirectory() {
		return $this->directory;
	}

	/**
	 * getter for $depth
	 * @return integer
	 */
	public function getDepth() {
		return $this->depth;
	}

	/**
	 * getter for $file_info
	 * @return \SplFileInfo
	 */
	public function getFileInfo() {
		return $this->file_info;
	}

	/**
	 * getter for $status
	 * @return string
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * getter for the scan directory
	 * @return string
	 */
	public function getScanDirectory() {
		// need this negative for the slice
		$depth = -($this->depth - 1);
		// if we are home just return
		if ( $depth === 0 ) {
			return $this->directory;			
		}
		// remove trailing slash(es)
		$path = rtrim( $this->directory, DIRECTORY_SEPARATOR);
		// explode the path into an array
		$path_array = explode( DIRECTORY_SEPARATOR, $path );
		// slide off the end to depth
		$sliced_array = array_slice( $path_array, 0 , $depth );
		// return it as a string 
		return implode(DIRECTORY_SEPARATOR, $sliced_array );
	}

}
