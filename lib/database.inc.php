<?php
  require_once( DIR_LIB . '/template.inc.php' );
  require_once( DIR_LIB . '/factory.inc.php' );
  class Database {
    private $mConfig;
    private $mDb;
    public function __construct( $config ) {
      $this->mConfig = $config;
      if( isset( $this->mConfig[ 'mysql' ] ) ) {
        $c = $this->mConfig[ 'mysql' ];
        try {
          $this->mDb =
            new MySQLi(
              $c[ 'host' ],
              $c[ 'user' ],
              $c[ 'password' ],
              '',
              isset( $c[ 'port' ] ) ? $c[ 'port' ] : '3306' );
          if( !$this->mDb->select_db( $c[ 'database' ] ) ) {
            $this->createDb();
          }
        }
        catch( Exception $e ) {
          Factory::getLogger()->error( $e->getMessage() );
        }
        finally {
          exit();
        }
      }
    }
    public function registerUrlDownloaded( $url, $peelerName, $renameTo, $status ) {
      $sql = sprintf( "CALL insertMetadata('%s','%s','%s','%s')",
                      $this->mDb->escape_string( $url ),
                      $this->mDb->escape_string( $peelerName ),
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
    private function createDb() {
      $tpl = new Template( DIR_BASE . '/db/create.tpl.sql' );
      $this->mDb->multi_query( $tpl->render( $this->mConfig[ 'mysql' ] ) );
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
            $log = Factory::getLogger();
            $log->error( $res->error );
          }
          $res->free();
        }
      }
    }
  }
?>