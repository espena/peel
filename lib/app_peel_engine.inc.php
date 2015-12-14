<?php
  require_once( DIR_LIB . '/i_application.inc.php' );
  require_once( DIR_LIB . '/factory.inc.php' );
  class AppPeelEngine implements IApplication {
    private $mBase;
    private $mPeelers;
    public function __construct( $base ) {
      $this->mBase = $base;
      $this->mPeelers = array();
    }
    public function getConfig() {
      return $this->mBase->getConfig();
    }
    public function run() {
      return $this->mBase->run();
      $c = $this->getConfig();
      foreach( $c[ 'peelers' ] as $name => $peelerConf ) {
        $this->mPeelers[ $name ] = Factory::createPeeler( $peelerConf );
      }
    }
    public function terminate() {
      return $this->mBase->terminate();
    }
  }
?>