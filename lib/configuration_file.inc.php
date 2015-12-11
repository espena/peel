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
        self::parseEnabled();
      }
      return self::$mConfigData;
    }
    private static function parseEnabled() {
      self::$mConfigData[ 'peelers' ] = array();
      $cEnabledDir = sprintf( '%s/%s', self::$mConfigData[ 'config_directory' ], self::$mConfigData[ 'peeler_conf' ][ 'dir_enabled' ] );
      $filePattern = sprintf( '%s/*.conf', $cEnabledDir );
      $confEnabled = glob( $filePattern );
      foreach( $confEnabled as $confFile ) {
        $c = parse_ini_file( $confFile, true, INI_SCANNER_NORMAL );
        $c[ 'config_file_path' ] = $confFile;
        array_push( self::$mConfigData[ 'peelers' ], $c );
      }
      print_r( self::$mConfigData );
    }
  }
?>