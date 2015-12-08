<?php
  require_once( DIR_LIB . '/factory.inc.php' );
  class ConfigurationFile {
    private static $mConfigData;
    public static function parse() {
      if( !self::$mConfigData ) {
        $params = Factory::getParameters();
        $file = isset( $params[ 'conf' ] ) ? $params[ 'conf' ] : DIR_CONF . '/peel.conf';
        self::$mConfigData = parse_ini_file( $file, true, INI_SCANNER_NORMAL );
      }
      return self::$mConfigData;
    }
  }
?>