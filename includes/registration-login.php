<?php
//declare variables
$username = "";
$email = "";
$errors = array();
$loginUser = false;

//if user click on loggin page
if(isset($_GET['forgotPass'])){
    $loginUser = true;
}

if(isset($_POST['submit'])){
       //recieve all input
       $username = esc($_POST['username']);
       $email = esc($_POST['email']);
       $password = esc($_POST['password']);
       $password_2 = esc($_POST['confirm_pass']);
       //validate for empty field
       if(empty($username) || empty($email) || empty($password)){
            array_push($errors, "You need to fill in the empty field");
       }
       //check if username password does match password
       if($password_2 != $password){
           array_push($errors , "Confirm password do not match");
       }

        //check if username or password exist
        $sql = "SELECT * FROM users WHERE username = '$username' OR email = '$email' LIMIT 1";
        $result = mysqli_query($conn, $sql);
        $user = mysqli_fetch_assoc($result);
        //check if user exist
        if ($user){
            if ($user['username'] === $username) {
                array_push($errors, "Oops , username already exist!");
            }
            if($user['email'] === $email) {
                   array_push($errors, "Oops , email Already exist!");
            }
        }
    if(count($errors) == 0){
        //encrypt password
        $password = md5($password);
        $sql = "INSERT INTO users (username , email , password , created_at , updated_at) 
                        VALUES('$username' , '$email' , '$password' , now() , now())";
        $result = mysqli_query($conn , $sql);
        //insert last id
        $reg_user_id = mysqli_insert_id($conn);
        //put log in user into session array
        $_SESSION['user'] = getUserById($reg_user_id);

        if(in_array($_SESSION['user']['role'] , ['Admin']['Author'])){
           //redirect to Admin Area
           header("location:" .$config_basedir. "Admin/dashboard.php");
           $_SESSION['message'] = "You are now logged in";
           exit(0);
        }else{
            $_SESSION['message'] = "You are now logged in";
           //redirect to public area
           header("location:index.php");
           exit(0);
        }
             
    }
}


if(isset($_POST['log_in'])){
    //get input from form
    $username = esc($_POST['username']);
    $password = esc($_POST['password']);
    //validate form to get the right input
    if(empty($username) || empty($password)){
        array_push($errors , "filled in the required form");
    }
    //check if user exist
    if(empty($errors)){
        //encrypt password
        $password = md5($password);
        $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password' LIMIT 1";
        $result = mysqli_query($conn , $sql);
        $sql_row = mysqli_num_rows($result);
        //check if exist
        if($sql_row > 0){
           $reg_user_id = mysqli_fetch_assoc($result)['id'];
           //save user id into session variable
           $_SESSION['user'] = getUserById($reg_user_id);
           $res = mysqli_query($conn , $sql);
           if(in_array($_SESSION['user']['role'] , ['Admin','Author'])){
              //redirect to Admin area
              header("location:" .$config_basedir. "Admin/dashboard.php");
              $_SESSION['message'] = "You are now logged in";
           }else{
               //redirect to public Area
               header("location:index.php");
               $_SESSION['message'] = "You are now logged in";
           }     
       }else{
           array_push($errors , "Invalid credentials!");
       }
    }   
}


//escape value from form
function esc(String  $value){
     global $conn;
     $val = trim($value);
     $val = mysqli_real_escape_string($conn , $value);
     return $val;
}

function getUserById($id){
    global $conn;
    $sql = "SELECT * FROM users WHERE id = $id LIMIT 1";
    $result = mysqli_query($conn , $sql);
    $user = mysqli_fetch_assoc($result);
    return $user;
}

/*********************
 * *WHEN USER CLICK ON FORGOT PASSWORD
 * *
 * *****************************/
//send mail action
//grab the email
//compare to fetch result from database
if(isset($_POST['forgot_pass'])){
     global $email , $conn , $errors;
     //grab input
     $email = esc($_POST['email']);
     //set the format to check the email aginst
     $checkemail = "/^[a-z0-9]+([_\\.-][a-z0-9]+)*@([a-z0-9]+([\.-][a-z0-9]+)*)+\\.[a-z]{2,}$/i";
    //chek if email is valid
     if(!preg_match($checkemail, $email)){
         //if not display an error msg
         array_push($errors , "Email is not valid");
     }
     //check if email exist
     $sql = "SELECT * FROM users WHERE email = '$email' LIMIT 1 ";
     $result = mysqli_query($conn , $sql);
     $rows = mysqli_num_rows($result);
     if($rows > 0){
        $res = mysqli_fetch_assoc($result);
        //bind password for mailing
        mail($email , "Forgotten Password" , "This is your password " . $res['password']. "\n \n try not to loose it again",'from:noreply@yourwebsitehere.co.uk');
        //alert success msg for completion of query
        $_SESSION['message'] = "A mail has been sent to your email address containing your password";
     }else{
         array_push($errors , "Email is not registered with us!");
     }
 }

 //if user click on change password
 if(isset($_POST['Change_pass'])){
    global $conn , $errors;
    //grab input from form
    $oldPassword = esc($_POST['oldPassword']);
    $newPassword = esc($_POST['newPassword']);
    $confirmPassword = esc($_POST['conf_Password']);
    $user_id = $_SESSION['user']['id'];
    
    //validate password
    if(empty($oldPassword)){
        array_push($errors , "old password is required!");
    }
    if(empty($newPassword)){
        array_push($errors , "new password is required!");
    }
    if(empty($confirmPassword)){
        array_push($errors , "confirm password cannot be empty");
    }
    //encrypt Password
    $oldPassword = md5($oldPassword);
    $newPassword = md5($newPassword);
    //query database to verify if password exist
    $sql = "SELECT * FROM users WHERE  id = '$user_id'";
    $result = mysqli_query($conn , $sql);
    $res = mysqli_fetch_assoc($result)['password'];
    if($res !== $oldPassword){
        array_push($errors , "Old password does not exist!");
    }else{
        //set new password to update the password
        $sql = "UPDATE users SET password = '$newPassword' WHERE id = '$user_id' ";
        $result = mysqli_query($conn , $sql);
        if($result){
            $_SESSION['message'] = "Your Password have been successfully changed";
        }
    }
}

?>

