<?php
  define( 'DIR_BASE', '..' );
  define( 'DIR_LIB', '../lib' );
  define( 'DIR_CONF', '../conf' );
  require_once( DIR_LIB . '/factory.inc.php' );
  $app = Factory::getApplication();
  $app->run();
  Factory::releaseApplication( $app );
?>