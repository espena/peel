<?php
  require_once( DIR_LIB . '/i_client_application.inc.php' );
  class AppCliBase implements IClientApplication {
    private $mBase;
    public function __construct( $base ) {
      $this->mBase = $base;
    }
    public function run() {
      echo( "This is the CLI frontend speaking\n" );
      if( $this->mBase ) {
        $this->mBase->run();
      }
    }
    public function terminate() {
      if( $this->mBase ) {
        $this->mBase->terminate();
      }
    }
  }
?>