<?php
namespace core; /// new \core\UsersModel()
class UsersModel // extends Model // implements IModel
{
  protected $mysqli; 
  public function __construct()
  {
    $this->mysqli = new \mysqli("localhost", "root", "", "steam_users");
    if ($this->mysqli->connect_errno) {
        printf("Не удалось подключиться: %s\n", $mysqli->connect_error);
        exit();
    }    
  }
  
  public function getAll() : array
  {
     $ret = array();   
     if ($result = $this->mysqli->query("SELECT * FROM users LIMIT 10;")) 
     {   
        while ($row = $result->fetch_array(MYSQLI_ASSOC))
        {
          $ret[] = $row;         
        }  
        $result->close();
     }    
     return $ret;    
  }
}
?>