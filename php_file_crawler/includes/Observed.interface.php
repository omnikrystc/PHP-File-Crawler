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

/**
 * Observed interface, this is the data passed from the Observable to the
 * Observer on notify.
 * 
 * @todo The constants REALLY shouldn't be here. It stinks.
 */
interface Observed {
	/**
	 * Permission denied attempting to access a directory
	 */
	const STATUS_DENIED = 'denied';
	/**
	 * Ignoring symbolic links so this entry was skipped
	 */
	const STATUS_SYMLINK = 'symlink';
	/**
	 * This directory is deeper than the allowed depth
	 */
	const STATUS_TOODEEP = 'toodeep';
	/**
	 * Directory doesn't exist, should only be possible on first call
	 */
	const STATUS_NODIR = 'nodir';
	/**
	 * Some other trapped error
	 */
	const STATUS_ERROR = 'error';
	/**
	 * This is a file that failed inclusion criteria and was skipped
	 */
	const STATUS_NOMATCH = 'nomatch';
	/**
	 * This is a directory that met exclusion criteria and was skipped
	 */
	const STATUS_EXCLUDE = 'exclude';
	/**
	 * This entry is a valid match
	 */
	const STATUS_MATCHED = 'matched';
	
	/**
	 * get the working directory
	 */
	public function getDirectory();
	/**
	 * get the target, this could be a file or directory name
	 */
	public function getTarget();
	/**
	 * get the full name, just directory + target with cleanup
	 */
	public function getFullName();
	/**
	 * get the depth (from start directory) the target is at
	 */
	public function getDepth();
	/**
	 * get the status of the target, one of the STATUS_* constants
	 */
	public function getStatus();
	/**
	 * tell if target is a directory
	 */
	public function isDirectory();
}
