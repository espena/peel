<?php
  require_once( DIR_LIB . '/i_client_application.inc.php' );
  class AppCliBase implements IClientApplication {
    private $mBase;
    public function __construct( $base ) {
      $this->mBase = $base;
    }
    public function getConfig() {
      return $this->mBase->getConfig();
    }
    public function run() {
      echo( "This is the CLI frontend speaking\n" );
      $this->mBase->run();
    }
    public function terminate() {
      $this->mBase->terminate();
    }
  }
?>