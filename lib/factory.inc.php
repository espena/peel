<?php
  require_once( DIR_LIB . '/app_base.inc.php' );
  require_once( DIR_LIB . '/app_cli_base.inc.php' );
  require_once( DIR_LIB . '/app_web_base.inc.php' );
  require_once( DIR_LIB . '/app_web_frontend.inc.php' );
  require_once( DIR_LIB . '/configuration_file.inc.php' );
  class Factory {
    private static $mConfig;
    private static $mParams;
    public static function getApplication() {
      $app = new AppBase();
      if( PHP_SAPI == 'cli' ) {
        $app = new AppCliBase( $app );
      }
      else {
        $app = new AppWebBase( $app );
        $app = new AppWebFrontend( $app );
      }
      return $app;
    }
    public static function getConfig() {
      if( !self::$mConfig ) {
        self::$mConfig = ConfigurationFile::parse();
      }
      return self::$mConfig;
    }
    public static function getParameters() {
      if( !self::$mParams ) {
        self::$mParams = getopt( "c", array( 'conf' ) ) or array();
      }
      return self::$mParams;
    }
    public static function releaseApplication( &$app ) {
      if( $app ) {
        $app->terminate();
        $app = null;
      }
    }
  }
?>