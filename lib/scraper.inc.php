<?php
  require_once( 'factory.inc.php' );
  define( 'DEFAULT_USER_AGENT_STRING', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)' );
  define( 'DEFAULT_CONNECTION_TIMEOUT', 20 );
  define( 'DEFAULT_REQUEST_TIMEOUT', 20 );
  class Scraper {
    private $mConfig;
    private $mResponseData;
    private $mResponseCode;
    public function __construct() {
      $this->mConfig = Factory::getConfig();
    }
    public function get( $url ) {
      $ch = $this->curlPreparePlain( $url );
      $this->mResponseData = curl_exec( $ch );
      $this->mResponseCode = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
      curl_close( $ch );
    }
    public function getResponseData() {
      return $this->mResponseData;
    }
    public function getResponseCode() {
      return $this->mResponseCode;
    }
    private function curlPreparePlain( $url ) {
      $sc = $this->mConfig[ 'scraping' ];
      $ch = curl_init();
      $ua = isset( $sc[ 'user_agent' ] ) ? $sc[ 'user_agent' ] : DEFAULT_USER_AGENT_STRING;
      $ct = isset( $sc[ 'connection_timeout' ] ) ? $sc[ 'connection_timeout' ] : DEFAULT_CONNECTION_TIMEOUT;
      $rt = isset( $sc[ 'request_timeout' ] ) ? $sc[ 'request_timeout' ] : DEFAULT_REQUEST_TIMEOUT;
      curl_setopt( $ch, CURLOPT_URL, $url );
      curl_setopt( $ch, CURLOPT_USERAGENT, $ua );
      curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
      curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, $ct );
      curl_setopt( $ch, CURLOPT_TIMEOUT, $rt );
      curl_setopt( $ch, CURLOPT_HEADER, true );
      curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
      curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
      return $ch;
    }
  }
?>