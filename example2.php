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

ini_set( 'display_errors', 'on' );
ini_set( 'xdebug.max_nesting_level', 200 );

error_reporting( E_ALL );

function debug( $comment ) {
	if ( $comment instanceof \SplFileInfo ) {
		print 'Path: ' . $comment->getPath() . PHP_EOL;
		print 'File: ' . $comment->getFilename() . PHP_EOL;
		print 'Dir?: ' . ( $comment->isDir() ? 'Yes' : 'No' ) . PHP_EOL;
		if ( $comment instanceof \DirectoryIterator ) {
			print 'Dot?: ' . ( $comment->isDot() ? 'Yes' : 'No' ) . PHP_EOL;
		} else {
			print 'Dot?: SplFileInfo';
		}
	} else {
	print 'Comment: ' . $comment . PHP_EOL;
	}
}

function stash() {
	require_once( 'php_file_crawler/DirectorySearch.class.php');
	require_once( 'php_file_crawler/MatchedObserver.class.php');
	require_once( 'php_file_crawler/SkippedDirObserver.class.php');
	require_once( 'php_file_crawler/includes/FileInfoFilterBase.class.php');

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
	$filter = new php_file_crawler\includes\FileInfoFilterBase();
	// this clears any previous patterns
	$filter->setRegExes( $patterns );
	// can add/remove after though (or skip the bulk thing entirely)
	$filter->addRegEx( '/\.htm[l]*$/i' );
	$filter->addRegEx( '/\.css$/i' );
	$filter->removeRegEx( '/\.zap$/i' );
	// only files modifed in the last 30 days
	//$filter->setMTimeAfter( time() - ( 60 * 60 * 24 * 30 ) );
	// create our search
	$search = new php_file_crawler\DirectorySearch( $filter );
	// subscribe an observer
	$matched = new php_file_crawler\MatchedObserver( $search );
	// do some searches
	$search->scanDirectory( '/home/thomas/Downloads' );
	$search->scanDirectory( '/home/thomas/Documents' );
	// the matcher is only logging the matches so display them
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
}

stash();
