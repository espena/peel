<?php
  interface IApplication {
    public function getConfig();
    public function run();
    public function terminate();
  }
?>