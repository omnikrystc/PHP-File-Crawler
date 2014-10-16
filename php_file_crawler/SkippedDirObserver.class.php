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
 * The observer that collects all directories the Observer reported skipped
 * for whatever reason
 */
class SkippedDirObserver extends includes\SimpleObserver {

	/**
	 * Implementation of the abstract doUpdate function
	 * @param includes\Observed $observed
	 */
	protected function doUpdate( includes\Observed $result ) {
		$watching = array(
			$result::STATUS_DENIED,
			$result::STATUS_BADDIR,
			$result::STATUS_NODIR,
#			$result::STATUS_EXCLUDE,
		);
		if ( in_array( $result->getStatus(), $watching ) ) {
			$this->addResult( clone $result );
		}
	}

}
