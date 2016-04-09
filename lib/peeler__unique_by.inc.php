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
    public function resolveDestinationPath( $dir, $sourceInfo ) {
      $log = Factory::getLogger();
      $res = $this->mPeeler->resolveDestinationPath( $dir, $sourceInfo );
      $c = $this->getConfig();
      $db = Factory::getDatabase();
      switch( $c[ 'peeler' ][ 'unique_by' ] ) {
        case 'url':
          if( $db->urlDownloaded( $sourceInfo[ 'url' ] ) ) {
            $log->message( 'Skipping %s (already downloaded)', $sourceInfo[ 'url' ] );
            $res = null;
          }
          break;
        case 'checksum':
          
          break;
      }
      return $res;
    }
    public function &getData() {
      return $this->mPeeler->getData();
    }
    public function &getConfig() {
      return $this->mPeeler->getConfig();
    }
  }
?>