<?php
  define( 'DIR_BASE', '.' );
  define( 'DIR_LIB', DIR_BASE . '/lib' );
  define( 'DIR_TPL', DIR_BASE . '/tpl' );
  define( 'DIR_CONF', DIR_BASE . '/conf' );
  require_once( DIR_LIB . '/factory.inc.php' );
  $app = Factory::getApplication();
  $app->run();
  Factory::releaseApplication( $app );
?>