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
      if( empty( $_GET[ 'callback' ] ) ) {
        echo $this->getJson();
      }
      else {
        echo $_GET['callback'] . '(' . $this->getJson() . ')';
      }
    }

    private function getJson() {
      switch( $_GET[ 'json' ] ) {
        case 'peel_log':
          $log = Factory::getLogger();
          $data = $log->getContent();
          break;
        case 'peel_ctrl':
          $conf = Factory::getConfig();
          $data = array_map( function( $e ) { return $e[ 'peeler' ]; }, $conf[ 'peelers' ] );
          break;
        default:
          $data = array();
      }
      return json_encode( $data );
    }

    public function terminate() {
      $this->mBase->terminate();
    }

    public function tpl( $idt ) {
      $this->mBase->tpl( $idt );
    }

  }

?>