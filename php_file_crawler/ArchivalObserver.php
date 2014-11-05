<?php
/**
 * PHP-File-Crawler
 *
 * @author     Thomas Robertson <tom@omnikrys.com>
 * @version    1.0
 * @package    php-file-crawler
 * @subpackage classes
 * @link       https://github.com/omnikrystc/PHP-File-Crawler
 */
namespace php_file_crawler;

require_once( 'php_file_crawler/includes/Observed.interface.php' );
require_once( 'php_file_crawler/includes/Observer.interface.php' );
require_once( 'php_file_crawler/includes/SimpleObserver.abstract.php' );

/**
 * This observer will copy everything found into a provided directory
 * maintaining the original directory structure 
 */
class SymLinkObserver extends includes\SimpleObserver {

	/**
	 * where the files will be copied
	 * @var \SplObjectStorage
	 */
	protected $destination;
	
	/**
	 * Extend the constructor
	 * @param Observable $observable
	 * @param String $destination
	 */
	public function __construct( Observable $observable, $destination ) {
		parent::__construct( $observable );
		$this->destination = $destination;

	}
	
	/**
	 * Implementation of the abstract doUpdate function
	 * @param includes\Observed $result
	 */
	protected function doUpdate( includes\Observed $result ) {
		// 
		if( $result->getStatus() == $result::STATUS_MATCHED ) {
			$this->addResult( clone $result );
		}
	}

}
