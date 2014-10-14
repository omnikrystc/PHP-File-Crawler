<?php
/**
 * PHP-File-Crawler
 * 
 * @author     Thomas Robertson <tom@omnikrys.com>
 * @version    1.0
 * @package    php-file-crawler
 * @link       https://github.com/omnikrystc/PHP-File-Crawler
 */
namespace php_file_crawler;

require_once( 'php_file_crawler/includes/Observed.interface.php' );
require_once( 'php_file_crawler/includes/Observer.interface.php' );
require_once( 'php_file_crawler/includes/CrawlerObserver.abstract.php' );

/**
 * The observer that collects all of the files found by the crawler.
 * 
 * @package    php-file-crawler
 * @subpackage classes
 */
class MatchedObserver extends includes\CrawlerObserver {
	/**
	 * Array of files reported as matched by the Observable
	 *  
	 * @var array
	 */
	private $files_found;

	/**
	 * Extension of the constructor for includes\CrawlerObserver
	 */
	public function __construct( includes\Observable $observable ) {
		parent::__construct( $observable );
		$this->files_found = array();
	}
	

	/**
	 * Implementation of the abstract doUpdate function
	 */
	protected function doUpdate( includes\Observed $observed ) {
		if( $observed->getStatus() == $observed::STATUS_MATCHED ) {
			$filename = sprintf( 
				'%02d %10s: %s', 
				$observed->getDepth(),
				$observed->getStatus(),
				$observed->getFullName()
			);
			if ( ! in_array( $filename, $this->files_found ) ) {
				$this->files_found[] = $filename;			
			}
		}
	}

	/**
	 * Returns a raw list of the files our Observable matched
	 * @return array of strings (files found)
	 */	
	public function getFileList() {
		return $this->files_found;
	}
	
}
