<?php
  require_once( DIR_LIB . '/factory.inc.php' );
  require_once( DIR_LIB . '/i_logger.inc.php' );
  class LogFile implements ILogger {
    private $mLogFile;
    private $mMaxSize;
    private $mConfig;
    public function __construct() {
      $this->mConfig = Factory::getConfig();
      $this->mLogFile = isset( $this->mConfig[ 'logging' ][ 'log_file' ] )
                      ? $this->mConfig[ 'logging' ][ 'log_file' ]
                      : '/var/log/peel.log';
      $this->mMaxSize = $this->mConfig[ 'logging' ][ 'max_size' ];
      $this->mMaxSize = str_replace( ' ', '', $this->mMaxSize );
      if( preg_match( '/^[0-9]+[0-9KMG]$/i', $this->mMaxSize ) !== false ) {
        $this->mMaxSize = str_replace( 'K', '000', $this->mMaxSize );
        $this->mMaxSize = str_replace( 'M', '000000', $this->mMaxSize );
        $this->mMaxSize = str_replace( 'G', '000000000', $this->mMaxSize );
      }
      else {
        $this->mMaxSize = '1000000';
      }
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
    private function purge() {
      $size = filesize( $this->mLogFile );
      if( $size >= $this->mMaxSize ) {
        $maxSize = floor( $this->mMaxSize * 0.75 );
        $fh = fopen( $this->mLogFile, "r+" );
        $start=ftell( $fh );
        fseek( $fh, -$maxSize, SEEK_END );
        $drop = fgets( $fh );
        $offset = ftell( $fh );
        for ( $x=0; $x < $maxSize; $x++ ) {
            fseek( $fh, $x + $offset );
            $c = fgetc( $fh );
            fseek( $fh, $x );
            fwrite( $fh, $c );
        }
        ftruncate( $fh, $maxSize - strlen( $drop ) );
        fclose( $fh );
      }
    }
    private function write( $prefix, $str ) {
      file_put_contents( $this->mLogFile, sprintf( "%s %s\n", $prefix, $str ) );
      $this->purge();
    }
  }
?>