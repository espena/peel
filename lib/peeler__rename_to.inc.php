<?php
  require_once( DIR_LIB . '/i_peeler.inc.php' );
  require_once( DIR_LIB . '/factory.inc.php' );
  require_once( DIR_LIB . '/utils.inc.php' );
  class Peeler_renameTo implements IPeeler {
    private $mPeeler;
    private $mData;
    public function __construct( $peeler ) {
      $this->mPeeler = $peeler;
      $this->mData = array();
      $this->mMetadata = array();
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
    public function resolveDestinationPath( $dir, $sourceInfo ) {
      $c = $this->getConfig();
      $pattern = $c[ 'peeler' ][ 'rename_to' ];
      if( preg_match_all( '/\{([^\}]+)\}/', $pattern, $m, PREG_SET_ORDER ) ) {
        $tit = $pattern;
        foreach( $m as $tag ) {
          $tit = str_replace( $tag[ 0 ], isset( $sourceInfo[ 'metadata' ][ $tag[ 1 ] ] ) ? $sourceInfo[ 'metadata' ][ $tag[ 1 ] ] : 'x', $tit );
        }
      }
      return Utils::makeUniquePath( "$dir/$tit" );
    }
  }
?>