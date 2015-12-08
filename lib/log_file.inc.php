<?php
  require_once( DIR_LIB . '/factory.inc.php' );
  require_once( DIR_LIB . '/i_logger.inc.php' );
  class LogFile implements ILogger {
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