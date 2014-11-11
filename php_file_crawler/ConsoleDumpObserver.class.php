<?php
/**
 * PHP-File-Crawler
 *
 * @author     Thomas Robertson <tom@omnikrys.com>
 * @version    1.0
 * @package    php-file-crawler
 * @subpackage classes
 * @link       https://github.com/omnikrystc/PHP-File-Crawler
 */
namespace php_file_crawler;

require_once( 'php_file_crawler/includes/Observed.interface.php' );
require_once( 'php_file_crawler/includes/Observer.interface.php' );
require_once( 'php_file_crawler/includes/SimpleObserver.abstract.php' );

/**
 * This observer is for debugging. It is just going to dump to the console
 * without storing anything or taking any action. It is also told what we are
 * crawling so it can identify problems.
 */
class ConsoleDumpObserver extends includes\SimpleObserver {
	/**
	 * array of strings
	 * @var array
	 */
	protected $watched;
	
	/**
	 * Extend the constructor
	 * @param includes\Observable $observable
	 * @param string $destination
	 * @param array $watched
	 */
	public function __construct( 
			includes\Observable $observable, 
			$watched 
	) {
		parent::__construct( $observable );
		$this->watched = $watched;
	}

	/**
	 * setter for $this->watched
	 * @param array $watched
	 */
	public function setWatched( $watched ) {
		if ( is_array( $watched ) ) {
			$this->watched = $watched;
		}
	}

	/**
	 * getter for $this->watched
	 * @return array $this->watched
	 */
	public function getWatched() {
		return $this->watched;
	}

	/**
	 * Adds a status to the watched array
	 * @param string $status
	 */
	public function addWatched( $status ) {
		if ( is_string( $status ) ) {
			if ( array_search( $status, $this->watched ) === false ) {
				$this->watched[] = $status;
			}
		}
	}

	/**
	 * Removes a status in the watched array
	 * @param string $status
	 */
	public function removeWatched( $status ) {
		if ( $index = array_search( $status, $this->watched ) !== false ) {
			unset( $this->watched[$index] );
		}
	}
	
	/**
	 * Implementation of the abstract doUpdate function
	 * @param includes\Observed $result
	 */
	protected function doUpdate( includes\Observed $result ) {
		static $counter = 0;
		if ( array_search( $result->getStatus(), $this->watched ) !== false ) {
			if ( $result->isFileInfoValid() ) {
				$path = $result->getFileInfo()->getPathname(); 
			} else {
				$path = 'Unknown';
			}
			printf( 
				'%06d %-10s: %3d, %-60s %s' . PHP_EOL,  
//				'%s, %3d, %s, %s, %s' . PHP_EOL,
				++$counter,  
				$result->getStatus(),
				$result->getDepth(),
				$result->getDirectory(),
				$path
			);
		}
	}

}
