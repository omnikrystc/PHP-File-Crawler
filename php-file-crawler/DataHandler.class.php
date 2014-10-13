<?php
/**
 * PHP-File-Crawler
 * 
 * @author     Thomas Robertson <tom@omnikrys.com>
 * @version    1.0
 * @package    php-file-crawler
 * @link       https://github.com/omnikrystc/PHP-File-Crawler
 */
namespace php_file_crawler;

require_once( 'FileCrawler.class.php' );

/**
 * This is the observer that collects all of the files found by the crawler.
 * 
 * @package    php-file-crawler
 * @subpackage classes
 */
class DataHandler implements \SplObserver {
	private $file_crawler;
	private $files_found;
	
	public function __construct( FileCrawler $file_crawler ) {
		$this->files_found = array();
		$this->file_crawler = $file_crawler;
		$file_crawler->attach( $this ); 
	}
	
	public function update( \SplSubject $observable ) {
		if ( $observable === $this->file_crawler ) {
			$this->doUpdate( $observable );
		}
	}
	
	private function doUpdate( FileCrawler $file_crawler ) {
		$this->files_found[] = $file_crawler->getFileName();
	}
	
	public function getFileList() {
		return $this->files_found;
	}
	
}
