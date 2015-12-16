<?php
  require_once( DIR_LIB . '/i_peeler.inc.php' );
  require_once( DIR_LIB . '/factory.inc.php' );
  class Peeler_basic implements IPeeler {
    private $mConfig;
    private $mScraper;
    public function __construct( $config ) {
      $this->mConfig = $config;
      $this->mScraper = Factory::createScraper();
    }
    public function start() {
      
    }
  }
?>