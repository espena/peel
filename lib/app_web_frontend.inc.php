<?php
 /**
  * PEEL Document Downloader engine.
  *
  * Web application frontend class.
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

  require_once( DIR_LIB . '/factory.inc.php' );

 /**
  * Peel web application frontend class.
  *
  * Master web interface, implements IWebApplication. Provides framework for web
  * administration GUI.
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
  class AppWebFrontend implements IWebApplication {

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
      // TODO: Implement actions here.
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
      $this->mBase->tpl( $idt );
    }
  }
?>