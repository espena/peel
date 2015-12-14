<?php
  interface IPeeler {
    public function start();
    public function getCurlHandle();
    public function closeCurlHandle( &$ch );
  }
?>