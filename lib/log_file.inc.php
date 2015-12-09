<?php
  require_once( DIR_LIB . '/factory.inc.php' );
  require_once( DIR_LIB . '/i_logger.inc.php' );
  class LogFile implements ILogger {
    public function __construct() {
      $c = Factory::getConfig();
      $this->mLogFile = isset( $c[ 'logging' ][ 'error_log' ] )
                      ? $c[ 'logging' ][ 'error_log' ]
                      : '/var/log/peel.log';
    }
    private function getPrefix( $level ) {
      $now = date("Y-m-d H:i:s");
      return sprintf( "%s - %s:", $now, str_pad( $level, 10, ' ', STR_PAD_RIGHT ) );
    }
    public function message( $str ) {
      $this->write( $this->getPrefix( 'MESSAGE' ), $str );
    }
    public function warning( $str ) {
      $this->write( $this->getPrefix( 'WARNING' ), $str );
    }
    public function error( $str ) {
      $this->write( $this->getPrefix( 'ERROR' ), $str );
    }
    private function write( $prefix, $str ) {
      file_put_contents( $this->mLogFile, sprintf( "%s %s\n", $prefix, $str ) );
    }
  }
?>