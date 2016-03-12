<?php
 /**
  * PEEL Document Downloader engine.
  *
  * Peel Engine class.
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
  * Peel engine class.
  *
  * The class that does the actual job. Initializes and runs individual
  * peelers in sequence.
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
  class AppPeelEngine implements IApplication {

   /**
    * The decorated (base) application instance.
    *
    * @var IApplication $mBase
    */
    private $mBase;

   /**
    * Array of enabled peelers.
    *
    * @var IPeeler[] $mPeelers
    */
    private $mPeelers;

   /**
    * Constructor.
    *
    * @param IApplication $base The decorated (base) application instance.
    * @return void
    */
    public function __construct( $base ) {
      $this->mBase = $base;
      $this->mPeelers = array();
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
    * Starts application execution. Iterates through each enabled peeler,
    * creates the peeler object, passing in configuration data for
    * that peeler and runs it.
    *
    * @return void
    */
    public function run() {
      $this->mBase->run();
      $c = $this->getConfig();
      foreach( $c[ 'peelers' ] as $name => $peelerConf ) {
        $this->mPeelers[ $name ] = Factory::createPeeler( $peelerConf );
        $this->mPeelers[ $name ]->start();
        print_r( $this->mPeelers[ $name ]->getData() );
      }
    }

   /**
    * Terminate application.
    *
    * Stops application execution.
    *
    * @return void
    */
    public function terminate() {
      return $this->mBase->terminate();
    }
  }
?>