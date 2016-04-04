<?php
  require_once( DIR_LIB . '/i_peeler.inc.php' );
  require_once( DIR_LIB . '/factory.inc.php' );
  require_once( DIR_LIB . '/utils.inc.php' );
  class Peeler_inHref implements IPeeler {
    private $mPeeler;
    private $mData;
    public function __construct( $peeler ) {
      $this->mPeeler = $peeler;
      $this->mData = array();
    }
    public function start() {
      $this->mPeeler->start();
      $data = $this->mPeeler->getData();
      if( preg_match_all( '/href=["\']([^"\']+\.pdf)["\']/', $data, $matches, PREG_SET_ORDER ) ) {
        $base = $this->mPeeler->getData( 'url_source' );
        foreach( $matches as $m ) {
          $this->mData[] = array( 'url' => Utils::rel2abs( $m[ 1 ], $base ) );
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
      return $this->mPeeler->resolveDestinationPath( $dir, $sourceInfo );
    }
  }
?>