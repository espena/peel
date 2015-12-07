<?php
  require_once( DIR_LIB . '/app_base.inc.php' );
  require_once( DIR_LIB . '/app_cli_base.inc.php' );
  require_once( DIR_LIB . '/app_web_base.inc.php' );
  require_once( DIR_LIB . '/app_web_frontend.inc.php' );
  class Factory {
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
    public static function releaseApplication( &$app ) {
      if( $app ) {
        $app->terminate();
        $app = null;
      }
    }
  }
?>