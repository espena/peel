<?php
  require_once( DIR_LIB . '/i_application.inc.php' );
  interface IWebApplication extends IApplication {
    public function tpl( $idt );
  }
?>