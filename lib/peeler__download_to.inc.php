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
      $log = Factory::getLogger();
      if( $dir = $this->getDestinationDir() ) {
        $this->download( $dir );
      }
    }
    public function getData( $key = '' ) {
      return $this->mData;
    }
    public function getConfig() {
      return $this->mPeeler->getConfig();
    }
    private function download( $dir ) {
      $c = $this->getConfig();
      $db = Factory::getDatabase();
      $log = Factory::getLogger();
      $data = $this->mPeeler->getData();
      $scraper = Factory::createScraper();
      foreach( $data as $sourceInfo ) {
        $url = $sourceInfo[ 'url' ];
        $log->message( "Found %s", basename( $url ) );
        $scraper->get( $url );
        sleep( 1.0 );
        $res = $scraper->getResponseCode();
        if( $dest = $this->resolveDestinationPath( $dir, $sourceInfo ) ) {
          if( $res == 200 ) {
            $log->message( "Writing %s", $dest );
            try {
              file_put_contents( $dest, $scraper->getResponseData() );
              $db->registerUrlDownloaded( $url, $c[ 'peeler' ][ 'name' ], $c[ 'peeler' ][ 'key' ], $dest, 'naming_ok' );
            }
            catch( Exception $ex ) {
              $log->error( $ex->getMessage() );
            }
          }
          else {
            $log->error( "Server status %s", $res, $url );
          }
        }
      }
    }
    private function getDestinationDir() {
      $c = $this->getConfig();
      $dir = $c[ 'peeler' ][ 'download_to' ];
      if( substr( $dir, 0, 1 ) != '/' ) {
        $dir = sprintf( '%s/%s', DIR_BASE, $dir );
      }


      $log = Factory::getLogger();
      try {
        mkdir( $dir, 0777, true );
        $log->message( "Destination directory successfully created" );
      }
      catch( Exception $ex ) {
        $log->error( "%s (%s)", $ex->getMessage(), $ex->getCode() );
        $dir = FALSE;
      }

      /*
      if( !file_exists( $dir ) || !is_link( $dir ) ) {
        $log = Factory::getLogger();
        $log->warning( "Directory %s does not exist", $dir );
        try {
          mkdir( $dir, 0777, true );
          $log->message( "Destination directory successfully created" );
        }
        catch( Exception $ex ) {
          $log->error( $ex->getMessage() );
          $dir = FALSE;
        }
      }
      */

      return $dir;
    }
    public function resolveDestinationPath( $dir, $sourceInfo ) {
      return $this->mPeeler->resolveDestinationPath( $dir, $sourceInfo );
    }
  }
?>