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
	 * @var array
	 */
	private $observers;
	/**
	 * Current depth
	 * 
	 * @var integer
	 */
	private $depth;
	/**
	 * Current working directory
	 * 
	 * @var string
	 */
	private $directory;
	/**
	 * Current file
	 * 
	 * @var string
	 */
	private $filename;
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
	 * Utility function to append the item to the path stripping/adding
	 * slashes as needed
	 * 
	 * @param string $dir the base working directory 
	 * @param string $item the item (file/directory name) to add
	 */	
	private function joinPath( $dir, $item ) {
		return '/' . join( 
			'/', 
			array( trim( $dir, '/' ), 
			trim( $item, '/' ) 
		) );
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
	 * The engine, crawls from the given working directory until done
	 * 
	 * @param string $dir the starting directory 
	 * @param boolean $skip_links (default TRUE) skip symbolic links 
	 */
	public function crawlDirectory( $dir, $max_depth = 0, $skip_links = TRUE ) {
		$this->clearStatus();
		$directory = realpath( $dir );
		if ( is_readable( $directory ) ) {
			$this->depth++;
			$this->directory = $directory;
			$oldpath = getcwd();
			chdir( $directory );
	    	$items = scandir( $directory );
			foreach ( $items as $item ) {
				$this->filename = $item;
				if ( $item != '.' && $item != '..' ) {
					$fullpath = $this->joinPath( $directory, $item );
		            if ( $skip_links && is_link( $item ) ) {
						$this->notifyStatus( self::STATUS_SYMLINK );
						//$this->dumpStatus( 'Pre check.' );
		            } elseif ( is_dir( $fullpath ) ) {
		            	if ( ! $this->isIgnoredDir( $item ) ) {
			            	$this->crawlDirectory( $fullpath, $skip_links );
		            	} else {
							$this->notifyStatus( self::STATUS_EXCLUDE );
		            	}
					} elseif ( $this->isMatchedFile( $item ) ) {
						$this->notifyStatus( self::STATUS_MATCHED );
					} else {
						$this->notifyStatus( self::STATUS_NOMATCH );
					}
				}
			}
			$this->directory = $oldpath;
			$this->depth--;
			chdir( $oldpath );
		} else {
			$this->directory = $dir;
			if( ! file_exists( $directory ) ) {
				$this->notifyStatus( self::STATUS_NODIR );
			} else {
				$this->notifyStatus( self::STATUS_DENIED );
			}
		} 
	}

	/** 
	 * internal function for quick debug dump
	 * @param string $comment simple comment to add to status console dump
	 */
	public function dumpStatus( $comment ) {
		print PHP_EOL . $comment . PHP_EOL;
		print str_repeat('*', strlen( $comment )) . PHP_EOL;
		print '>   Dir: ' . $this->directory . PHP_EOL;
		print '>  File: ' . $this->filename . PHP_EOL;
		print '> Depth: ' . $this->depth . PHP_EOL;
		print '>Status: ' . $this->status . PHP_EOL;
	}
	
	/** internal function to clear status
	 * @param boolean $reset_depth 
	 * 		TRUE = set depth to 0 
	 * 		FALSE = don't change
	 */
	private function clearStatus( $reset_depth = FALSE ) {
		$this->directory = null;
		$this->filename = null;
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
	private function notifyStatus( $status ) {
		$this->status = $status;
		$this->notify();
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
	 * access for the observers
	 * 
	 * @return string 
	 */
	public function getDirectory() {
		return $this->directory;		
	}
	
	/** 
	 * access for the observers
	 * 
	 * @return string 
	 */
	public function getFilename() {
		return $this->filename;		
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
	 * @return string 
	 */
	public function getFullPath() {
		return $this->joinPath( $this->directory, $this->filename );
	}

}




 

