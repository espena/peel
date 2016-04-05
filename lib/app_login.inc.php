<?php
 /**
  * PEEL Document Downloader engine.
  *
  * Login base class.
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
  require_once( DIR_LIB . '/template.inc.php' );
  require_once( DIR_LIB . '/factory.inc.php' );

 /**
  * Base class for Peel user authentication.
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
  class AppLogin implements IWebApplication {

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
      if( isset( $_GET[ 'logout' ] ) ) {
        unset( $_SESSION[ 'login' ] );
        header( 'Location: /' );
        exit();
      }
      else if( isset( $_POST[ 'u' ] ) && isset( $_POST[ 'p' ] ) ) {
        $_SESSION[ 'post' ] = $_POST;
        header( 'Location: /' );
        exit();
      }
      if( $this->authenticate() ) {
        $this->mBase->run();
      }
      else {
        $this->tpl( 'login' );
      }
    }

    private function authenticate() {
      if( empty( $_SESSION[ 'login' ] ) && isset( $_SESSION[ 'post' ] ) ) {
        $db = Factory::getDatabase();
        $_SESSION[ 'login' ] = $db->verifyUser( $_SESSION[ 'post' ][ 'u' ], $_SESSION[ 'post' ][ 'p' ] );
      }
      unset( $_SESSION[ 'post' ] );
      return !empty( $_SESSION[ 'login' ] );
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
      if( method_exists( $this->mBase, 'tpl' ) ) {
        $this->mBase->tpl( $idt );
      }
    }
  }
?>