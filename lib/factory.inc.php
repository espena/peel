<?php
  require_once( DIR_LIB . '/app_base.inc.php' );
  require_once( DIR_LIB . '/app_cli_base.inc.php' );
  require_once( DIR_LIB . '/app_web_base.inc.php' );
  require_once( DIR_LIB . '/app_web_frontend.inc.php' );
  require_once( DIR_LIB . '/app_enabler.inc.php' );
  require_once( DIR_LIB . '/configuration_file.inc.php' );
  require_once( DIR_LIB . '/log_file.inc.php' );
  class Factory {
    private static $mConfig;
    private static $mLogger;
    private static $mParams;
    public static function getApplication() {
      $app = new AppBase();
      if( PHP_SAPI == 'cli' ) {
        $app = new AppCliBase( $app );
        $p = self::getParameters();
        if( isset( $p[ 'e' ] ) || isset( $p[ 'enable' ] ) || isset( $p[ 'd' ] ) || isset( $p[ 'disable' ] ) ) {
          $app = new AppEnabler( $app );
        }
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
    public static function getLogger() {
      if( !self::$mLogger ) {
        self::$mLogger = new LogFile();
      }
      return self::$mLogger;
    }
    public static function getParameters() {
      if( !self::$mParams ) {
        self::$mParams = getopt( "c:e:d:", array( 'conf:', 'enable:', 'disable:' ) ) or array();
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