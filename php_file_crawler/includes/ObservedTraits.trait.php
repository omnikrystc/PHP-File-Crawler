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

}
