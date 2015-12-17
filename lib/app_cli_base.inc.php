<?php
 /**
  * PEEL Document Downloader engine.
  *
  * Application base class for command-line invocations.
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

  require_once( DIR_LIB . '/i_client_application.inc.php' );

 /**
  * Base class for Peel command-line Application classes.
  *
  * Implements interface IClientApplication, providing basic functionality
  * for command-line (client) invocations.
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
  class AppCliBase implements IClientApplication {

   /**
    * The decorated (base) application instance.
    *
    * @var IApplication $mBase
    */
    private $mBase;

   /**
    * Constructor.
    *
    * @param IApplication $base The decorated (base) application instance.
    * @return void
    */
    public function __construct( $base ) {
      $this->mBase = $base;
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
      echo( "This is the CLI frontend speaking\n" );
      $this->mBase->run();
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