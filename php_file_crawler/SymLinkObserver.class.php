<?php
/**
 * PHP-File-Crawler
 * 
 * @author     Thomas Robertson <tom@omnikrys.com>
 * @version    1.3
 * @package    php-file-crawler
 * @subpackage classes
 * @link       https://github.com/omnikrystc/PHP-File-Crawler
 */
namespace php_file_crawler;

require_once( 'php_file_crawler/includes/Observed.interface.php' );
require_once( 'php_file_crawler/includes/Observer.interface.php' );
require_once( 'php_file_crawler/includes/SimpleObserver.abstract.php' );

/**
 * The observer that collects all of the files found by the crawler.
 */
class SymLinkObserver extends includes\SimpleObserver {
	
	/**
	 * Implementation of the abstract doUpdate function
	 * @param includes\Observed $observed
	 */
	protected function doUpdate( includes\Observed $observed ) {
		if( $observed->getStatus() == $observed::STATUS_SYMLINK ) {
			$result = sprintf( 
				'%02d %10s: %s', 
				$observed->getDepth(),
				$observed->getStatus(),
				$observed->getFullPath()
			);
			$this->addResult( $result );
		}
	}
	
}
