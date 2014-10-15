<?php
/**
 * PHP-File-Crawler
 * 
 * @author     Thomas Robertson <tom@omnikrys.com>
 * @version    1.4
 * @package    php-file-crawler
 * @subpackage classes
 * @link       https://github.com/omnikrystc/PHP-File-Crawler
 */
namespace php_file_crawler;

require_once( 'php_file_crawler/includes/Observable.interface.php' );
require_once( 'php_file_crawler/includes/Observed.interface.php' );
require_once( 'php_file_crawler/includes/Observer.interface.php' );

/**
 * This is the class that does the actual file crawling. It is observable so 
 * its only job is to crawl files and notify the observers when it finds a
 * file.  
 */
class FileCrawler implements includes\Observable, includes\Observed {
	/**
	 * The observers to this observable
	 *  
	 * @var \SplObjectStorage
	 */
	private $observers;
	/**
	 * Current depth
	 * 
	 * @var integer
	 */
	private $depth;
	/**
	 * Current file info
	 * 
	 * @var SplFileInfo
	 */
	private $file_info;
	/**
	 * Current status
	 * 
	 * @var string
	 */
	private $status;
	/**
	 * The match criteria for the files we crawl (regex patterns)
	 * 
	 * @var array
	 */
	private $file_matches;
	/**
	 * The exclude criteria for the directories we crawl
	 */ 
	private $dir_ignores;
	/**
	 * Default 'match all' criteria if no file match criteria are provided
	 */
	const DEFAULT_MATCH = '/.*/';
	
	/**
	 * Class constructor. Allows initialization of the include/excludes too.
	 * 
	 * @param array $file_matches (regex patterns) or null
	 * @param array $dir_ignores (regex patterns) or null
	 */
	public function __construct($file_matches = null, $dir_ignores = null) {
		$this->depth = 0;
		$this->observers = new \SplObjectStorage();
		$this->setDirIgnores( $dir_ignores );
		$this->setFileMatches( $file_matches );
	}

	/**
	 * Return the file match criteria ($file_matches array)
	 * 
	 * @return array
	 */	
	public function getFileMatches() {
		return $this->file_matches;
	}
	
	/**
	 * Set the file match criteria or set default
	 * 
	 * @param array $file_matches (regex patterns) or null
	 */	
	public function setFileMatches( $file_matches ) {
		if( is_array( $file_matches ) ) {
			$this->file_matches = $file_matches;
		} else {
			$this->file_matches = array( self::DEFAULT_MATCH );
		}
	}
	
	/**
	 * Return the dir exclude criteria (regex patterns)
	 * 
	 * @return array
	 */	
	public function getDirIgnores() {
		return $this->dir_ignores;
	}
	
	/**
	 * Set the dir exclude criteria or an empty array as default
	 * 
	 * @param array $dir_ignores (regex patterns) or null
	 */	
	public function setDirIgnores( $dir_ignores ) {
		if ( is_array( $dir_ignores ) ) {
			$this->dir_ignores = $dir_ignores;
		} else {
			$this->dir_ignores = array();
		}
	}
	
	/**
	 * checks a directory against the $dir_ignores array
	 * 
	 * @param string $dir the directory to check
	 * @return boolean
	 */
	private function isIgnoredDir( $dir ) {
		foreach ( $this->dir_ignores as $ignore ) {
			if ( preg_match( $ignore, $dir ) ) {
				return TRUE;
			} 
		}	
		return FALSE;
	}
	
	/**
	 * Checks a filename against the $file_matches array
	 * 
	 * @param string $file the filename to check
	 * @return boolean
	 */
	private function isMatchedFile( $file ) {
		foreach ( $this->file_matches as $match ) {
			if ( preg_match( $match, $file ) ) {
				return TRUE;
			} 
		}	
		return FALSE;
	}
	
	/**
	 * Only purpose is to turn a directory into a valid DirectoryIterator or
	 * kick back an error. This is only called on first run for each new
	 * working directory (passed into a recursive function after that)
	 * @param string $directory
	 */
	public function search( $directory ) {
		$work_dir = new \DirectoryIterator( $work_dir );
			
	}
	
	private function searchDirectory( \DirectoryIterator $target ) {
		$this->resetStatus();
		$this->depth++;
		if ( $dir_iterator = $this->getDirectoryIterator( $target ) ) {
			foreach ( $dir_iterator as $file ) {
				if ( $file->isDot() ) {
					continue;
				} elseif ( $file->isDir() ) {
					$this->search( $file->getPathName() );
				} elseif ( $file_info = $this->verifyFileInfo( $file ) ) {
					// we have a winner folks!!!!!;
					$this->notifyStatus( self::STATUS_MATCHED, $file_info ); 
				}
			}		
		}
		$this->depth--;	
	}
	
	/**
	 * Check the file clears our file filters and return a file info if so.
	 * 
	 * @param string $directory 
	 * @return \SplFileInfo or FALSE on failure
	 */
	private function verifyFileInfo( \DirectoryIterator $file ) {
		return new \SplFileInfo( $file->getPathname() );
	}
	
	/**
	 * Check directory clears our directory filters and return an iterator
	 * if so.
	 * 
	 * @param \DirectoryIterator $parent 
	 * @return \DirectoryIterator or FALSE on failure
	 */
	private function getDirectoryIterator( \DirectoryIterator $parent ) {
		return new \DirectoryIterator( $parent->getPathname() );
	}
	 
	/**
	 * Attach for our Observers
	 * 
	 * @param includes\Observer $observer the observer to attach
	 */
	public function attach( includes\Observer $observer ) {
		$this->observers->attach( $observer );
	} 
	
	/**
	 * Detach for our Observers
	 * 
	 * @param \SplObserver $observer the observer to detach
	 */
	public function detach( includes\Observer $observer ) {
		$this->observers->detach( $observer );
	}
	
	/**
	 * Notify for our observers
	 * 
	 */
	public function notify() {
		foreach ( $this->observers as $observer ) {
			$observer->update( $this );
		}
	} 

	/** 
	 * internal function for quick debug dump
	 * @param string $comment simple comment to add to status console dump
	 */
	public function printStatus( $comment ) {
		print PHP_EOL . $comment . PHP_EOL;
		print str_repeat('*', strlen( $comment )) . PHP_EOL;
		print '>  File: ' . $this->file_info . PHP_EOL;
		print '> Depth: ' . $this->depth . PHP_EOL;
		print '>Status: ' . $this->status . PHP_EOL;
	}
	
	/** internal function to clear status
	 * @param boolean $reset_depth 
	 * 		TRUE = set depth to 0 
	 * 		FALSE = don't change
	 */
	private function resetStatus( $reset_depth = FALSE ) {
		$this->file_info = null;
		$this->status = null;
		if ( $reset_depth ) {
			$this->depth = 0;
		}
	}
		
	/**
	 * Internal function to set status and trigger notify
	 * 
	 * @param string $status one of the self::STATUS_* constants
	 */
	private function notifyStatus( $status, \SplFileInfo $file_info = null ) {
		$this->file_info = $file_info;
		$this->status = $status;
		$this->notify();
	}
	
	/** 
	 * access for the observers
	 * 
	 * @return string 
	 */
	public function getDepth() {
		return $this->depth;
	}
	
	/** 
	 * access for the observers
	 * 
	 * @return string 
	 */
	public function getStatus() {
		return $this->status;
	}
	
	/** 
	 * access for the observers
	 * 
	 * @return \SplFileInfo 
	 */
	public function getFileInfo() {
		return $this->file_info;
	}

}




 

