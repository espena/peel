<?php
  require_once( DIR_LIB . '/template.inc.php' );
  require_once( DIR_LIB . '/factory.inc.php' );
  class Database {
    private $mConfig;
    private $mDb;
    public function __construct( $config ) {
      $this->mConfig = $config;
      if( isset( $this->mConfig[ 'database' ] ) ) {
        $c = $this->mConfig[ 'database' ];
        try {
          $this->mDb =
            new MySQLi(
              $c[ 'host' ],
              $c[ 'user' ],
              $c[ 'password' ],
              '',
              isset( $c[ 'port' ] ) ? $c[ 'port' ] : '3306' );
          if( !$this->mDb->select_db( $c[ 'database_name' ] ) ) {
            $this->createDb();
          }
        }
        catch( Exception $e ) {
          Factory::getLogger()->error( $e->getMessage() );
          exit();
        }
      }
    }
    public function log( $message, $level ) {
      $sql = sprintf( "CALL log('%s','%s')",
                      $this->mDb->escape_string( $message ),
                      $this->mDb->escape_string( $level ) );
      $this->mDb->multi_query( $sql );
      $this->flushResults();
    }
    public function verifyUser( $username, $password ) {
      $row = null;
      $sql = sprintf( "CALL verifyUser('%s','%s')",
                      $this->mDb->escape_string( $username ),
                      $this->mDb->escape_string( $password ) );
      $this->mDb->multi_query( $sql );
      if( $res = $this->getFirstResult() ) {
        $row = $res->fetch_assoc();
        $res->free();
      }
      $this->flushResults();
      return $row;
    }
    public function registerUrlDownloaded( $url, $peelerName, $peelerKey, $renameTo, $status ) {
      $sql = sprintf( "CALL insertMetadata('%s','%s','%s','%s','%s')",
                      $this->mDb->escape_string( $url ),
                      $this->mDb->escape_string( $peelerName ),
                      $this->mDb->escape_string( $peelerKey ),
                      $this->mDb->escape_string( $renameTo ),
                      $this->mDb->escape_string( $status ) );
      $this->mDb->multi_query( $sql );
      $this->flushResults();
    }
    public function urlDownloaded( $url ) {
      $sql = sprintf( "CALL fetchMetadataByUrl('%s')", $this->mDb->escape_string( $url ) );
      $this->mDb->multi_query( $sql );
      if( $res = $this->getFirstResult() ) {
        $n = $res->num_rows;
        $res->free();
      }
      else {
        $n = 0;
      }
      $this->flushResults();
      return $n > 0;
    }
    public function resetPeeler( $peelerToReset ) {
      $sql = sprintf( "CALL resetPeeler('%s')",
                      $this->mDb->escape_string( $peelerToReset ) );
      $this->mDb->multi_query( $sql );
      $this->flushResults();
    }
    public function getPeelerInfoStatus( $peelerKey ) {
      $sql = sprintf( "CALL getPeelerInfoStatus('%s')",
                      $this->mDb->escape_string( $peelerKey ) );
      $this->mDb->multi_query( $sql );
      if( $res = $this->getFirstResult() ) {
        $row = $res->fetch_assoc();
      }
      else {
        $row = array();
      }
      $this->flushResults();
      return $row;
    }
    private function createDb() {
      $tpl = new Template( DIR_BASE . '/db/create.tpl.sql' );
      $sql = $tpl->render( $this->mConfig[ 'database' ] );
      $this->mDb->multi_query( $sql );
      $this->flushResults();
    }
    private function getFirstResult() {
      $res = null;
      if( $this->mDb->more_results() ) {
        $res = $this->mDb->store_result();
      }
      return $res;
    }
    private function flushResults() {
      while( $this->mDb->more_results() ) {
        $this->mDb->next_result();
        if( $res = $this->mDb->store_result() ) {
          if( $res->errorno ) {
            Factory::getLogger()->error( $res->error );
          }
          $res->free();
        }
      }
    }
  }
?>