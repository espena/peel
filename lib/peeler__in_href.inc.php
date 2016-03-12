<?php
  require_once( DIR_LIB . '/i_peeler.inc.php' );
  require_once( DIR_LIB . '/factory.inc.php' );
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
        foreach( $matches as $m ) {
          $this->mData[] = $m[ 1 ];
        }
      }
    }
    public function getData( $key = '' ) {
      return $this->mData;
    }
    public function getConfig() {
      return $this->mPeeler->getConfig();
    }
  }
?>