<?php
namespace core;
class UsersController extends \core\Controller{
  public  $model = null; //\core\UsersModel
  
  public function __construct(){
    $this->model = new \core\UsersModel();    
  }
  
  public function index(){
    $data = $this->model->getAll();    
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
  }
  
  public function add(){
      header('Content-Type: application/json; charset=utf-8');
      $params = explode("?", $_SERVER["REQUEST_URI"]);  
      $params = @$params[1];   
      parse_str($params, $arr);

      if (!empty($params) && !empty($arr["usersid"])){   
        $mysqli = new \mysqli("localhost", "root", "", "steam_users"); 
        $mysqli -> query(sprintf( "INSERT INTO users (usersid) VALUES( '".$arr["usersid"]."');"));
        
        $steamid = implode($arr);
        $apikey = "5BC0F61DCDAFEDB5E0DB2A80D0D1280E";
        $ISteamUser = "http://api.steampowered.com/ISteamUser/";
        $IPlayerService = "http://api.steampowered.com/IPlayerService/";
        
        $GetPlayerSummaries = @file_get_contents($ISteamUser . "GetPlayerSummaries/v0002/?key=" . $apikey . "&steamids=" . $steamid);
        $PlayerSummaries = (array) json_decode($GetPlayerSummaries, true);            
        
        if (!empty($PlayerSummaries['response']['players'])){
            $PlayerSummaries = (array) json_decode($GetPlayerSummaries) -> response -> players[0];
            $GetPlayerBans = @file_get_contents($ISteamUser . "GetPlayerBans/v1/?key=" . $apikey . "&steamids=" . $steamid);
            $PlayerBans = (array) json_decode($GetPlayerBans) -> players[0];
            $GetRecentlyPlayedGames = @file_get_contents($IPlayerService . "GetRecentlyPlayedGames/v0001/?key=" . $apikey . "&steamid=" . $steamid . "&format=json");
            $RecentlyPlayedGames = (array) json_decode($GetRecentlyPlayedGames) -> response; 
            $GetFriendList = @file_get_contents($ISteamUser . "GetFriendList/v0001/?key=" . $apikey . "&steamid=" . $steamid . "&relationship=friend");

            if(trim($GetFriendList)!=''){
                $FriendList = (array) json_decode($GetFriendList) -> friendslist;
            }
            else{
                $FriendList = array("friendslist" => 1);
            }

            if ($mysqli->error == ""){
                $err = array("msg" => "OK");
                $res =  $mysqli->query(sprintf("SELECT id FROM users WHERE usersid = ('".$arr["usersid"]."');"));
                $row = $res->fetch_assoc();
                $response = $PlayerSummaries + $FriendList + $PlayerBans + $RecentlyPlayedGames + $row + $err;
                echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            }
            else{
              $err = array("Error1" => "Error: usersid not set");
              echo json_encode($err, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            }
          }
          else{
            $err = array("msg" => "Error: profile does not exist");
            echo json_encode($err, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
          }
      }
      else{
          $err = array("msg" => "Error: usersid not set");
          echo json_encode($err, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);    
      }
    }
    
    public function del() {
        $params = explode("?", $_SERVER["REQUEST_URI"]);  
        $params = @$params[1]; 
        parse_str($params, $arr);
      
      if (!empty($params) && !empty($arr["id"])){
          $mysqli = new \mysqli("localhost", "root", "", "quest1"); 
          $mysqli->query(sprintf("DELETE FROM users WHERE id = ('".$arr["id"]."');"));
        
        if ($mysqli->error == ""){
            $err = array("msg"=>"OK");
            echo json_encode($err, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }     
      }
      else{
          $err = array("msg"=>"Error: usersid not set");
          echo json_encode($err, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);    
      }
   }  
}
?>