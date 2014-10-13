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


/**
 * This is the class that does the actual file crawling. It is observable so 
 * its only job is to crawl files and notify the observers when it finds a
 * file.  
 * 
 * @package    php-file-crawler
 * @subpackage classes
 */
class FileCrawler implements \SplSubject {
	private $observers;
	private $last_found;
	
	public function __construct() {
		$this->observers = new \SplObjectStorage();
	}
	
	private function joinPath( $directory, $filename ) {
		return '/' . join( 
			'/', 
			array( trim( $directory, '/' ), 
			trim( $filename, '/' ) 
		) );
	}	
	
	public function crawlDirectory( $directory ) {
		$realpath = realpath( $directory );		
	    $items = scandir( $realpath );
		foreach ( $items as $item ) {
			if ( $item != '.' && $item != '..' ) {
				$fullpath = $this->joinPath( $realpath, $item );
	            if( is_dir( $fullpath ) ) {
	            	$this->crawlDirectory( $fullpath );
				} else {
					$this->last_found = $fullpath;
					$this->notify();
				}
			}
		}
	}
	
	public function attach( \SplObserver $observer ) {
		$this->observers->attach( $observer );
	} 
	
	public function detach( \SplObserver $observer ) {
		$this->observers->detach( $observer );
	} 
	
	public function notify() {
		foreach ( $this->observers as $observer ) {
			$observer->update( $this );
		}
	} 
	
	function getFileName() {
		return $this->last_found;
	}

}




 

