<?php
//COOKIE FUNCTION FOR REGISTRETION SUBSCRIPTION OF NEWLY_REG_USERS
/**************
 */

$conn = mysqli_connect("localhost" , "root", "" , "bloomblu");
if (mysqli_connect_errno()){
    echo"Unable to connect to the datatbase";
}

if(isset($_POST['email'])){
    global $conn ;
    $email = $_POST['email'];
    $ip = $_SERVER['REMOTE_ADDR'];
    $check = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(*) FROM subscribers WHERE email = '$email'"));
        if ($check[0] == 0) {
            $query = mysqli_query($conn, "INSERT INTO subscribers (email , ip ) VALUES('$email' , '$ip') ");
            if($query){
                echo 'true';
            }else{
                echo 'false';
            }
        }else{
            echo 'exists';
        }   
}

?>