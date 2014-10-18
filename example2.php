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
require_once( 'php_file_crawler/SkippedDirObserver.class.php');
require_once( 'php_file_crawler/includes/FileInfoFilterBase.class.php');

ini_set( 'display_errors', 'on' );
// uses 3 levels per directory level so if you go deep you need to up this...
ini_set( 'xdebug.max_nesting_level', 200 );
error_reporting( E_ALL );


/**
 * basic example of the php_file_crawler\DirectorySearch in action
 */
function simpleFilter() {
	// file filter is a match all filter...
	// Everything must match, including 1 regex if any are set, to match
	$file_filter = new php_file_crawler\includes\FileInfoFilterBase();
	$file_filter->addRegEx( '/\.htm[l]*$/i' );	// find pdfs
	$file_filter->addRegEx( '/\.css$/i' );	// and docs
	// dir filter is a match any filter...
	// If anything matches the directory is excluded from the search
	$dir_filter = new php_file_crawler\includes\FileInfoFilterBase();
	$dir_filter->setIsLink( TRUE );			// no linked directories
	$dir_filter->addRegEx( '/^\./' );		// no hidden directories
	$dir_filter->addRegEx( '/extract/' );	// my Download's extract directory
		// create our search, last param is depth and is optional
	$search = new php_file_crawler\DirectorySearch( $file_filter, $dir_filter, 7 );
	// subscribe a observers
	$matched = new php_file_crawler\MatchedObserver( $search );
	$skipped = new php_file_crawler\SkippedDirObserver( $search );
	// do some searches
	$search->scanDirectory( '/home/thomas/Downloads' );
	$search->scanDirectory( '/home/thomas/Documents' );
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
		'/\.zip$/i',
		'/\.zap$/i',
	);
	//
	//	Our file filter
	//
	$file_filter = new php_file_crawler\includes\FileInfoFilterBase();
	// this clears any previous patterns
	$file_filter->setRegExes( $patterns );
	// can add/remove after though (or skip the bulk thing entirely)
	$file_filter->addRegEx( '/\.htm[l]*$/i' );
	$file_filter->addRegEx( '/\.css$/i' );
	$file_filter->removeRegEx( '/\.zap$/i' );
	// only files modifed in the last 30 days
	$file_filter->setMTimeAfter( time() - ( 60 * 60 * 24 * 30 ) );
	//
	// directory filter
	//
	$dir_filter = new php_file_crawler\includes\FileInfoFilterBase();
	// exclude symlinks
	$dir_filter->setIsLink( FALSE );
	// exclude my extract directory
	$dir_filter->addRegEx( '/^extract$/');
	// create our search
	$search = new php_file_crawler\DirectorySearch( $file_filter, $dir_filter, 7 );
	// subscribe any observers
	$matched = new php_file_crawler\MatchedObserver( $search );
	$skipped = new php_file_crawler\SkippedDirObserver( $search );
	// do some searches
	$search->scanDirectory( '/home/thomas/Downloads' );
	$search->scanDirectory( '/home/thomas/Documents' );
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

simpleFilter();
