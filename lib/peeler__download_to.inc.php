<?php
  require_once( DIR_LIB . '/i_peeler.inc.php' );
  require_once( DIR_LIB . '/factory.inc.php' );
  require_once( DIR_LIB . '/utils.inc.php' );
  class Peeler_downloadTo implements IPeeler {
    private $mPeeler;
    private $mData;
    public function __construct( $peeler ) {
      $this->mPeeler = $peeler;
      $this->mData = array();
    }
    public function start() {
      $this->mPeeler->start();
      $data = $this->mPeeler->getData();
      $log = Factory::getLogger();
      if( $dir = $this->getDestinationDir() ) {
        $this->download( $data, $dir );
      }
    }
    public function getData( $key = '' ) {
      return $this->mData;
    }
    public function getConfig() {
      return $this->mPeeler->getConfig();
    }
    private function download( $urlList, $dir ) {
      $log = Factory::getLogger();
      $scraper = Factory::createScraper();
      foreach( $urlList as $url ) {
        $log->message( "Downloading %s", basename( $url ) );
        $scraper->get( $url );
        $res = $scraper->getResponseCode();
        if( 200 == $res ) {
          $dest = $this->resolveDestinationPath( $dir, $url );
          $log->message( "Success! Writing %s", $dest );
          file_put_contents( $dest, $scraper->getResponseData() );
        }
        else {
          $log->error( "Server status %s", $res, basename( $url ) );
        }
      }
    }
    private function getDestinationDir() {
      $c = $this->getConfig();
      $dir = $c[ 'peeler' ][ 'download_to' ];
      if( !file_exists( $dir ) ) {
        $log->warning( "Directory %s does not exist", $dir );
        if( mkdir( $dir, 0777, true ) ) {
          $log->message( "Destination directory successfully created" );
        }
        else {
          $log->error( "Could not create destination directory" );
          $dir = FALSE;
        }
      }
      return $dir;
    }
    public function resolveDestinationPath( $dir, $url ) {
      return $this->mPeeler->resolveDestinationPath( $dir, $url );
    }
  }
?>