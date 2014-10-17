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

	$search = new php_file_crawler\DirectorySearch();
//	$matched = new php_file_crawler\MatchedObserver( $search );
//	$baddir = new php_file_crawler\SkippedDirObserver( $search );

	$search->scanDirectory( '/mnt/nas' );

	print 'Finished! ' . PHP_EOL;

//	$line = 0;
//	foreach( $baddir->getResults() as $data ) {
//		printf ( '%04d: %10s ', ++$line, $data->getStatus() );
//		print $data->getDirectory() . PHP_EOL;
//	}
}

stash();
