<?php
 /**
  * Peeler interface.
  *
  * Common implementation specification for all peelers.
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
  interface IPeeler {
   /**
    * Start peeler.
    */
    public function start();

   /**
    * Retrieve peeler data.
    */
    public function getData( $key = '' );

   /**
    * Retrieve peeler configuration.
    */
    public function getConfig();

   /**
    * Compile path and name for destination file.
    * @param string $dir Path to destination directory.
    * @param array $sourceInfo Associative array with information
    *        about the source file.
    * @return string Path and name for the destination file.
    */
    function resolveDestinationPath( $dir, $sourceInfo );
  }
?>