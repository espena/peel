<?php
  require_once( DIR_LIB . '/i_peeler.inc.php' );
  require_once( DIR_LIB . '/factory.inc.php' );
  require_once( DIR_LIB . '/utils.inc.php' );
  class Peeler_inHref implements IPeeler {
    private $mPeeler;
    public function __construct( $peeler ) {
      $this->mPeeler = $peeler;
    }
    public function start() {
      $this->mPeeler->start();
      $data = &$this->mPeeler->getData();
      $data[ 'sourceInfo' ] = array();
      if( preg_match_all( '/<a[^>]*?href=["\']([^"\']+\.pdf)["\'][^>]*>([^<]*)</', $data[ 'start_page' ], $matches, PREG_SET_ORDER ) ) {
        $base = $data[ 'url_source' ];
        foreach( $matches as $m ) {
          $data[ 'sourceInfo' ][ ] = array( 'url' => Utils::rel2abs( $m[ 1 ], $base ), 'linktext' => trim( $m[ 2 ] ) );
        }
      }
    }
    public function &getData() {
      return $this->mPeeler->getData();
    }
    public function &getConfig() {
      return $this->mPeeler->getConfig();
    }
    public function resolveDestinationPath( $dir, $sourceInfo ) {
      return $this->mPeeler->resolveDestinationPath( $dir, $sourceInfo );
    }
  }
?>