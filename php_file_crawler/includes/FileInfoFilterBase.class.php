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

require_once( 'includes/FileInfoFilter.interface.php' );

/**
 * abstract to DRY most of the filtering implementation
 */
class FileInfoFilterBase implements FileInfoFilter {

	/**
	 * file/dir accessed before
	 * @param int @atime_before
	 */
	protected $atime_before;

	/**
	 * file/dir accessed after
	 * @param int @atime_after
	 */
	protected $atime_after;

	/**
	 * file/dir created before
	 * @param int @ctime_before
	 */
	protected $ctime_before;

	/**
	 * file/dir created after
	 * @param int @ctime_after
	 */
	protected $ctime_after;

	/**
	 * file/dir modified before
	 * @param int @mtime_before
	 */
	protected $mtime_before;

	/**
	 * file/dir modified after
	 * @param int @mtime_after
	 */
	protected $mtime_after;

	/**
	 * is a linked file/dir
	 * @param boolean $is_link
	 */
	protected $is_link;

	/**
	 * regex patterns to match
	 * @param array $link
	 */
	protected $regexes;

	/**
	 * constructor for class
	 */
	public function __construct() {
		$this->regexes = array();
	}

	/**
	 * setter for $atime_before
	 * @param int $atime_before
	 */
	public function setATimeBefore( $atime_before ) {
		$this->atime_before = $atime_before;
	}

	/**
	 * getter for $atime_before
	 * @return int $atime_before
	 */
	public function getATimeBefore() {
		return $this->atime_before;
	}

	/**
	 * setter for $atime_after
	 * @param int $atime_after
	 */
	public function setATimeAfter( $atime_after ) {
		$this->atime_after = $atime_after;
	}

	/**
	 * getter for $atime_after
	 * @return int $atime_after
	 */
	public function getATimeAfter() {
		return $this->atime_after;
	}

	/**
	 * setter for $ctime_before
	 * @param int $ctime_before
	 */
	public function setCTimeBefore( $ctime_before ) {
		$this->ctime_before = $ctime_before;
	}

	/**
	 * getter for $ctime_before
	 * @return int $ctime_before
	 */
	public function getCTimeBefore() {
		return $this->ctime_before;
	}

	/**
	 * setter for $ctime_after
	 * @param int $ctime_after
	 */
	public function setCTimeAfter( $ctime_after ) {
		$this->ctime_after = $ctime_after;
	}

	/**
	 * getter for $ctime_after
	 * @return int $ctime_after
	 */
	public function getCTimeAfter() {
		return $this->ctime_after;
	}

	/**
	 * setter for $mtime_before
	 * @param int $mtime_before
	 */
	public function setMTimeBefore( $mtime_before ) {
		$this->mtime_before = $mtime_before;
	}

	/**
	 * getter for $mtime_before
	 * @return int $mtime_before
	 */
	public function getMTimeBefore() {
		return $this->mtime_before;
	}

	/**
	 * setter for $mtime_after
	 * @param int $mtime_after
	 */
	public function setMTimeAfter( $mtime_after ) {
		$this->mtime_after = $mtime_after;
	}

	/**
	 * getter for $mtime_after
	 * @return int $mtime_after
	 */
	public function getMTimeAfter() {
		return $this->mtime_after;
	}

	/**
	 * setter for $is_link
	 * @param boolean $is_link
	 */
	public function setIsLink( $is_link ) {
		$this->is_link = $is_link;
	}

	/**
	 * getter for $is_link
	 * @return boolean $is_link
	 */
	public function getIsLink() {
		return $this->is_link;
	}

	/**
	 * setter for $regexes
	 * @param array $regexes
	 */
	public function setRegExes( $regexes ) {
		if( ! is_array( $regexes ) ) {
			$this->regexes = $regexes;
		}
	}

	/**
	 * getter for $regexes
	 * @return boolean $regexes
	 */
	public function getRegExes() {
		return $this->regexes;
	}

	/**
	 * adds a regular expression to be matched against
	 * @param string $new_regex
	 */
	public function addRegEx( $regex ) {
		if( ! in_array($regex, $this->regexes ) ) {
			$this->regexes[] = $new_regex;
		}
	}

	/**
	 * removes a regular expression to be matched against
	 * @param string $regex
	 */
	public function removeRegEx( $regex ) {
		$this->regexes = array_filter(
			$this->regexes,
			function( $a ) use ( $regex ) { return ( ! ( $a === $regex )); }
		);
	}

	public function isFiltered( \SplFileInfo $file_info ) {

	}
}
