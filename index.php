<?php
  header('Content-Type: text/html; charset=utf-8');

  function __autoload($classname)
  {
    $classname = str_replace("\\", "/", $classname);
    $parts = explode("/", $classname);
    $last_name = $parts[count($parts)-1];
    $last_name = UCFirst($last_name);
    $classname = implode("/", $parts);
    if (!empty($classname) )
      {
        $cname = $classname;
        if (file_exists($cname.".class.php"))
        {
          require_once("{$cname}.class.php");
        }
      }
  }
  list($ctl, $method) = \core\FactoryRouting::getController($_SERVER["REQUEST_URI"]);
  $ctl->$method();
?>
