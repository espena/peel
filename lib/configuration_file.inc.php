<?php
  require_once( DIR_LIB . '/factory.inc.php' );
  class ConfigurationFile {
    public static function parse() {
      $params = Factory::getParameters();
      $file = isset( $params[ 'conf' ] ) ? $params[ 'conf' ] : DIR_CONF . '/peel.conf';
      return parse_ini_file( $file, true, INI_SCANNER_NORMAL );
    }
  }
?>