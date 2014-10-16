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

require_once( 'php_file_crawler/includes/Observable.interface.php' );
require_once( 'php_file_crawler/includes/Observed.interface.php' );
require_once( 'php_file_crawler/includes/ObservedTraits.trait.php' );
require_once( 'php_file_crawler/includes/Observer.interface.php' );

/**
 * This is the class that does the actual file crawling. It is observable so 
 * its only job is to crawl files and notify the observers when it finds a
 * file.  
 */
class DirectorySearch implements includes\Observed {
	use includes\ObservedTraits;
	/**
	 * The observers to this observable
	 *  
	 * @var \SplObjectStorage
	 */
	private $observers;
	
	private function notifyStatus( $status ) {
		//$this->debug( 'Notify: ' . $status );		
	}
	
	private function notifyMatch( $file_info ) {
		printf ( '%02d: %s' . PHP_EOL, $this->depth, $file_info->getPathname() );		
	}
	
	private function debug( $comment ) {
		print $comment . PHP_EOL;	
	}
	
	public function scanDirectory( $directory ) {
		if ( $iterator = $this->getIteratorFromDirectory( $directory ) ) {
			$this->scanIterator( $iterator );
		}		
	}

	private function scanIteratorFromCurrent( \DirectoryIterator $current ) {
		if ( $iterator = $this->getIteratorFromCurrent( $current ) ) {
			$this->scanIterator( $iterator );
		}
	}
	
	private function scanIterator( \DirectoryIterator $iterator ) {
		$this->depth++;
		foreach ( $iterator as $current ) {
			$this->filterCurrent( $current );
		}
		$this->depth--;
	}
	
	private function getIteratorFromDirectory( $directory ) {
		$this->directory = $directory;
		if ( is_readable( $directory ) ) {
			return new \DirectoryIterator( $directory );
		} elseif ( ! file_exists( $directory ) ) {
			$this->notifyStatus( self::STATUS_NODIR );
		} else {
			$this->notifyStatus( self::STATUS_DENIED );
		}
		return FALSE;
	}

	private function isCurrentValid( \DirectoryIterator $current ) {
		if ( ! $current->valid() ) {
			$this->notifyStatus( self::STATUS_INVALID );
		} elseif ( $current->isDot() ) {
			$this->notifyStatus( self::STATUS_BADDIR );
		} elseif ( ! $current->isReadable() ) {
			$this->notifyStatus( self::STATUS_DENIED );
		} else {
			return TRUE;
		}
		return FALSE;
	}

	private function isFileInfoValid() {
		$file_info = $this->file_info;
		if ( is_null($file_info) || ! $file_info->valid() ) {
			return FALSE;
		}
		return TRUE;
	}
	
	private function getIteratorFromCurrent( \DirectoryIterator $current ) {
		if ( $this->isCurrentValid( $current ) ) {
			return new \DirectoryIterator( $current->getPathname() );
		}
		return FALSE;
	}
	
	private function filterCurrent( \DirectoryIterator $current ) {
		if ( $this->isCurrentValid( $current ) ) {
			if ( $current->isDir() ) {
				$this->filterCurrentDir( $current );
			} elseif ( $current->isFile() ) {
				$this->filterCurrentFile( $current );
			} else {
				$this->notifyStatus( self::STATUS_UNKNOWN );
			}
		}

	}
	
	private function filterCurrentFile( \DirectoryIterator $current ) {
		if ( $file_info = $this->getCurrentFileInfo( $current ) ) {
			// do file filtering here.
			$this->notifyMatch( $file_info );
			//$this->debug( $file_info->getPathname() );
		}
	}
	
	private function filterCurrentDir( \DirectoryIterator $current ) {
		if ( $this->isCurrentValid( $current ) ) {
			// do directory filtering here.
			$this->scanIteratorFromCurrent( $current );
		}
	}
	
	private function getCurrentFileInfo( $current ) {
		if ( $this->isCurrentValid( $current ) ) {
			return new \SplFileInfo( $current->getPathname() );
		}
		return FALSE;
	}
	
	private function verifyFileInfo( \DirectoryIterator $target ) {
		return new \DirectoryIterator( $target->getPathname());
	}
	
}




 

