<?php
namespace core;

class Controller 
{
  public function getHeader()
  {
    return "Content-type: text/json;";
  }
  
  public function getPage()
  {
    echo "Impossible entrance";
  }
  
  public function index()
  {
    echo "Index method of ".get_class($this);
  }  
}
?>