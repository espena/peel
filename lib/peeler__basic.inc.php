<?php
  require_once( DIR_LIB . '/i_peeler.inc.php' );
  class Peeler_basic implements IPeeler {
    private $mConfig;
    public function __construct( $config ) {
      $this->mConfig = $config;
    }
    public function start() {
      
    }
  }
?>