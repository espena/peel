<?php
  require_once( DIR_LIB . '/factory.inc.php' );
  class ConfigurationFile {
    private static $mConfigData;
    private static function createDirectories() {
      foreach( self::$mConfigData[ 'peeler_conf' ] as $dirName ) {
        $dirName = self::$mConfigData[ 'config_directory' ] . '/' . $dirName;
        if( !file_exists( $dirName ) ) {
          mkdir( $dirName, 0755 );
        }
      }
    }
    public static function parse() {
      if( !self::$mConfigData ) {
        $params = Factory::getParameters();
        $file = isset( $params[ 'conf' ] ) ? $params[ 'conf' ] : DIR_CONF . '/peel.conf';
        self::$mConfigData = parse_ini_file( $file, true, INI_SCANNER_NORMAL );
        self::$mConfigData[ 'config_file_path' ] = $file;
        self::$mConfigData[ 'config_directory' ] = dirname( $file );
        self::createDirectories();
        self::parsePeelers();
      }
      return self::$mConfigData;
    }
    private static function parsePeelers() {
      self::$mConfigData[ 'peelers' ] = array();
      $aDir = sprintf( '%s/%s', self::$mConfigData[ 'config_directory' ], self::$mConfigData[ 'peeler_conf' ][ 'dir_available' ] );
      $eDir = sprintf( '%s/%s', self::$mConfigData[ 'config_directory' ], self::$mConfigData[ 'peeler_conf' ][ 'dir_enabled' ] );
      $fileAvailablePattern = sprintf( '%s/*.conf', $aDir );
      $confFiles = glob( $fileAvailablePattern );
      foreach( $confFiles as $confFile ) {
        $key = strtolower( basename( $confFile, '.conf' ) );
        $c = parse_ini_file( $confFile, true, INI_SCANNER_NORMAL );
        $c[ 'config_file_path' ] = $confFile;
        $c[ 'peeler' ][ 'status' ] = is_link( sprintf( '%s/%s.conf', $eDir, basename( $confFile, '.conf' ) ) ) ? 'enabled' : 'disabled';
        $c[ 'peeler' ][ 'key' ] = $key;
        $c[ 'peeler' ] = array_merge( $c[ 'peeler' ], Factory::getDatabase()->getPeelerInfoStatus( $key ) );
        $tmp = $c[ 'peeler' ][ 'data_timestamp' ];
        unset( $c[ 'peeler' ][ 'data_timestamp' ] );
        $c[ 'peeler' ][ 'hash' ] = md5( json_encode( $c[ 'peeler' ] ) );
        $c[ 'peeler' ][ 'data_timestamp' ] = $tmp;
        self::$mConfigData[ 'peelers' ][] = $c;
      }
    }
  }
?>