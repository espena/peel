<?php
 /**
  * PEEL Document Downloader engine.
  *
  * Application base class.
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
  * Base class for all Peel Application classes.
  *
  * Implements interface IApplication, providing basic functionality common
  * to both web and command-line invocations.
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
  class AppBase implements IApplication {

   /**
    * Get configuration array.
    *
    * Returns the application configuration, including the settings for
    * individually enabled peelers.
    *
    * @return array The configuration parameters as key/value pairs.
    */
    public function getConfig() {
      return Factory::getConfig();
    }

   /**
    * Run application.
    *
    * Starts application execution.
    *
    * @return void
    */
    public function run() {

    }

   /**
    * Terminate application.
    *
    * Stops application execution.
    *
    * @return void
    */
    public function terminate() {

    }
  }
?>