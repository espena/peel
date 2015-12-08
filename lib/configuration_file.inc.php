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
      }
      return self::$mConfigData;
    }
  }
?>