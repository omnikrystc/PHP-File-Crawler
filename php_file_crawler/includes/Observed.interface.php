<?php
/**
 * PHP-File-Crawler
 * 
 * @author     Thomas Robertson <tom@omnikrys.com>
 * @version    2.0
 * @package    php-file-crawler
 * @subpackage includes
 * @link       https://github.com/omnikrystc/PHP-File-Crawler
 */
namespace php_file_crawler\includes;

/**
 * Observed interface, this is the data passed from the Observable to the
 * Observer on notify.
 * 
 * @todo The constants REALLY shouldn't be here. It stinks.
 */
interface Observed {
	/**
	 * This entry is a valid match
	 */
	const STATUS_MATCHED = 'matched';
	/**
	 * Permission denied attempting to access directory/file
	 */
	const STATUS_DENIED = 'denied';
	/**
	 * Invalid \DirectoryIterator
	 */	
	const STATUS_INVALID = 'invalid';
	/**
	 * Not isFile() or isDir()
	 */	
	const STATUS_UNKNOWN = 'unknown';
	/**
	 * Directory doesn't exist
	 */	
	const STATUS_NODIR = 'nodir';
	/**
	 * The dot directories
	 */
	const STATUS_BADDIR = 'baddir';
	/**
	 * Ignoring symbolic links so this entry was skipped
	 */
	const STATUS_SYMLINK = 'symlink';
	/**
	 * This directory is deeper than the allowed depth
	 */
	const STATUS_TOODEEP = 'toodeep';
	/**
	 * Some other trapped error
	 */
	const STATUS_ERROR = 'error';
	
	/**
	 * get the directory (fall back when there is no file info)
	 * @return string
	 */
	public function getDirectory();
	/**
	 * get the depth
	 * @return integer 
	 */
	public function getDepth();
	/**
	 * get the directory
	 * @return string
	 */
	public function getFileInfo();
	/**
	 * get the status (one of the self::STATUS_* constants)
	 * @return string 
	 */
	public function getStatus();
}
