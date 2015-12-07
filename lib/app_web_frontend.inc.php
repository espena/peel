<?php
  class AppWebFrontend implements IWebApplication {
    private $mBase;
    public function __construct( $base ) {
      $this->mBase = $base;
    }
    public function run() {
      if( $this->mBase ) {
        $this->mBase->run();
      }
      echo( 'This is the web frontend speaking...' );
    }
    public function terminate() {
      if( $this->mBase ) {
        $this->mBase->terminate();
      }
    }
    public function tpl( $idt ) {
      if( $this->mBase ) {
        $this->mBase->tpl( $idt );
      }
    }
  }
?>