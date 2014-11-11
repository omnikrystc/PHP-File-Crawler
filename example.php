<?php
/**
 * PHP-File-Crawler example
 *
 * @author     Thomas Robertson <tom@omnikrys.com>
 * @version    1.1
 * @package    php-file-crawler
 * @subpackage example
 * @link       https://github.com/omnikrystc/PHP-File-Crawler
 */

require_once( 'php_file_crawler/DirectorySearch.class.php');
require_once( 'php_file_crawler/MatchedObserver.class.php');
require_once( 'php_file_crawler/SkippedDirObserver.class.php');
require_once( 'php_file_crawler/ConsoleDumpObserver.class.php');
require_once( 'php_file_crawler/includes/FileInfoFilterBase.class.php');
// shortcut for using STATUS_ constants
use php_file_crawler\includes\Observed as STATUS;

ini_set( 'display_errors', 'on' );
// uses 4 levels per directory level so if you go deep you need to up this...
ini_set( 'xdebug.max_nesting_level', 200 );
error_reporting( E_ALL );

/**
 * subscribe the debug observer and run it...
 * @param string $directory
 * @param string $depth
 */
function debugRun( $depth ) {
	// what we want to watch
	$watched = array(
		STATUS::STATUS_MATCHED,
//		STATUS::STATUS_FILTERED,
//		STATUS::STATUS_EXCLUDED,
		STATUS::STATUS_DENIED,
		STATUS::STATUS_NODIR,
		STATUS::STATUS_INVALID,
		STATUS::STATUS_UNKNOWN,
//		STATUS::STATUS_DOTDIR,
//		STATUS::STATUS_SYMLINK,
//		STATUS::STATUS_TOODEEP,
		STATUS::STATUS_ERROR,
	);
	// file filter is a match all filter...
	// Everything must match, including 1 regex if any are set, to match
	$file_filter = new php_file_crawler\includes\FileInfoFilterBase();
	$file_filter->addRegEx( '/\.htm[l]*$/i' );	// find pdfs
	$file_filter->addRegEx( '/\.css$/i' );	// and docs
	// dir filter is a match any filter...
	// If anything matches the directory is excluded from the search
	$dir_filter = new php_file_crawler\includes\FileInfoFilterBase();
	$dir_filter->setIsLink( TRUE );			// no linked directories
//	$dir_filter->addRegEx( '/^\./' );		// no hidden directories
	$dir_filter->addRegEx( '/^extract$/' );	// my Download's extract directory
	// create our search, last param is depth and is optional
	$search = new php_file_crawler\DirectorySearch(
		$file_filter,
		$dir_filter,
		$depth
	);
	// subscribe a observers
	// debug observer
	$observer = new php_file_crawler\ConsoleDumpObserver(
		$search,
		$watched
	);
	// do the search
//	$search->searchDirectory( '/home/thomas/Documents' );
//	$search->searchDirectory( '/home/thomas/Downloads' );
	$search->searchDirectory( '/.' );
//	$search->searchDirectory( '/home/thomas' );
}

