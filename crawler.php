<?php
ini_set('display_errors', 'on');

require_once( 'php-file-crawler/DataHandler.class.php');
require_once( 'php-file-crawler/FileCrawler.class.php');

$crawler = new php_file_crawler\FileCrawler();
$watcher = new php_file_crawler\DataHandler( $crawler );

$crawler->crawlDirectory( '../../' );

$found = $watcher->getFileList();

foreach ( $found as $filename ) {
	print $filename.PHP_EOL;
}
