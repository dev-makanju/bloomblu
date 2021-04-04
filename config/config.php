<?php 

session_start();

$host = "127.0.0.1";
$username = "root";
$password = "";
$db_name = "bloomblu";
$sitename = "Bloomblu";
$config_basedir = "http://127.0.0.1/bloomblue/";

$conn = mysqli_connect($host , $username , $password , $db_name);

if(mysqli_connect_errno()){
    echo"FAILED: to connect to database".mysqli_connect_errno();
}

//update session t know the current login user
if(isset($_SESSION['user'])){
    $time = date('U')+50;
    $sql = "UPDATE users SET updated_at = now() , last_login_time = '$time' WHERE id ='".$_SESSION['user']['id']."'";
    $result = mysqli_query($conn , $sql);
}

/***
 * *SET GLOBAL COOKIE FOR SITE ViSITOR
 * */
//check if cookie is set
//if not set a new daily cookies
if($_COOKIE['webcount']){
    updateWebcountView($_COOKIE['webcount']);
}else{
   //set cookie for post views
  if(!isset($_SESSION['webcount'])){
     //set new cookie
     $name = 'webcount';
     $value = session_id();
     setcookie($name , $value , time() + (3600 * 24 ));
     updateWebcountView($_COOKIE['webcount']);
     header("location:".$_SERVER['PHP_SELF'] );
  }else{
    //use-ip
    $cookie_key = $_SERVER['REMOTE_ATTR'];
    updateWebcountView($cookie_key);
  }
}

function updateWebcountView($cookie){
  global $conn;
  $sql = "SELECT * FROM website_visitor_counter WHERE webcount = '$cookie'";
    $res = mysqli_query($conn , $sql);
    if(mysqli_num_rows($res) == 0){     
        $sql = "INSERT INTO website_visitor_counter(webcount,date) VALUES('$cookie',now())";
        $rs = mysqli_query($conn , $sql);
    }

}


?>