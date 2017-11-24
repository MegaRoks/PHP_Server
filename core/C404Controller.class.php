<?php
namespace core;
require_once("Controller.class.php");

class C404Controller extends \core\Controller {
  public function index()
  {
    echo "<h1>404 Not Found</h1>";
  }  
}
?>