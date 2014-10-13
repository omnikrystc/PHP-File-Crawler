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
	private $file_matches; 
	private $dir_ignores;
	const DEFAULT_MATCH = '/.*/';
	
	public function __construct($file_matches = null, $dir_ignores = null) {
		$this->observers = new \SplObjectStorage();
		$this->setDirIgnores( $dir_ignores );
		$this->setFileMatches( $file_matches );
	}
	
	public function getFileMatches() {
		return $this->file_matches;
	}
	
	public function setFileMatches( $file_matches ) {
		if( is_array( $file_matches ) ) {
			$this->file_matches = $file_matches;
		} else {
			$this->file_matches = array( self::DEFAULT_MATCH );
		}
	}
	
	public function getDirIgnores() {
		return $this->dir_ignores;
	}
	
	public function setDirIgnores( $dir_ignores ) {
		if ( is_array( $dir_ignores ) ) {
			$this->dir_ignores = $dir_ignores;
		} else {
			$this->dir_ignores = array();
		}
	}
	
	private function joinPath( $dir, $addition ) {
		return '/' . join( 
			'/', 
			array( trim( $dir, '/' ), 
			trim( $addition, '/' ) 
		) );
	}	
	
	private function isIgnoredDir( $dir ) {
		foreach ( $this->dir_ignores as $ignore ) {
			if ( preg_match( $ignore, $dir ) ) {
				return TRUE;
			} 
		}	
		return FALSE;
	}
	
	private function isMatchedFile( $file ) {
		foreach ( $this->file_matches as $match ) {
			if ( preg_match( $match, $file ) ) {
				return TRUE;
			} 
		}	
		return FALSE;
	}
	
	public function crawlDirectory( $dir, $skip_links = TRUE ) {
		$realpath = realpath( $dir );
		if ( ! $realpath ) {
			return;
		}
		$oldpath = getcwd();
		chdir( $realpath );
	    $items = scandir( $realpath );
		foreach ( $items as $item ) {
			if ( $item != '.' && $item != '..' ) {
				$fullpath = $this->joinPath( $realpath, $item );
	            if ( $skip_links && is_link( $item ) ) {
					print ' > Skipped link: ' . $fullpath . PHP_EOL;
	            } elseif ( is_dir( $fullpath ) ) {
	            	if ( ! $this->isIgnoredDir( $item ) ) {
		            	$this->crawlDirectory( $fullpath, $skip_links );
	            	}
				} elseif ( $this->isMatchedFile( $item ) ) {
					$this->last_found = $fullpath;
					$this->notify();
				}
			}
		}
		chdir( $oldpath );
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




 

