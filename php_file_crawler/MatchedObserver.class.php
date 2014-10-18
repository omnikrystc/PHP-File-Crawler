<?php
/**
 * PHP-File-Crawler
 *
 * @author     Thomas Robertson <tom@omnikrys.com>
 * @version    1.5
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
class MatchedObserver extends includes\SimpleObserver {

	/**
	 * Implementation of the abstract doUpdate function
	 * @param includes\Observed $result
	 */
	protected function doUpdate( includes\Observed $result ) {
		if( $result->getStatus() == $result::STATUS_MATCHED ) {
			static $loop = 0;
			printf( ' %06d: %s %10s %s' . PHP_EOL,
				++$loop,
				($result->getFileInfo()->IsLink() ? 'Y' : 'N' ),
				$result->getStatus(),
				$result->getFileInfo()->getPathname()
			);
			$this->addResult( clone( $result ) );
		}
	}
}
