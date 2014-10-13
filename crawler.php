<?php
ini_set('display_errors', 'on');

print 'w';

require_once( 'php-file-crawler/DataHandler.class.php');
require_once( 'php-file-crawler/FileCrawler.class.php');

print 't';

$file_includes = array( 
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
#	'/\.$/i', 
#	'/\.php$/i', 
);
$dir_excludes = array( 
	'/^\./', 				# excluding any hidden directories
	'/^ftb$/',				# ftb folder full of extracted crap
);

print 'f';

$crawler = new php_file_crawler\FileCrawler( $file_includes, $dir_excludes );
$watcher = new php_file_crawler\DataHandler( $crawler );

print '?' . PHP_EOL;

$crawler->crawlDirectory( '/home/thomas' );

$found = $watcher->getFileList();
print 'Found: ' . count( $found ) . PHP_EOL;
file_put_contents( '/tmp/dumped.txt', implode( PHP_EOL, $found ) );
