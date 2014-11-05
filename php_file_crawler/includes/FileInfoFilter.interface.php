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
 * Filter interface for filtering files/directories
 */ 
interface FileInfoFilter {

	/**
	 * file info matched any of the criteria (directories)
	 * @param \SplFileInfo $file_info
	 */
	public function matchedAny( \SplFileInfo $file_info );

	/**
	 * file info matched all of the criteria (files)
	 * @param \SplFileInfo $file_info
	 */
	public function matchedAll( \SplFileInfo $file_info );

}