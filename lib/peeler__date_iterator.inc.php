<?php
  require_once( DIR_LIB . '/i_peeler.inc.php' );
  require_once( DIR_LIB . '/factory.inc.php' );
  require_once( DIR_LIB . '/template.inc.php' );
  require_once( DIR_LIB . '/utils.inc.php' );
  class Peeler_dateIterator implements IPeeler {
    private $mPeeler;
    private $mData;
    public function __construct( $peeler ) {
      $this->mPeeler = $peeler;
      $this->mData = array();
    }
    private function deriveIterationUrls( $config ) {
      $urls = array();
      $urlTpl = new Template( $config[ 'peeler' ][ 'url_start_page' ] );
      $config[ 'peeler' ][ 'url_start_page' ] = array();
      $begin = strtotime( $config[ 'peeler' ][ 'date_iteration_begin' ] );
      $end = strtotime( $config[ 'peeler' ][ 'date_iteration_end' ] );
      $interval = strtotime( $config[ 'peeler' ][ 'date_iteration_interval' ] ) - time();
      for( $t = $begin; $t <= $end; $t += $interval ) {
        $urls[] = $urlTpl->render( getdate( $t ) );
      }
      return $urls;
    }
    public function start() {
      $config = &$this->mPeeler->getConfig();
      $urls = $this->deriveIterationUrls( $config );
      for( $i = 0; $i < count( $urls ); $i++ ) {
        $config[ 'peeler' ][ 'url_start_page' ] = $urls[ $i ];
        $this->mPeeler->start();
      }
    }
    public function &getData() {
      return $this->mPeeler->getData();
    }
    public function &getConfig() {
      return $this->mPeeler->getConfig();
    }
    private function getDestinationDir() {
      return $mPeeler->getDestinationDir();
    }
    public function resolveDestinationPath( $dir, $sourceInfo ) {
      return $this->mPeeler->resolveDestinationPath( $dir, $sourceInfo );
    }
  }
?>