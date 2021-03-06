<?php
  error_reporting( E_ALL );
  ini_set( 'display_errors', 1 );

  define( 'DIR_BASE', '..' );
  define( 'DIR_LIB', '../lib' );
  define( 'DIR_TPL', '../tpl' );
  define( 'DIR_CONF', '../conf' );
  require_once( DIR_LIB . '/factory.inc.php' );
  $app = Factory::getApplication();
  $app->run();
  Factory::releaseApplication( $app );
?>