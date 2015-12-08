<?php
  require_once( DIR_LIB . '/i_web_application.inc.php' );
  class AppWebBase implements IWebApplication {
    private $mBase;
    public function __construct( $base ) {
      $this->mBase = $base;
    }
    public function getConfig() {
      return $this->mBase->getConfig();
    }
    public function run() {
      if( $this->mBase ) {
        $this->mBase->run();
      }
      $this->tpl( 'main' );
    }
    public function terminate() {
      if( $this->mBase ) {
        $this->mBase->terminate();
      }
    }
    public function tpl( $idt ) {
      
    }
  }
?>