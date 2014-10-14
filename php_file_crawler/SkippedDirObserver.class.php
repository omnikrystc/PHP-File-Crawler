<?php
/**
 * PHP-File-Crawler
 * 
 * @author     Thomas Robertson <tom@omnikrys.com>
 * @version    1.1
 * @package    php-file-crawler
 * @subpackage classes
 * @link       https://github.com/omnikrystc/PHP-File-Crawler
 */
namespace php_file_crawler;

require_once( 'php_file_crawler/includes/Observed.interface.php' );
require_once( 'php_file_crawler/includes/Observer.interface.php' );
require_once( 'php_file_crawler/includes/SimpleObserver.abstract.php' );

/**
 * The observer that collects all directories the Observer reported skipped
 * for whatever reason
 */
class SkippedDirObserver extends includes\SimpleObserver {
	
	/**
	 * Implementation of the abstract doUpdate function
	 * @param includes\Observed $observed
	 */
	protected function doUpdate( includes\Observed $observed ) {
		$watching = array( 
			$observed::STATUS_DENIED,
			$observed::STATUS_TOODEEP,
			$observed::STATUS_NODIR,
			$observed::STATUS_EXCLUDE,
		);
		if ( in_array( $observed->getStatus(), $watching ) || ( 
			$observed->getStatus() == $observed::STATUS_SYMLINK
			&& $observed->isDirectory() ) 
		) { 
			$result = sprintf( 
				'%02d %10s: %s', 
				$observed->getDepth(),
				$observed->getStatus(),
				$observed->getFullName()
			);
			$this->addResult( $result );
		}
	}
	
}
