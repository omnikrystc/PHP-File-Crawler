<?php
/**
 * PHP-File-Crawler example 
 * 
 * @author     Thomas Robertson <tom@omnikrys.com>
 * @version    1.0
 * @package    php-file-crawler
 * @subpackage example
 * @link       https://github.com/omnikrystc/PHP-File-Crawler
 */

ini_set('display_errors', 'on');

require_once( 'php_file_crawler/MatchedObserver.class.php');
require_once( 'php_file_crawler/SymLinkObserver.class.php');
require_once( 'php_file_crawler/SkippedDirObserver.class.php');
require_once( 'php_file_crawler/FileCrawler.class.php');

/**
 * Simple helper to dump the contents of an array of strings both to console
 * and to a file in /tmp
 * @param array $files array of strings for output
 * @param string $type simple one word name for the filename and console header  
 */ 
function dumpData( $files, $type ) {
	print PHP_EOL . 'Found ' . count( $files ) . ' of type: ' . $type . PHP_EOL;
	print str_repeat("*", 80) . PHP_EOL;
	$results = implode( PHP_EOL, $files );
	print $results;
	print PHP_EOL . str_repeat("*", 80) . PHP_EOL;
	file_put_contents( '/tmp/' . $type . '.txt', $results );
}

/**
 * Array of regular expressions for matching files 
 * @var array $file_includes
 */
$file_includes = array( 
	'/\.doc$/', 
	'/\.pdf$/', 
	'/\.xls$/', 
	'/\.zip$/', 
	'/\.iso$/',
);

/**
 * Array of regular expressions for excluding directories 
 * @var array $file_includes
 */
$dir_excludes = array( 
	'/^\./', 				# excluding any hidden directories
	'/^ftb$/',				# ftb folder full of extracted crap
);

// subject/observable
$crawler = new php_file_crawler\FileCrawler( $file_includes, $dir_excludes );

// different observers
$matches = new php_file_crawler\MatchedObserver( $crawler );
$symlinks = new php_file_crawler\SymLinkObserver( $crawler );
$skipped = new php_file_crawler\SkippedDirObserver( $crawler );

// crawl a few different places
$crawler->crawlDirectory( '/home/lost+found' ); // permission denied
$crawler->crawlDirectory( '/home/thomas' ); // full access, lots to scan
$crawler->crawlDirectory( '/home/virtual' ); // partial permissions
$crawler->crawlDirectory( '/home/baddirectory' ); // doesn't exist

// data dump
dumpData( $matches->getList(), 'matches' );
dumpData( $symlinks->getList(), 'symlinks' );
dumpData( $skipped->getList(), 'skipped' );
