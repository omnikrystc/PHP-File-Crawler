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

require_once( 'php_file_crawler/includes/FileInfoFilter.interface.php' );

/**
 * abstract to DRY most of the filtering implementation
 */
class FileInfoFilterBase implements FileInfoFilter {

	/**
	 * file/dir accessed before
	 * @var int @atime_before
	 */
	protected $atime_before;

	/**
	 * file/dir accessed after
	 * @var int @atime_after
	 */
	protected $atime_after;

	/**
	 * file/dir created before
	 * @var int @ctime_before
	 */
	protected $ctime_before;

	/**
	 * file/dir created after
	 * @var int @ctime_after
	 */
	protected $ctime_after;

	/**
	 * file/dir modified before
	 * @var int @mtime_before
	 */
	protected $mtime_before;

	/**
	 * file/dir modified after
	 * @var int @mtime_after
	 */
	protected $mtime_after;

	/**
	 * is a linked file/dir
	 * @var bool $is_link
	 */
	protected $is_link;

	/**
	 * regex patterns to match
	 * @var array $link
	 */
	protected $regexes;

	/**
	 * constructor for class
	 */
	public function __construct() {
		$this->regexes = array();
		$this->atime_after = 0;
		$this->atime_before = 0;
		$this->mtime_after = 0;
		$this->mtime_before = 0;
		$this->ctime_after = 0;
		$this->ctime_before = 0;
	}

	/**
	 * clean the input for all the time setters
	 * @param int $time
	 * @return int
	 */
	private function cleanTime( $time ) {
		if( is_int( $time ) && $time > 0 ) {
			return $time;
		} else {
			return 0;
		}
	}

	/**
	 * setter for $atime_before
	 * @param int $time
	 */
	public function setATimeBefore( $time ) {
		$this->atime_before = $this->cleanTime( $time );
	}

	/**
	 * getter for $atime_before
	 * @return int
	 */
	public function getATimeBefore() {
		return $this->atime_before;
	}

	/**
	 * setter for $atime_after
	 * @param int $time
	 */
	public function setATimeAfter( $time ) {
		$this->atime_after = $this->cleanTime( $time );
	}

	/**
	 * getter for $atime_after
	 * @return int
	 */
	public function getATimeAfter() {
		return $this->atime_after;
	}

	/**
	 * setter for $ctime_before
	 * @param int $time
	 */
	public function setCTimeBefore( $time ) {
		$this->ctime_before = $this->cleanTime( $time );
	}

	/**
	 * getter for $ctime_before
	 * @return int
	 */
	public function getCTimeBefore() {
		return $this->ctime_before;
	}

	/**
	 * setter for $ctime_after
	 * @param int $time
	 */
	public function setCTimeAfter( $time ) {
		$this->ctime_after = $this->cleanTime( $time );
	}

	/**
	 * getter for $ctime_after
	 * @return int
	 */
	public function getCTimeAfter() {
		return $this->ctime_after;
	}

	/**
	 * setter for $mtime_before
	 * @param int $time
	 */
	public function setMTimeBefore( $time ) {
		$this->mtime_before = $this->cleanTime( $time );
	}

	/**
	 * getter for $mtime_before
	 * @return int
	 */
	public function getMTimeBefore() {
		return $this->mtime_before;
	}

	/**
	 * setter for $mtime_after
	 * @param int $time
	 */
	public function setMTimeAfter( $time ) {
		$this->mtime_after = $this->cleanTime( $time );
	}

	/**
	 * getter for $mtime_after
	 * @return int
	 */
	public function getMTimeAfter() {
		return $this->mtime_after;
	}

	/**
	 * setter for $is_link
	 * @param bool $is_link
	 */
	public function setIsLink( $is_link ) {
		if ( is_null( $is_link ) ) {
			$this->is_link = null;
		} elseif ( is_bool( $is_link ) ) {
			$this->is_link = $is_link;
		}
	}

	/**
	 * getter for $is_link
	 * @return bool
	 */
	public function getIsLink() {
		return $this->is_link;
	}

	/**
	 * setter for $regexes
	 * @param array $regexes
	 */
	public function setRegExes( $regexes ) {
		if( is_array( $regexes ) ) {
			$this->regexes = $regexes;
		}
	}

	/**
	 * getter for $regexes
	 * @return array
	 */
	public function getRegExes() {
		return $this->regexes;
	}

	/**
	 * adds a regular expression to be matched against
	 * @param string $regex
	 */
	public function addRegEx( $regex ) {
		if( ! in_array($regex, $this->regexes ) ) {
			$this->regexes[] = $regex;
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
		if( $this->atime_after
			&& $this->atime_after > $file_info->getATime()
		) {
			return FALSE;
		}
		if( $this->atime_before
			&& $this->atime_before < $file_info->getATime()
		) {
			return FALSE;
		}
		if( $this->mtime_after
			&& $this->mtime_after > $file_info->getMTime()
		) {
			return FALSE;
		}
		if( $this->mtime_before
			&& $this->mtime_before < $file_info->getMTime()
		) {
			return FALSE;
		}
		if( $this->ctime_after
			&& $this->ctime_after > $file_info->getCTime()
		) {
			return FALSE;
		}
		if( $this->ctime_before
			&& $this->ctime_before < $file_info->getCTime()
		) {
			return FALSE;
		}
		if( ! is_null( $this->is_link )
			&& $this->is_link != $file_info->isLink()
		) {
			return FALSE;
		}
		if( count( $this->regexes ) > 0 ) {
			foreach ( $this->regexes as $regex ) {
				if ( preg_match( $regex, $file_info->getFilename() ) ) {
					return TRUE;
				}
			}
			return FALSE;
		}
		return TRUE;
	}
}
