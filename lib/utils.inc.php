<?php
 /**
  * PEEL Document Downloader engine.
  *
  * Utility functions.
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

 /**
  * Utility functions.
  *
  * Various light-weight functions to be used in different contexts across
  * the application.
  */
  class Utils {  

   /**
    * Transforms a relative URL to fully qualifying URL.
    *
    * @param string $rel The relative URL to be transformed.
    * @param string $base The base URL (i.e. the URL of the current web page).
    *
    * @return string The fully qualifying URL, including the transfer protocol.
    */
    static function rel2abs( $rel, $base )
    {
        /* return if already absolute URL */
        if ( parse_url( $rel, PHP_URL_SCHEME ) != '' || substr( $rel, 0, 2 ) == '//' ) return $rel;
        /* queries and anchors */
        if ( $rel[ 0 ] == '#' || $rel[ 0 ]== '?' ) return $base . $rel;
        /* parse base URL and convert to local variables:
         $scheme, $host, $path */
        extract( parse_url( $base ) );
        /* remove non-directory element from path */
        $path = preg_replace( '#/[^/]*$#', '', $path );
        /* destroy path if relative url points to root */
        if ( $rel[ 0 ] == '/' ) $path = '';
        /* dirty absolute URL */
        $abs = "$host$path/$rel";
        /* replace '//' or '/./' or '/foo/../' with '/' */
        $re = array( '#(/\.?/)#', '#/(?!\.\.)[^/]+/\.\./#' );
        for( $n = 1; $n > 0; $abs = preg_replace( $re, '/', $abs, -1, $n ) ) { }
        /* absolute URL is ready! */
        return $scheme . '://' .$abs;
    }


  }

?>