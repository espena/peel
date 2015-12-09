<?php
  require_once( DIR_LIB . '/factory.inc.php' );
  require_once( DIR_LIB . '/i_logger.inc.php' );
  class LogFile implements ILogger {
    public function __construct() {
      $c = Factory::getConfig();
      $this->mLogFile = isset( $c[ 'logging' ][ 'error_log' ] )
                      ? $c[ 'logging' ][ 'error_log' ]
                      : '/var/log/peeler.log';
    }
    private function getPrefix( $level ) {
      $now = date("Y-m-d H:i:s");
      return sprintf( "%s - %s:", $now, str_pad( $level, 10, ' ', STR_PAD_RIGHT ) );
    }
    public function message( $str ) {
      
    }
    public function warning( $str ) {
      
    }
    public function error( $str ) {
      
    }
    private function write( $str ) {

    }
  }
?>