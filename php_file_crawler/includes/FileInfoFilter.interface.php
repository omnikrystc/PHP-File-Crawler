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

/**
 * Filter interface for filtering files/directories
 */
interface FileInfoFilter {

	/**
	 * identify what is filtered
	 * @param \SplFileInfo $file_info
	 *
	 */
	public function isFiltered( \SplFileInfo $file_info );

}