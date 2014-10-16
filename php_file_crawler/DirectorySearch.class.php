<?php
/**
 * PHP-File-Crawler
 *
 * @author     Thomas Robertson <tom@omnikrys.com>
 * @version    1.1
 * @package    php-file-crawler
 * @subpackage classes
 * @link       https://github.com/omnikrystc/PHP-File-Crawler
 */
namespace php_file_crawler;

require_once( 'php_file_crawler/includes/Observable.interface.php' );
require_once( 'php_file_crawler/includes/ObservableTraits.trait.php' );
require_once( 'php_file_crawler/includes/ObservedData.class.php' );
require_once( 'php_file_crawler/includes/Observer.interface.php' );

 /**
 * This is the class that does the actual file crawling. It is observable so
 * its only job is to crawl files and notify the observers when it finds a
 * file.
 */
class DirectorySearch implements includes\Observable {
	use includes\ObservableTraits;
	/**
	 * implementation of the Observed interface for updating observers
	 * @var includes\ObservedData $status
	 */
	private $status;

	/**
	 * constructor
	 */
	public function __construct() {
		$this->status = new includes\ObservedData();
		$this->observers = new \SplObjectStorage();
	}

	/**
	 * Update status and trigger a notify so the Observers get an update
	 * @param string $status (an Observed::STATUS_* constant)
	 */
	private function notifyStatus( $status ) {
		$this->status->setStatus( $status );
		$this->notify();
	}

	/**
	 * Debug dump cause I am lazy
	 * @todo Remove this
	 */
	private function debug( $comment ) {
		print $comment . PHP_EOL;
	}

	/**
	 * Scan a directory using its path
	 * @param string $directory
	 */
	public function scanDirectory( $directory ) {
		if ( $iterator = $this->getIteratorFromDirectory( $directory ) ) {
			$this->scanIterator( $iterator );
		}
	}

	/**
	 * Scan a directory using the current target of a DirectoryIterator
	 * @param \DirectoryIterator
	 */
	private function scanIteratorFromCurrent( \DirectoryIterator $current ) {
		if ( $iterator = $this->getIteratorFromCurrent( $current ) ) {
			$this->scanIterator( $iterator );
		}
	}

	/**
	 * Scan using the passed iterator
	 * @param \DirectoryIterator
	 */
	private function scanIterator( \DirectoryIterator $iterator ) {
		$this->status->increaseDepth();
		foreach ( $iterator as $current ) {
			$this->filterCurrent( $current );
		}
		$this->status->decreaseDepth();
	}

	/**
	 * Get a DirectoryIterator from the provided path
	 * @param string $directory
	 */
	private function getIteratorFromDirectory( $directory ) {
		$this->status->setDirectory( $directory );
		if ( is_readable( $directory ) ) {
			return new \DirectoryIterator( $directory );
		} elseif ( ! file_exists( $directory ) ) {
			$this->notifyStatus( includes\ObservedData::STATUS_NODIR );
		} else {
			$this->notifyStatus( includes\ObservedData::STATUS_DENIED );
		}
		return FALSE;
	}

	/**
	 * is the DirectoryIterator's current target valid
	 * @param \DirectoryIterator
	 * @return boolean
	 */
	private function isCurrentValid( \DirectoryIterator $current ) {
		if ( ! $current->valid() ) {
			$this->notifyStatus( includes\ObservedData::STATUS_INVALID );
		} elseif ( $current->isDot() ) {
			$this->notifyStatus( includes\ObservedData::STATUS_BADDIR );
		} elseif ( ! $current->isReadable() ) {
			$this->notifyStatus( includes\ObservedData::STATUS_DENIED );
		} else {
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Get a DirectoryIterator using the passed DirectoryIterator's current
	 * target
	 * @param \DirectoryIterator
	 * @return \DirectoryIterator or FALSE
	 */
	private function getIteratorFromCurrent( \DirectoryIterator $current ) {
		if ( $this->isCurrentValid( $current ) ) {
			return new \DirectoryIterator( $current->getPathname() );
		}
		return FALSE;
	}

	/**
	 * filter helper that passes to a specific filter function
	 * @param \DirectoryIterator
	 */
	private function filterCurrent( \DirectoryIterator $current ) {
		if ( $this->isCurrentValid( $current ) ) {
			if ( $current->isDir() ) {
				$this->filterCurrentDir( $current );
			} elseif ( $current->isFile() ) {
				$this->filterCurrentFile( $current );
			} else {
				$this->notifyStatus( includes\ObservedData::STATUS_UNKNOWN );
			}
		}

	}

	/**
	 * filter the file pointed to by the passed DirectoryIterator
	 * @param \DirectoryIterator
	 */
	private function filterCurrentFile( \DirectoryIterator $current ) {
		if ( $file_info = $this->getCurrentFileInfo( $current ) ) {
			// do file filtering here.
			$this->status->setFileInfo( $file_info );
			$this->notifyStatus( includes\ObservedData::STATUS_MATCHED );
		}
	}

	/**
	 * filter the directory pointed to by the passed DirectoryIterator
	 * @param \DirectoryIterator
	 */
	private function filterCurrentDir( \DirectoryIterator $current ) {
		if ( $this->isCurrentValid( $current ) ) {
			// do directory filtering here.
			$this->status->setFileInfo( $file_info );
			$this->scanIteratorFromCurrent( $current );
		}
	}

	/**
	 * get a SplFileInfo object from the passed DirectoryIterator
	 * @param \DirectoryIterator
	 */
	private function getCurrentFileInfo( \DirectoryIterator $current ) {
		if ( $this->isCurrentValid( $current ) ) {
			return new \SplFileInfo( $current->getPathname() );
		}
		return FALSE;
	}

	/**
	 * Overriding the method provided by ObservableTraits trait
	 *
	 */
	public function notify() {
		foreach ( $this->observers as $observer ) {
			$observer->update( $this->status );
		}
	}

}






