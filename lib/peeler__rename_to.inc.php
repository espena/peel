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
          $tit = str_replace( $tag[ 0 ], $this->expand( $sourceInfo[ 'metadata' ], $tag[ 1 ] ), $tit );
        }
      }
      return Utils::makeUniquePath( "$dir/$tit" );
    }
    private function expand( $metadata, $tag ) {
      $exp = '';
      $parts = explode( '|', $tag );
      if( isset( $metadata[ $parts[ 0 ] ] ) ) {
        $exp = trim( $metadata[ $parts[ 0 ] ] );
        for( $i = 1; $i < count( $parts ); $i++ ) {
          $params = array();
          if( preg_match( '/^([^\(]+)\(([^\)]*)\)$/i', $parts[ $i ], $m ) ) {
            $func = $m[ 1 ];
            $params = explode( ',', $m[ 2 ] );
          }
          else {
            $func = strtolower( trim( $parts[ $i ] ) );
          }
          $exp = $this->execute( $func, $params, $exp );
        }
      }
      return $exp;
    }
    private function execute( $func, $params, $exp ) {
      switch( $func ) {
        case 'lpad':
        case 'rpad':
          if( count( $params ) >= 2 ) {
            $exp = str_pad( $exp, $params[ 0 ], $params[ 1 ], $func == 'lpad' ? STR_PAD_LEFT : STR_PAD_RIGHT );
          }
          break;
      }
      return $exp;
    }
  }
?>