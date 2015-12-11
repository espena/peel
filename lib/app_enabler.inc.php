<?php
  require_once( DIR_LIB . '/i_application.inc.php' );
  class AppEnabler implements IApplication {
    private $mBase;
    public function __construct( $base ) {
      $this->mBase = $base;
    }
    public function getConfig() {
      return $this->mBase->getConfig();
    }
    public function run() {
      $this->mBase->run();
      
    }
    public function terminate() {
      $this->mBase->terminate();
    }
  }
?>