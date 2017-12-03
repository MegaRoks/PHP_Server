<?php
namespace core;

class FactoryRouting 
{
    static function getController($uri) :  array //\core\Controller
    {
      $parts = explode("/", $uri);     
      $method_name = "index";     
      if (!empty($parts[2]))
      {
        $method_name = $parts[2];
      }
      
      $controller = null;
      $cname = "\\core\\".UCFirst(@$parts[1])."Controller";
      $file_name = str_replace("\\", "/",$cname.".class.php");
      
      if (!empty($parts[1]) && file_exists( getcwd().$file_name))
      {              
        $controller =  new $cname();      
      }
      else
      {
        $controller =  new \core\C404Controller();
      }     
      
      if (method_exists($controller, $method_name))
      {        
        return array($controller, $method_name);        
      }
      else
      {
        throw new \Exception("Error! Warning! Achtung! Danger!");
      }      
    }
}
?>
