<?php
 /**
  * PEEL Document Downloader engine.
  *
  * Class for handling activation and deactivation of peelers.
  *
  * PHP version > 5.5
  *
  * @category   peel
  * @package    PEEL
  * @author     Espen Andersen <post@espenandersen.no>
  * @copyright  2016 The Author
  * @license    GNU General Public License, version 3
  * @link       https://github.com/espena/peel
  */

  require_once( DIR_LIB . '/i_application.inc.php' );
  require_once( DIR_LIB . '/factory.inc.php' );

 /**
  * Peeler enabler/disabler class.
  *
  * Implements interface IApplication, implements administration of
  * selected peelers. Invoked with the -e or -d commsnd-line arguments.
  *
  * /IApplication classes are instantiated in /Factory.
  *
  * @category   peel
  * @package    PEEL
  * @author     Espen Andersen <post@espenandersen.no>
  * @copyright  2016 The Author
  * @license    GNU General Public License, version 3
  * @link       https://github.com/espena/peel
  */
  class AppEnabler implements IApplication {

   /**
    * The decorated (base) application instance.
     *
    * @var IApplication $mBase
    */
    private $mBase;

   /**
    * Direcotry where config files for available peelers are located.
    *
    * @var string $mPathPeelersAvailable
    */
    private $mPathPeelersAvailable;

   /**
    * Direcotry where config files for enabled peelers are located.
    *
    * @var string $mPathPeelersEnabled
    */
    private $mPathPeelersEnabled;

   /**
    * Constructor
    *
    * @param IApplication $base The decorated (base) application instance.
    * @return void
    */
    public function __construct( $base ) {
      $this->mBase = $base;
    }

   /**
    * Disable activated peeler.
    *
    * Removes the configuration file for the selected peeler from the
    * peelers-enabled directory.
    *
    * @param string $peelerToDisable The name of the peeler that should be disabled.
    * @return void
    */
    private function disablePeeler( $peelerToDisable ) {
      if( empty( $peelerToDisable ) ) return;
      $path = sprintf( '%s/%s.conf', $this->mPathPeelersEnabled, $peelerToDisable );
      if( file_exists( $path ) ) {
        if( unlink( $path ) ) {
          Factory::getLogger()->message( "Peeler %s successfully disabled", $peelerToDisable );
        }
        else {
          Factory::getLogger()->error( "Unable to deacitvate peeler %s", $peelerToDisable );
        }
      }
    }

   /**
    * Enable available peeler.
    *
    * Copies the configuration file for the selected peeler from the
    * peelers-available directory to the peelers-enabled directory.
    *
    * @param string $peelerToEnable The name of the peeler that should be enabled.
    * @return void
    */
    private function enablePeeler( $peelerToEnable ) {
      if( empty( $peelerToEnable ) ) return;
      $source = sprintf( '%s/%s.conf', $this->mPathPeelersAvailable, $peelerToEnable );
      $destin = sprintf( '%s/%s.conf', $this->mPathPeelersEnabled, $peelerToEnable );
      if( !file_exists( $destin ) && file_exists( $source ) ) {
        if( symlink( $source, $destin ) ) {
          Factory::getLogger()->message( "Peeler %s successfully enabled", $peelerToEnable );
        }
        else {
          Factory::getLogger()->error( "Unable to acitvate peeler %s", $peelerToEnable );
        }
      }
    }

    private function runPeeler( $peelerToRun ) {
      if( empty( $peelerToRun ) ) return;
      $c = $this->getConfig();
      foreach( $c[ 'peelers' ] as $peelerConf ) {
        if( $peelerConf[ 'peeler' ][ 'key' ] == $peelerToRun ) {
          $peeler = Factory::createPeeler( $peelerConf );
          $peeler->start();
        }
      }
    }

    private function resetPeeler( $peelerToReset ) {
      if( empty( $peelerToReset ) ) return;
      $db = Factory::getDatabase();
      $db->resetPeeler( $peelerToReset );
      Factory::getLogger()->message( "Peeler %s has been reset", $peelerToReset );
    }

   /**
    * Get configuration array.
    *
    * Returns the application configuration, including the settings for
    * individually enabled peelers.
    *
    * @return array The configuration parameters as key/value pairs.
    */
    public function getConfig() {
      return $this->mBase->getConfig();
    }

   /**
    * Run application.
    *
    * Starts application execution.
    *
    * @return void
    */
    public function run() {
      $this->mBase->run();
      $c = $this->getConfig();
      $p = Factory::getParameters();
      $peelerToEnable = isset( $p[ 'e' ] ) ? $p[ 'e' ] : ( isset( $p[ 'enable' ] ) ? $p[ 'enable' ] : '' );
      $peelerToDisable = isset( $p[ 'd' ] ) ? $p[ 'd' ] : ( isset( $p[ 'disable' ] ) ? $p[ 'disable' ] : '' );
      $peelerToReset = isset( $p[ 'r' ] ) ? $p[ 'r' ] : ( isset( $p[ 'reset' ] ) ? $p[ 'reset' ] : '' );
      $peelerToRun = isset( $p[ 'x' ] ) ? $p[ 'x' ] : ( isset( $p[ 'execute' ] ) ? $p[ 'execute' ] : '' );
      $this->mPathPeelersAvailable = $this->resolvePath( $c, 'dir_available' );
      $this->mPathPeelersEnabled = $this->resolvePath( $c, 'dir_enabled' );
      $this->enablePeeler( $peelerToEnable );
      $this->disablePeeler( $peelerToDisable );
      $this->resetPeeler( $peelerToReset );
      $this->runPeeler( $peelerToRun );
    }

   /**
    * Resolve path to peeler configuration file directory.
    *
    * Returns the correct path to the selected configuration directory. Paths that are not
    * absolute are made relative to the current configuration directory.
    *
    * @param array $c Application configuration.
    * @param string $dirTag Directory for which to retrieve the path.
    *        May be one of the following values:.
    *        <ul>
    *          <li>
    *            <b>'dir_available'</b> - the directory where <i>available</i> peeler configuration files
    *            are located.
    *          </li>
    *          <li>
    *            <b>'dir_enabled'</b> - the directory where <i>enabled</i> peeler configuration files
    *            are located.
    *          </li>
    *        </ul>
    *
    * @return string Normalized file path to selected directory.
    */
    private function resolvePath( $c, $dirTag ) {
      if( substr( $c[ 'peeler_conf' ][ $dirTag ], 0, 1 ) != '/' ) {
        $path = $c[ 'config_directory' ] . '/' . $c[ 'peeler_conf' ][ $dirTag ];
      }
      else {
        $path = $c[ 'peeler_conf' ][ $dirTag ];
      }
      return realpath( $path );
    }

   /**
    * Terminate application.
    *
    * Stops application execution.
    *
    * @return void
    */
    public function terminate() {
      $this->mBase->terminate();
    }
  }
?>