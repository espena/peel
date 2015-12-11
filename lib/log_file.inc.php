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
                      : DIR_BASE . '/log/peel.log';
      if( substr( $this->mLogFile, 0, 1 ) !== '/' ) {
        $this->mLogFile = DIR_BASE . '/' . $this->mLogFile;
      }
      $logDir = dirname( $this->mLogFile );
      if( !file_exists( $logDir ) ) {
        mkdir( $logDir, 0777, true );
      }
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
    public function message( $fmt /* printf-style args list */ ) {
      $this->write( $this->getPrefix( 'MESSAGE' ), func_get_args() );
    }
    public function warning( $fmt /* printf-style args list */ ) {
      $this->write( $this->getPrefix( 'WARNING' ), func_get_args() );
    }
    public function error( $fmt /* printf-style args list */ ) {
      $this->write( $this->getPrefix( 'ERROR' ), func_get_args() );
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
    private function write( $prefix, $args ) {
      $str = call_user_func_array( 'sprintf', $args );
      file_put_contents( $this->mLogFile, sprintf( "%s %s\n", $prefix, $str ), FILE_APPEND );
      $this->purge();
    }
  }
?>