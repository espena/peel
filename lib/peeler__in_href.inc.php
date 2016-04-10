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
      $conf = $this->getConfig();
      $pattern = sprintf( '/<a[^>]*?href=["\']([^"\']*?%s[^"\']*?)["\'][^>]*>([^<]*)</', $conf[ 'peeler' ][ 'in_href' ] );
      if( preg_match_all( $pattern, $data[ 'start_page' ], $matches, PREG_SET_ORDER ) ) {
        $base = $data[ 'url_source' ];
        foreach( $matches as $m ) {
          $data[ 'sourceInfo' ][ ] = array( 'url' => Utils::rel2abs( $m[ 1 ], $base ), 'linktext' => trim( $m[ 2 ] ) );
        }
      }
      else {
        Factory::getLogger()->warning( 'Rgex in_href pattern not found.' );
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