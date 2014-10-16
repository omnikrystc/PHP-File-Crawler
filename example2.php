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

ini_set( 'display_errors', 'on' );
error_reporting( E_ALL );

require_once( 'php_file_crawler/DirectorySearch.class.php');
require_once( 'php_file_crawler/MatchedObserver.class.php');

$search = new php_file_crawler\DirectorySearch();
$matched = new php_file_crawler\MatchedObserver( $search );

$search->scanDirectory( '/home/thomas/Documents' );
$search->scanDirectory( '/home/thomas/Documents' );

$line = 0;
foreach( $matched->getResults() as $data ) {
	printf ( '%04d: %10s ', ++$line, $data->getStatus() );
	print $data->getFileInfo()->getPathname() . PHP_EOL;
}
