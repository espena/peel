<?php
  require_once( DIR_LIB . '/i_application.inc.php' );
  require_once( DIR_LIB . '/factory.inc.php' );
  class AppEnabler implements IApplication {
    private $mBase;
    private $mPathPeelersAvailable;
    private $mPathPeelersEnabled;
    public function __construct( $base ) {
      $this->mBase = $base;
    }
    private function disablePeeler( $peelerToDisable ) {
      if( empty( $peelerToDisable ) ) return;
      $path = sprintf( '%s/%s.conf', $this->mPathPeelersEnabled, $peelerToDisable );
      if( file_exists( $path ) ) {
        if( unlink( $path ) ) {
          Factory::getLogger()->message( "Peeler %s successfully disabled", $peelerToDisable );
        }
        else {
          Factory::getLogger()->error( "Unable to deacitvate peeler %s", $peelerToDisable );
        }
      }
    }
    private function enablePeeler( $peelerToEnable ) {
      if( empty( $peelerToEnable ) ) return;
      $source = sprintf( '%s/%s.conf', $this->mPathPeelersAvailable, $peelerToEnable );
      $destin = sprintf( '%s/%s.conf', $this->mPathPeelersEnabled, $peelerToEnable );
      if( file_exists( $source ) ) {
        if( copy( $source, $destin ) ) {
          Factory::getLogger()->message( "Peeler %s successfully enabled", $peelerToEnable );
        }
        else {
          Factory::getLogger()->error( "Unable to acitvate peeler %s", $peelerToEnable );
        }
      }
    }
    public function getConfig() {
      return $this->mBase->getConfig();
    }
    public function run() {
      $this->mBase->run();
      $c = $this->getConfig();
      $p = Factory::getParameters();
      $peelerToEnable = isset( $p[ 'e' ] ) ? $p[ 'e' ] : ( isset( $p[ 'enable' ] ) ? $p[ 'enable' ] : '' );
      $peelerToDisable = isset( $p[ 'd' ] ) ? $p[ 'd' ] : ( isset( $p[ 'disable' ] ) ? $p[ 'disable' ] : '' );
      $this->mPathPeelersAvailable = $this->resolvePath( $c, 'dir_available' );
      $this->mPathPeelersEnabled = $this->resolvePath( $c, 'dir_enabled' );
      $this->enablePeeler( $peelerToEnable );
      $this->disablePeeler( $peelerToDisable );
    }
    private function resolvePath( $c, $dirTag ) {
      if( substr( $c[ 'peeler_conf' ][ $dirTag ], 0, 1 ) != '/' ) {
        $path = $c[ 'config_directory' ] . '/' . $c[ 'peeler_conf' ][ $dirTag ];
      }
      else {
        $path = $c[ 'peeler_conf' ][ $dirTag ];
      }
      return $path;
    }
    public function terminate() {
      $this->mBase->terminate();
    }
  }
?>