<?php

  require_once( DIR_LIB . '/i_application.inc.php' );
  require_once( DIR_LIB . '/dropbox_sdk/Dropbox/strict.php' );
  require_once( DIR_LIB . '/dropbox_sdk/Dropbox/autoload.php' );
  
  use \Dropbox as dbx;

  class AppDropbox implements IWebApplication {

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

    public function tpl( $idt ) {
      $this->mBase->tpl( $idt );
    }

  }

?>