<?php
  require_once( DIR_LIB . '/i_peeler.inc.php' );
  require_once( DIR_LIB . '/factory.inc.php' );
  require_once( DIR_LIB . '/utils.inc.php' );
  class Peeler_linktextMetadata implements IPeeler {
    private $mPeeler;
    public function __construct( $peeler ) {
      $this->mPeeler = $peeler;
    }
    public function start() {
      $this->mPeeler->start();
      $data = &$this->mPeeler->getData();
      $c = $this->getConfig();
      foreach( $data[ 'sourceInfo' ] as $k => &$sourceInfo ) {
        if( preg_match( '/' . $c[ 'peeler' ][ 'linktext_metadata' ] . '/', $sourceInfo[ 'linktext' ], $m ) == 1 ) {
          $sourceInfo[ 'metadata' ] = Utils::purgeNumericSubscripts( $m );
        }
        else {
          $log = Factory::getLogger();
          $log->warning( "Missing or unexpected information in link text: %s (%s)", $sourceInfo[ 'linktext' ], $sourceInfo[ 'url' ] );
          unset( $data[ 'sourceInfo' ][ $k ] );
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
      return $this->mPeeler->resolveDestinationPath( $dir, $sourceInfo[ 'url' ] );
    }
  }
?>