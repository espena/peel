<?php
 /**
  * Peeler factory.
  *
  * Creates peelers and various other objects.
  *
  * PHP version > 5.5
  *
  * @category   peel
  * @package    PEEL
  * @author     Espen Andersen <post@espenandersen.no>
  * @copyright  2016 Espen Andersen
  * @license    GNU General Public License, version 3
  * @link       https://github.com/espena/peel
  */

  require_once( DIR_LIB . '/app_base.inc.php' );
  require_once( DIR_LIB . '/app_cli_base.inc.php' );
  require_once( DIR_LIB . '/app_web_base.inc.php' );
  require_once( DIR_LIB . '/app_web_frontend.inc.php' );
  require_once( DIR_LIB . '/app_enabler.inc.php' );
  require_once( DIR_LIB . '/app_peel_engine.inc.php' );
  require_once( DIR_LIB . '/app_dropbox.inc.php' );
  require_once( DIR_LIB . '/peeler__basic.inc.php' );
  require_once( DIR_LIB . '/peeler__in_href.inc.php' );
  require_once( DIR_LIB . '/peeler__url_metadata.inc.php' );
  require_once( DIR_LIB . '/peeler__rename_to.inc.php' );
  require_once( DIR_LIB . '/peeler__unique_by.inc.php' );
  require_once( DIR_LIB . '/peeler__download_to.inc.php' );
  require_once( DIR_LIB . '/database.inc.php' );
  require_once( DIR_LIB . '/scraper.inc.php' );
  require_once( DIR_LIB . '/configuration_file.inc.php' );
  require_once( DIR_LIB . '/log_file.inc.php' );

  class Factory {
    private static $mConfig;
    private static $mLogger;
    private static $mDatabase;
    private static $mParams;
    public static function createPeeler( $conf ) {
      $peeler = new Peeler_basic( $conf );
      if( !empty( $conf[ 'peeler' ][ 'in_href' ] ) ) {
        $peeler = new Peeler_inHref( $peeler );
      }
      if( !empty( $conf[ 'peeler' ][ 'url_metadata' ] ) ) {
        $peeler = new Peeler_urlMetadata( $peeler );
      }
      if( !empty( $conf[ 'peeler' ][ 'rename_to' ] ) ) {
        $peeler = new Peeler_renameTo( $peeler );
      }
      if( !empty( $conf[ 'peeler' ][ 'unique_by' ] ) ) {
        $peeler = new Peeler_uniqueBy( $peeler );
      }
      if( !empty( $conf[ 'peeler' ][ 'download_to' ] ) ) {
        $peeler = new Peeler_downloadTo( $peeler );
      }
      return $peeler;
    }
    public static function createScraper() {
      return new Scraper();
    }
    public static function getApplication() {
      $app = new AppBase();
      if( PHP_SAPI == 'cli' ) {
        $app = new AppCliBase( $app );
        $p = self::getParameters();
        if( isset( $p[ 'e' ] ) || isset( $p[ 'enable' ] ) || isset( $p[ 'd' ] ) || isset( $p[ 'disable' ] ) ) {
          $app = new AppEnabler( $app );
        }
        else {
          $app = new AppPeelEngine( $app );
        }
      }
      else {
        $app = new AppWebBase( $app );
        $app = new AppWebFrontend( $app );
        //$app = new AppDropbox( $app );
      }
      return $app;
    }
    public static function getConfig() {
      if( !self::$mConfig ) {
        self::$mConfig = ConfigurationFile::parse();
      }
      return self::$mConfig;
    }
    public static function getDatabase() {
      if( !self::$mDatabase ) {
        self::$mDatabase = new Database( self::getConfig() );
      }
      return self::$mDatabase;
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