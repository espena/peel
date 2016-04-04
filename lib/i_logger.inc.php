<?php
  interface ILogger {
    public function message( $fmt /* ... printf-style args list */ );
    public function warning( $fmt /* ... printf-style args list */ );
    public function error( $fmt /* ... printf-style args list */ );
    public function getContent();
  }
?>