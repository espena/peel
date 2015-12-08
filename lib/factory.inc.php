<?php
  require_once( DIR_LIB . '/app_base.inc.php' );
  require_once( DIR_LIB . '/app_cli_base.inc.php' );
  require_once( DIR_LIB . '/app_web_base.inc.php' );
  require_once( DIR_LIB . '/app_web_frontend.inc.php' );
  require_once( DIR_LIB . '/confifuration_file.inc.php' );
  class Factory {
    private static $mConfig;
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
      if( !$this->mConfig ) {
        $this->mConfig = ConfigurationFile::parse();
      }
      return $this->mConfig;
    }
    public static function releaseApplication( &$app ) {
      if( $app ) {
        $app->terminate();
        $app = null;
      }
    }
  }
?>