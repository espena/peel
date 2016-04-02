<?php
  require_once( DIR_LIB . '/i_peeler.inc.php' );
  require_once( DIR_LIB . '/factory.inc.php' );
  require_once( DIR_LIB . '/utils.inc.php' );
  class Peeler_uniqueBy implements IPeeler {
    private $mPeeler;
    private $mData;
    public function __construct( $peeler ) {
      $this->mPeeler = $peeler;
      $this->mData = array();
    }
    public function start() {
      $this->mPeeler->start();
    }
    public function getData( $key = '' ) {
      return $this->mPeeler->getData( $key );
    }
    public function getConfig() {
      return $this->mPeeler->getConfig();
    }
  }
?>