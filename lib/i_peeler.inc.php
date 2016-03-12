<?php
  interface IPeeler {
    public function start();
    public function getData( $key = '' );
    public function getConfig();
  }
?>