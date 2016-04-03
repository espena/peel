<?php

  require_once( DIR_LIB . '/i_application.inc.php' );
  
  class AppAjaxResponse implements IWebApplication {

    private $mBase;

    public function __construct( $base ) {
      $this->mBase = $base;
    }

    public function getConfig() {
      return $this->mBase->getConfig();
    }

    public function run() {
      header( 'content-type: application/json; charset=utf-8' );
      $json = json_encode( array( "foo" => "bar" ) );
      if( empty( $_GET[ 'callback' ] ) ) {
        echo $json;
      }
      else {
        echo $_GET['callback'] . '(' . $json . ')';
      }
    }

    public function terminate() {
      $this->mBase->terminate();
    }

    public function tpl( $idt ) {
      $this->mBase->tpl( $idt );
    }

  }

?>