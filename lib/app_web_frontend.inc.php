<?php
  class AppWebFrontend implements IWebApplication {
    private $mBase;
    public function __construct( $base ) {
      $this->mBase = $base;
    }
    public function getConfig() {
      return $this->mBase->getConfig();
    }
    public function run() {
      $this->mBase->run();
      echo( 'This is the web frontend speaking...' );
    }
    public function terminate() {
      $this->mBase->terminate();
    }
    public function tpl( $idt ) {
      $this->mBase->tpl( $idt );
    }
  }
?>