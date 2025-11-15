<?php
require_once __DIR__ . '/db.php';
if(session_status()===PHP_SESSION_NONE){ session_start(); }

function login($username,$password){
  global $mysqli;
  $stmt=$mysqli->prepare("SELECT id, username, password_hash FROM admins WHERE username=? LIMIT 1");
  $stmt->bind_param('s',$username); $stmt->execute();
  if($row=$stmt->get_result()->fetch_assoc()){
    if(password_verify($password,$row['password_hash'])){
      $_SESSION['admin_id']=(int)$row['id']; $_SESSION['admin_username']=$row['username']; return true;
    }
  }
  return false;
}
function require_admin(){ if(empty($_SESSION['admin_id'])){ header('Location: login.php'); exit; } }
function logout(){
  $_SESSION=[];
  if (ini_get("session.use_cookies")) {
    $p=session_get_cookie_params();
    setcookie(session_name(), '', time()-42000, $p["path"], $p["domain"], $p["secure"], $p["httponly"]);
  }
  session_destroy();
}
