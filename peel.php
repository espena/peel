<?php
  define( 'DIR_LIB', 'lib' );
  require_once( DIR_LIB . '/factory.inc.php' );
  $app = Factory::getApplication();
  $app->run();
  Factory::releaseApplication( $app );
?>