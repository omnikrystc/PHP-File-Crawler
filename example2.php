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

$search = new php_file_crawler\DirectorySearch();
$search->scanDirectory( '/home/thomas/Documents' );
