<?php
  require_once( DIR_LIB . '/i_application.inc.php' );
  require_once( DIR_LIB . '/factory.inc.php' );
  class AppBase implements IApplication {
    public function getConfig() {
      return Factory::getConfig();
    }
    public function run() {

    }
    public function terminate() {

    }
  }
?>