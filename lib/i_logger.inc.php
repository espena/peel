<?php
  interface ILogger {
    public function message( $str );
    public function warning( $str );
    public function error( $str );
  }
?>