<?php
 /**
  * PEEL Document Downloader engine.
  *
  * Web application base class..
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

  require_once( DIR_LIB . '/i_web_application.inc.php' );

 /**
  * Base class for Peel web application classes.
  *
  * Implements interface IWebApplication, providing basic functionality
  * for web (browser) invocations.
  *
  * /IWebApplication classes are instantiated in /Factory.
  *
  * @category   peel
  * @package    PEEL
  * @author     Espen Andersen <post@espenandersen.no>
  * @copyright  2016 The Author
  * @license    GNU General Public License, version 3
  * @link       https://github.com/espena/peel
  */
  class AppWebBase implements IWebApplication {

   /**
    * The decorated (base) application instance.
    *
    * @var IWebApplication $mBase
    */
    private $mBase;

   /**
    * Constructor.
    *
    * @param IWebApplication $base The decorated (base) application instance.
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
      $this->mBase->run();
      $this->tpl( 'main' );
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

   /**
    * Invoke template.
    *
    * Render HTML template file and write content to output buffer.
    *
    * @param string $idt ID of template to be processed.
    * @return void
    */
    public function tpl( $idt ) {
      
    }
  }
?>