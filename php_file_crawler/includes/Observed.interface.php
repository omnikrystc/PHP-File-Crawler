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
	 * getter for $directory
	 * @return string
	 */
	public function getDirectory();

	/**
	 * getter for $depth
	 * @return integer
	 */
	public function getDepth();

	/**
	 * getter for $file_info
	 * @return string
	 */
	public function getFileInfo();

	/**
	 * getter for $status
	 * @return string
	 */
	public function getStatus();
}