/**
 * basic example of the php_file_crawler\DirectorySearch in action
*/
function simpleFilter() {
	// file filter is a match all filter...
	// Everything must match, including 1 regex if any are set, to match
	$file_filter = new php_file_crawler\includes\FileInfoFilterBase();
	$file_filter->addRegEx( '/\.htm[l]*$/i' );	// find html files
	$file_filter->addRegEx( '/\.css$/i' );	// and css files
	// dir filter is a match any filter...
	// If anything matches the directory is excluded from the search
	$dir_filter = new php_file_crawler\includes\FileInfoFilterBase();
	$dir_filter->setIsLink( TRUE );			// no linked directories
	$dir_filter->addRegEx( '/^\./' );		// no hidden directories
	$dir_filter->addRegEx( '/^extract$/' );	// my Download's extract directory
	// create our search, last param is depth and is optional
	$search = new php_file_crawler\DirectorySearch( $file_filter, $dir_filter, 7 );
	// subscribe observers
	$matched = new php_file_crawler\MatchedObserver( $search );
	$skipped = new php_file_crawler\SkippedDirObserver( $search );
	// do some searches
	$search->searchDirectory( '/home/thomas/Downloads' );
	$search->searchDirectory( '/home/thomas/Documents' );

	// matched observer just logs it so dump the log
	$line = 0;
	print '************************ Matched' . PHP_EOL;
	foreach( $matched->getResults() as $data ) {
		printf(
			'%04d: %s %10s %s' . PHP_EOL,
			++$line,
			($data->getFileInfo()->IsLink() ? 'Y' : 'N' ),
			$data->getStatus(),
			$data->getFileInfo()->getPathname()
		);
	}
	// skipped observer just logs it so dump the log
	$line = 0;
	print '************************ Skipped' . PHP_EOL;
	foreach( $skipped->getResults() as $data ) {
		printf(
			'%04d: %s %10s %s' . PHP_EOL,
			++$line,
			($data->getFileInfo()->IsLink() ? 'Y' : 'N' ),
			$data->getStatus(),
			$data->getFileInfo()->getPathname()
		);
	}
}

/**
 * another example of the php_file_crawler\DirectorySearch in action
 */
function stash() {
	$patterns = array(
			'/\.jpg$/i',
			'/\.jpeg$/i',
			'/\.gif$/i',
			'/\.tif$/i',
			'/\.tiff$/i',
			'/\.png$/i',
			'/\.psd$/i',
			'/\.doc$/i',
			'/\.docx$/i',
			'/\.mp4$/i',
			'/\.mpg$/i',
			'/\.mov$/i',
			'/\.wmv$/i',
			'/\.pdf$/i',
			'/\.xls$/i',
			'/\.xlsx$/i',
			#		'/\.$/i',
	);
	//
	//	Our file filter
	//
	$file_filter = new php_file_crawler\includes\FileInfoFilterBase();
	$file_filter->setRegExes( $patterns );
	//$file_filter->setMTimeAfter( time() - ( 60 * 60 * 24 * 30 ) );
	//
	// directory filter
	//
	$dir_filter = new php_file_crawler\includes\FileInfoFilterBase();
	// exclude symlinks
	$dir_filter->setIsLink( TRUE );
	// temps and whatnot
	$dir_filter->addRegEx( '/^temp$/');
	$dir_filter->addRegEx( '/^tmp$/');
	$dir_filter->addRegEx( '/^lib$/');
	$dir_filter->addRegEx( '/^src$/');
	$dir_filter->addRegEx( '/^\..+/');
	// create our search
	$search = new php_file_crawler\DirectorySearch( $file_filter, $dir_filter );
	// subscribe any observers
	$matched = new php_file_crawler\MatchedObserver( $search );
	$skipped = new php_file_crawler\SkippedDirObserver( $search );
	// do some searches
	// something in DirectoryIterator freaks on root so add dot to make it work
	$search->searchDirectory( '/mnt/clients' );
	$search->searchDirectory( '/home' );
	// the matcher is only logging the matches so display them
	print '*********************************************************' . PHP_EOL;
	print '*                Matched Files                          *' . PHP_EOL;
	print '*********************************************************' . PHP_EOL;
	$line = 0;
	foreach( $matched->getResults() as $data ) {
		printf(
			'%04d: %s %10s %s' . PHP_EOL,
			++$line,
			($data->getFileInfo()->IsLink() ? 'Y' : 'N' ),
			$data->getStatus(),
			$data->getFileInfo()->getPathname()
		);
	}
	print '*********************************************************' . PHP_EOL;
	print '*                Excluded Directories                   *' . PHP_EOL;
	print '*********************************************************' . PHP_EOL;
	$line = 0;
	foreach( $skipped->getResults() as $data ) {
		printf(
			'%04d: %s %10s %s' . PHP_EOL,
			++$line,
			($data->getFileInfo()->IsLink() ? 'Y' : 'N' ),
			$data->getStatus(),
			$data->getFileInfo()->getPathname()
		);
	}
}

stash();
// simpleFilter();
//debugRun( 0 );
