<?php
/**
 * PHP-File-Crawler
 * 
 * @author     Thomas Robertson <tom@omnikrys.com>
 * @version    1.2
 * @package    php-file-crawler
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
 * 
 * @package    php-file-crawler
 * @subpackage classes
 */
class FileCrawler implements includes\Observable, includes\Observed {
	/**
	 * The observers to this observable
	 *  
	 * @var array
	 */
	private $observers;
	/**
	 * The last folder acted on
	 * 
	 * @var string
	 */
	private $current_realpath;
	/**
	 * The last item is a directory
	 * 
	 * @var boolean
	 */
	private $current_item;
	/** 
	 * The status of this item (see self::STATUS_* constants)
	 * @var string
	 */	 
	private $current_is_dir;
	/**
	 * The last item acted on (folder or file name)
	 * 
	 * @var string
	 */
	private $current_status;
	/**
	 * Current depth of the crawl
	 * 
	 * @var integer
	 */
	private $current_depth;
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
		$this->current_depth = 0;
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
	public function crawlDirectory( $dir, $skip_links = TRUE ) {
		$this->clearStatus();
		$realpath = realpath( $dir );
		if ( is_readable( $realpath ) ) {
			$this->current_depth++;
			$this->current_realpath = $realpath;
			$oldpath = getcwd();
			chdir( $realpath );
	    	$items = scandir( $realpath );
			foreach ( $items as $item ) {
				$this->current_item = $item;
				if ( $item != '.' && $item != '..' ) {
					$fullpath = $this->joinPath( $realpath, $item );
					$this->current_is_dir = is_dir( $fullpath );
		            if ( $skip_links && is_link( $item ) ) {
						$this->notifyStatus( self::STATUS_SYMLINK );
						//$this->dumpStatus( 'Pre check.' );
		            } elseif ( $this->current_is_dir ) {
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
			$this->current_realpath = $oldpath;
			$this->current_depth--;
			chdir( $oldpath );
		} else {
			$this->current_realpath = $dir;
			if( ! file_exists( $realpath ) ) {
				$this->notifyStatus( self::STATUS_NODIR );
			} else {
				$this->notifyStatus( self::STATUS_DENIED );
			}
		} 
	}

	/** 
	 * internal function for quick debug dump
	 * 
	 */
	public function dumpStatus( $comment ) {
		print PHP_EOL . $comment . PHP_EOL;
		print str_repeat('*', strlen( $comment )) . PHP_EOL;
		print '>   Dir: ' . $this->current_realpath . PHP_EOL;
		print '>  Item: ' . $this->current_item . PHP_EOL;
		print '>  Dir?: ' . ( $this->current_is_dir ? 'Yes' : 'No' ) . PHP_EOL;
		print '> Depth: ' . $this->current_depth . PHP_EOL;
		print '>Status: ' . $this->current_status . PHP_EOL;
	}
	
	/** internal function to clear status
	 * @param boolean $reset_depth 
	 * 		TRUE = set depth to 0 
	 * 		FALSE = don't change
	 */
	private function clearStatus( $reset_depth = FALSE ) {
		$this->current_realpath = null;
		$this->current_item = null;
		$this->current_is_dir = null;
		$this->current_status = null;
		if ( $reset_depth ) {
			$this->current_depth = 0;
		}
	}
		
	/**
	 * Internal function to set status and trigger notify
	 * 
	 * @param string $status one of the self::STATUS_* constants
	 */
	private function notifyStatus( $status ) {
		$this->current_status = $status;
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
		return $this->current_realpath;		
	}
	
	/** 
	 * access for the observers
	 * 
	 * @return string 
	 */
	public function getTarget() {
		return $this->current_item;
	}
	
	/** 
	 * access for the observers
	 * 
	 * @return string 
	 */
	public function getDepth() {
		return $this->current_depth;
	}
	
	/** 
	 * access for the observers
	 * 
	 * @return string 
	 */
	public function getStatus() {
		return $this->current_status;
	}
	
	/** 
	 * access for the observers
	 * 
	 * @return string 
	 */
	public function isDirectory() {
		return $this->current_is_dir;	
	}
	
	/** 
	 * access for the observers
	 * 
	 * @return string 
	 */
	function getFullName() {
		return $this->joinPath( $this->current_realpath, $this->current_item );
	}

}




 

