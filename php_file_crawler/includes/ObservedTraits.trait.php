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
	 * Current depth
	 *
	 * @var integer
	 */
	private $depth;
	/**
	 * Current directory
	 *
	 * @var integer
	 */
	private $directory;
	/**
	 * Current file info
	 *
	 * @var SplFileInfo
	 */
	private $file_info;
	/**
	 * Current status
	 *
	 * @var string
	 */
	private $status;

	/**
	 * Observed implementation
	 *
	 * @return string
	 */
	public function getDirectory() {
		return $this->directory;
	}

	/**
	 * Observed implementation
	 *
	 * @return integer
	 */
	public function getDepth() {
		return $this->depth;
	}

	/**
	 * Observed implementation
	 *
	 * @return \SplFileInfo
	 */
	public function getFileInfo() {
		return $this->file_info;
	}

	/**
	 * Observed implementation
	 *
	 * @return string
	 */
	public function getStatus() {
		return $this->status;
	}
}
