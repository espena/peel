<?php
  require_once( DIR_LIB . '/i_peeler.inc.php' );
  require_once( DIR_LIB . '/factory.inc.php' );
  require_once( DIR_LIB . '/utils.inc.php' );
  class Peeler_urlMetadata implements IPeeler {
    private $mPeeler;
    private $mData;
    public function __construct( $peeler ) {
      $this->mPeeler = $peeler;
      $this->mData = array();
    }
    public function start() {
      $this->mPeeler->start();
      $this->mData = $this->mPeeler->getData();
      $c = $this->getConfig();
      foreach( $this->mData as &$sourceInfo ) {
        if( preg_match( '/' . $c[ 'peeler' ][ 'url_metadata' ] . '/', $sourceInfo[ 'url' ], $m ) == 1 ) {
          $sourceInfo[ 'metadata' ] = Utils::purgeNumericSubscripts( $m );
        }
        else {
          $log = Factory::getLogger();
          $log->error( "The URL did not match 'url_metadata' pattern (%s)", $url );
        }
      }
    }
    public function getData( $key = '' ) {
      return $this->mData;
    }
    public function getConfig() {
      return $this->mPeeler->getConfig();
    }
    public function resolveDestinationPath( $dir, $sourceInfo ) {
      return $this->mPeeler->resolveDestinationPath( $dir, $sourceInfo[ 'url' ] );
    }
  }
?>