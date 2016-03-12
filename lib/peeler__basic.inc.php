<?php
 /**
  * PEEL Document Downloader engine.
  *
  * Basic peeler.
  *
  * PHP version > 5.5
  *
  * @category   peel
  * @package    PEEL
  * @author     Espen Andersen <post@espenandersen.no>
  * @copyright  2016 The Author
  * @license    GNU General Public License, version 3
  * @link       https: //github.com/espena/peel
  */
  require_once( DIR_LIB . '/i_peeler.inc.php' );
  require_once( DIR_LIB . '/factory.inc.php' );
 /**
  * Basic peeler class.
  *
  * All peelers must implement this class as a minimum requirement.
  *
  * Classes that implement /IPeeler are instantiated in /Factory using
  * a decorator pattern.
  *
  * @category   peel
  * @package    PEEL
  */
  class Peeler_basic implements IPeeler {

   /**
    * Array with data (usually HTML code) downloded from target locations.
    */
    private $mRawData;

   /**
    * Configuration data for current peeler.
    */
    private $mConfig;

   /**
    * cURL-based web scraper/downloader implementation.
    */
    private $mScraper;

   /**
    * Constructor
    *
    * Creates a basic peeler to be decorated with additional \IPeeler
    * implementations from the \Factory class.
    *
    * @param array $config The contents of the configuration file for current
    *        peeler, parsed into a multi-dimensional array.
    */
    public function __construct( $config ) {
      $this->mConfig = $config;
      $this->mRawData = array();
      $this->mScraper = Factory::createScraper();
    }

   /**
    * Starts current peeler
    *
    * Initializes and runs current peeler.
    *
    * @return void.
    */
    public function start() {
      $this->mScraper->get( $this->mConfig[ 'peeler' ][ 'url_start_page' ] );
      $this->mRawData[ 'start_page' ] = $this->mScraper->getResponseData();
    }

   /**
    * Retrieve peeler result.
    * @param $key which data field to retrieve.
    * @return string The data (usually HTML) from the start page.
    */
    public function getData( $key = 'start_page' ) {
      return $this->mRawData[ $key ];
    }

   /**
    * Retrieve peeler configuration.
    * @return array The contents of the configuration file for current peeler.
    */
    public function getConfig() {
      return $this->mConfig;
    }
  }
?>