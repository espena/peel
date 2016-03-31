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
      $this->handleOAuthFlow( isset( $_SERVER['PATH_INFO'] ) ? $_SERVER['PATH_INFO'] : '/' );
      $this->mBase->run();
    }

    public function terminate() {
      $this->mBase->terminate();
    }

    public function tpl( $idt ) {
      $this->mBase->tpl( $idt );
    }

    private function getDbxClient() {
      $dbxClient = null;
      $c = $this->getConfig();
      $accessToken      = isset( $c[ 'dropbox' ][ 'access_token' ] ) ? $c[ 'dropbox' ][ 'access_token' ] : null;
      $clientIdentifier = isset( $c[ 'dropbox' ][ 'client_identifier' ] ) ? $c[ 'dropbox' ][ 'client_identifier' ] : null;
      $userLocale       = isset( $c[ 'dropbox' ][ 'user_locale' ] ) ? $c[ 'dropbox' ][ 'user_locale' ] : null;
      if( $accessToken && $clientIdentifier && $userLocale ) {
        $dbxClient = new dbx\Client( $accessToken, $clientIdentifier, $userLocale );
      }
      return $dbxClient;
    }

    private function handleOAuthFlow( $req ) {
      switch( $req ) {
        case '/':
          $dbxClient = $this->getDbxClient();
          if( $dbxClient == null ) {
            header( 'Location: /dropbox-auth-start' );
            exit;
          }
          break;

        case '/download':

          break;

        case '/upload':

          break;

        case '/dropbox-auth-start':

          break;

        case '/dropbox-auth-finish':

          break;

        case '/dropbox-auth-unlink':

          break;

        default:
          ;
      }
    }

  }

?>