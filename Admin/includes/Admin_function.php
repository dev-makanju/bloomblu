<?php
//declaring local varaible
$editUser = false;
$Admin_id = 0;
$isEditingUser = false;
$username = "";
$email = "";
$role = "";

//general variable for post and Admin function
$errors = [];


/***when user click on edit profile**/
if(isset($_GET['edit-profile'])){
    $editUser = true;
}

/******
 * *Admin users action //managing Admin functions 
 * ***/
if(isset($_POST['create_user'])){
    createAdmin($_POST);
}
//if user click the edit admib button
if(isset($_GET['edit-user'])){
    $isEditingUser = true;
    $encrypt_2 = base64_decode(urldecode($_GET['edit-user']));
    $Admin_id = ((($encrypt_2*956783)/5678)/123456789);
    editAdmin($Admin_id);
}
//if user click on update Admin
if(isset($_POST['update_user'])){
    updateAdmin($_POST);
}
// user click on delete admin
if(isset($_GET['delete-user'])){
    $encrypt_2 = base64_decode(urldecode($_GET['delete-user']));
    $Admin_id = ((($encrypt_2*956783)/5678)/123456789);
    deleteAdmin($Admin_id);
}

//Admin function 
function createAdmin($request_values){
    global $conn , $username , $role  , $email , $errors;
    //check form for valid input
    $username = esc($request_values['username']);
    $email = esc($request_values['email']);
    $password = esc($request_values['password']);
    $confirmPassword = esc($request_values['Confirm_password']);
    if (isset($request_values['role'])) {
        $role = $request_values['role'];
    }
    
    if (empty($username)) {
        array_push($errors, "username is required");
    }
    if (empty($email)) {
        array_push($errors, "email is required");
    }
    if (empty($password)) {
        array_push($errors, "password is required");
    }
    if (empty($confirmPassword)) {
        array_push($errors, "Confirm password is required");
    }
    if (empty($role)) {
        array_push($errors, "role cannot be empty");
    }
    if($password !== $confirmPassword){
        array_push($errors , "Confirm password is not a match!");
    }

    //check if user is not created twice
    $sql = "SELECT * FROM users WHERE username = '$username' AND email = '$email' ";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);
    if($user) {
        if($user['username'] === $username){
             array_push($errors, "Username Already Exist!");
        } 
        if($user['email'] === $email){
            array_push($errors, "Email Already exist");
        }
    }
    //check if no error exits
    if(count($errors) == 0 ){
        //encrypt password 
        $password = md5($password);
        //save user into database
        $sql = "INSERT INTO users ( username , email , password , role , created_at , updated_at) 
                VALUES('$username' , '$email' , '$password' , '$role' , NOW() , NOW())"; 
        $result = mysqli_query($conn , $sql);
        if($result){
            $_SESSION['message'] = "Admin user created successfully";
            //redirect to user.php
            header("location:user.php");
            exit(0);
        }
    }
}

//set Admin id as parameter
//fetch Admin from database
//set username and email on form


//when user click on edit Admin
function editAdmin($Admin_id){
    global $conn , $username , $Admin_id , $role  , $email , $errors ;
    $sql = "SELECT * FROM users WHERE id = '$Admin_id' LIMIT 1";
    $result = mysqli_query($conn , $sql);
    $user = mysqli_fetch_assoc($result);
    //set Output on form value
    $username = $user['username'];
    $email = $user['email'];
}

//update Admin user
function updateAdmin($request_values){
    global $conn , $Admin_id , $username , $role  , $email , $errors , $isEditingUser;

    $username = esc($request_values['username']);
    $email = esc($request_values['email']);
    $password = esc($request_values['password']);
    $confirmPassword = esc($request_values['Confirm_password']);
    //password Hashing
    $password = md5($password);
    //Validate editing form
    if (isset($request_values['role'])) {
        $role = $request_values['role'];
    }
    if (empty($username)) {
        array_push($errors, "username is required");
    }
    if (empty($email)) {
        array_push($errors, "email is required");
    }
    if (empty($password)) {
        array_push($errors, "password is required");
    }
    if (empty($confirmPassword)) {
        array_push($errors, "Confirm password is required");
    }
    if (empty($role)) {
        array_push($errors, "role cannot be empty");
    }
    //check if user is not created twice
    $sql = "SELECT * FROM users WHERE username = '$username' AND email = '$email' ";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);
    if($user) {
        if($user['username'] === $username){
             array_push($errors, "Username Already Exist!");
        } 
        if($user['email'] === $email){
            array_push($errors, "Email Already exist");
        }
    }
    if(count($errors) == 0) {
        $sql = "UPDATE users SET username = $username , email = $email , password = $password , role = $role , updated_at = now()";
        $result = mysqli_query($conn , $sql);
        if($result){
            $_SESSION['message'] = "User succesfully updated";
            header("location:user.php");
        }
    }   
}

//set user id as parameter
//delete user from database

function deleteAdmin($Admin_id){
    global $conn;
    $sql = "DELETE FROM users WHERE id = '$Admin_id' LIMIT 1";
    $result = mysqli_query($conn , $sql);
    if($result){
        $_SESSION['message'] = "User successfully deleted";
        header("location:user.php");
    }
}

//*****/fetch all Adminuser from the database 
//**** */ return thier corresponding role

function getAdminUser($pageno){
    global $conn , $role;
    $no_of_posts_per_page = 10;
    $offset = ($pageno-1) * $no_of_posts_per_page;
    $sql = "SELECT COUNT(*)FROM users WHERE role IS NOT NULL ";
    $result = mysqli_query($conn , $sql);
    $total_rows = mysqli_fetch_array($result)[0];
    $total_page = ceil($total_rows / $no_of_posts_per_page);
    $sql = "SELECT * FROM users WHERE role IS NOT NULL LIMIT $offset , $no_of_posts_per_page";
    $result = mysqli_query($conn , $sql);
    $user_data = mysqli_fetch_all($result , MYSQLI_ASSOC);
    $final_post = array();

    foreach($user_data as $user){ 
        array_push($final_post , $user);
    }
    return ['total_page'=>$total_page , 'user'=>$final_post];
}


//escape submitted value from form 
//To prevrnt sql injection

function esc(String $value){
    global $conn;
    $val = trim($value);
    $val = mysqli_real_escape_String($conn , $value);
    return $val;
}

//make slug
function makeSlug(String $string){
    $string = strtolower($string);
    $slug = preg_replace('/[^A-Za-z0-9-]+/','-',$string);
    return $slug;
}

/******************************
 * **MANAGE TOPICS
 *>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
 >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
 >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
 * *********************/
//variable declaration
$isEditingTopic = false;
$topic_name = "";
$topic_id = 0;

//return all topics from the database
function getAllTopics($pageno){
    global $conn;
    $no_of_posts_per_page = 10;
    $offset = ($pageno-1) * $no_of_posts_per_page;
    $sql = "SELECT COUNT(*)FROM topics";
    $result = mysqli_query($conn , $sql);
    $total_rows = mysqli_fetch_array($result)[0];
    $total_page = ceil($total_rows / $no_of_posts_per_page);
    $sql = "SELECT * FROM topics LIMIT $offset , $no_of_posts_per_page";
    $result = mysqli_query($conn , $sql);
    $topic_data = mysqli_fetch_all($result , MYSQLI_ASSOC);
    $final_post = array();
    foreach($topic_data as $topic){
         array_push($final_post , $topic);
    }
    return ['total_page'=>$total_page , 'topic'=>$final_post];
}

/***
//Topic Action
//when user click on create topic
 * **/
if(isset($_POST['create_topic'])){
    createTopic($_POST);
}
//when user click on edit topic
if(isset($_GET['edit-topic'])){
    $isEditingTopic = true;
    $encrypt_2 = base64_decode(urldecode($_GET['edit-topic']));
    $topic_id = ((($encrypt_2*956783)/5678)/123456789);
    editTopic($topic_id);
}
//when user click on Update Topic 
if(isset($_POST['update_topic'])){
    updateTopic($_POST);
}
//whenn user click on delete topics
if(isset($_GET['delete-topic'])){
    $encrypt_2 = base64_decode(urldecode($_GET['delete-topic']));
    $topic_id = ((($encrypt_2*956783)/5678)/123456789);
    deleteTopic($topic_id);
}

//topic functions

function createTopic($request_values){
    global $conn , $topic_name , $isEditingTopic , $errors , $topic_id;
    //save topic name into database
    $topic_name = esc($request_values['topic']);
    $slug = makeSlug($topic_name);
    if(isset($request_values['topic_id'])){
        $topic_id = $request_values['topic_id'];
    }
    //validate for valid input
    if(empty($topic_name)){
        array_push($errors , "topic is required!");
    }
    //check if topic exist
    $sql = "SELECT * FROM topics WHERE slug = '$slug' LIMIT 1 ";
    $result = mysqli_query($conn , $sql);
    $topic = mysqli_num_rows($result);
    if($topic > 0){
        array_push($errors , "Topic already exist");
    }
    if(count($errors) == 0){
       $sql = "INSERT INTO topics (name , slug) VALUES('$topic_name' , '$slug')";
       $result = mysqli_query($conn , $sql);
       if ($result) {
           $_SESSION['message'] = "Topic created successfully";
           header("location:" . $config_basedir ."topics.php");
           exit(0);
       }  
    }
}

//recieve topic id as aparameter
//fetch topic details from database
    //set topic name on formm for editing

function editTopic($topic_id){
    global $conn , $topic_id , $topic_name ;
    $sql = "SELECT * FROM topics WHERE id = '$topic_id' LIMIT 1 ";
    $result = mysqli_query($conn , $sql);
    $topic = mysqli_fetch_assoc($result);
    //set form output on form value
    $topic_name = $topic['name'];
}

function updateTopic($request_values){
    global $conn , $topic_name  , $topic_id , $slug , $errors;
    //save topic name into database
    $topic_name = esc($request_values['topic']);
    $topic_id = esc($request_values['topic_id']);
    //make slug
    $slug = makeSlug($topic_name);
    if(isset($request_values['topic_id'])){
        $topic_id = $request_values['topic_id'];
    }
    //validate for valid input
    if(empty($topic_name)){
        array_push($errors , "topic is required!");
    }
    //check if topic exist
    $sql = "SELECT * FROM topics WHERE slug = '$slug' LIMIT 1 ";
    $result = mysqli_query($conn , $sql);
    $topic = mysqli_num_rows($result);
    if($topic > 0){
        array_push($errors , "Topic already exist");
    }
    if(count($errors) == 0) {
        $sql = "UPDATE topics SET name = '$topic_name' , slug = '$slug' WHERE id = $topic_id ";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $_SESSION['message'] = "Topic updated successfully";
            header("location:" . $config_basedir ."topics.php");
            exit(0);
        }
    }    
}


function deleteTopic($topic_id)
{
    global $conn;
    $sql = "DELETE FROM topics WHERE id = $topic_id";
    $result = mysqli_query($conn , $sql);
    if($result) {
        $_SESSION['message'] = "Topic deleted successfully ";
        header("location:" . $config_basedir ."topics.php");
        exit(0);
    }
}

//return all user information from database
function getAllUserInfo(){
    global $conn ;
    $user_id = $_SESSION['user']['id'];
    $sql = "SELECT  * FROM users WHERE id = '$user_id'";
    $result = mysqli_query($conn , $sql);
    $user_info = mysqli_fetch_all($result , MYSQLI_ASSOC);
    return $user_info;
}

/*********************************************************
 * *PROFILE PAGE UPDATE
 * ******************************************************/
//initiallizing variables 
$public = false;
$bio = "";
$nationality = "";
$state = "";
$location = "";
$image = "";

//Update profile Action 
if(isset($_POST['update_profile'])){
    updateProfile($_POST);
}

if(isset($_GET['edit-profile'])){
    $profile_id = $_GET['edit-profile'];
    $encrypt_2 = base64_decode(urldecode($profile_id));
    $id = ((($encrypt_2*956783)/5678)/123456789);
    editProfile($id);
}

//user functionality to to process update profile
function updateProfile($request_values){
    global $conn , $errors , $bio , $nationality , $state , $location , $image;
    //grab input from variables
    $user_id = $_SESSION['user']['id'];
    $bio = htmlentities(esc($request_values['user_bio']));
    $state = esc($request_values['User_state']);
    $nationality = esc($request_values['User_nationality']);
    $current_city = esc($request_values['user_city']);   
    $image = $_FILES['profileImage']['name'];
    //target the image file directory
    $target = "../CSS/MEDIA/" . basename($image);
    if(!move_uploaded_file($_FILES['profileImage']['tmp_name'],$target)){
        array_push($errors , "Image failed to upload");
    }
    //no validation , it depends on user choice to do anything with is selected form
    $sql = "UPDATE users SET image = '$image' , bio = '$bio' , state = '$state' , nationality = '$nationality' , location = '$current_city'  , updated_at = now() WHERE id = '$user_id'";
    $result = mysqli_query($conn , $sql);
    if($result){
        header("location:profile.php");  
    }
}


//grab the current logged in user id
//fetch infomation from database
//set data on input from database 
function editProfile($id){
    global $conn , $bio , $nationality , $state , $location;
    $sql = "SELECT * FROM users WHERE id = '$id' ";
    $result = mysqli_query($conn , $sql);
    $edit = mysqli_fetch_assoc($result);
    $bio = $edit['bio'];
    $state = $edit['state'];
    $nationality = $edit['nationality'];
    $location = $edit['location'];
}


/*****************
 * ** MANAGE ADVERTICEMENT
 * *********** */
$isEditingAds = false;
$Ads_name = "";
$Ads_image = "";
$Ads_type = "";

// Actions 
if(isset($_POST['save_ads'])){
    createAds($_POST);
}

if(isset($_POST['update_ads'])){
    if (isset($_GET['edit-ads'])){
        $Ads_id = $_GET['edit-ads'];
        updateAds($_POST, $Ads_id);
    }
}

if(isset($_GET['edit-ads'])){
    $isEditingAds = true;
    if(isset($_GET['edit-ads'])){
        $Ads_id = $_GET['edit-ads'];
        editAds($Ads_id);
    }
}
if(isset($_GET['delete-ads'])){
    $Ads_id = $_GET['delete-ads'];
    deleteAds($Ads_id);
}

function getAllads(){
    global $conn;
    $query = "SELECT * FROM Ads ";
    $result = mysqli_query($conn , $query);
    $res = mysqli_fetch_all($result , MYSQLI_ASSOC);
    return $res;
}

function getAllAdsCount(){
    global $conn;
    $result = mysqli_query($conn , "SELECT COUNT(*) AS total FROM Ads ");
    $data = mysqli_fetch_assoc($result);
    return $data['total'];
}

//user functionality to to process update profile
function createAds($request_values){
    global $conn , $Ads_name , $Ads_image , $Ads_id , $errors;
    //grab input from variables
    $Ads_name = $request_values['link'];
    if(isset($request_values['Ads_id'])){
        $Ads_type =  $request_values['Ads_id'];
    }
    $Ads_image = $_FILES['AdsImage']['name'];
    //target the image file directory
    $target = "../CSS/MEDIA/" . basename($Ads_image);
    if(!move_uploaded_file($_FILES['AdsImage']['tmp_name'],$target)){
        array_push($errors , "Image failed to upload");
    }
    if(empty($Ads_name)){
        array_push($errors , "kindly add a link");
    }
    if(empty($Ads_type)){
        array_push($errors , "kindly add a cartegory");
    }
    if(count($errors)== 0){
       if(getAllAdsCount() < 4){
          $sql = "INSERT INTO Ads (linkname , ads_type , image , created_at ) VALUES('$Ads_name', '$Ads_type' ,'$Ads_image', now())";
          $result = mysqli_query($conn, $sql);
           if ($result) {
              header("location:ads.php");
              $_SESSION['message'] = "ads successfully created";
              exit(0);
            }
        }else{
            array_push($errors , "You can Only create 3 ads on this platform...Thanks");
        }
    }    
}

//user functionality to to process update profile
function updateAds($request_values , $Ads_id){
    global $conn , $Ads_name , $Ads_type , $Ads_image , $errors;
    //grab input from variables
    $Ads_name = $request_values['link'];
    if(isset($request_values['Ads_id'])){
        $Ads_type = $request_values['Ads_id'];
    }
    $Ads_image = $_FILES['AdsImage']['name'];
    //target the image file directory
    $target = "../CSS/MEDIA/" . basename($Ads_image);
    if(!move_uploaded_file($_FILES['AdsImage']['tmp_name'],$target)){
        array_push($errors , "Image failed to upload");
    }
    if(empty($Ads_name)){
        array_push($errors , "kindly add a link");
    }
    if(empty($Ads_type)){
        array_push($errors , "kindly add a cartigory");
    }
    if(count($errors)== 0){
        $sql = "UPDATE Ads SET image = '$Ads_image' , ads_type = '$Ads_type' , linkname = '$Ads_name' , updated_at = now() WHERE id = '$Ads_id'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            header("location:ads.php");
            $_SESSION['message'] = "ads successfully updated";
            exit(0);
        }
    }    
}

//edit ads
function editAds($Ads_id)
{
    global $conn , $Ads_name , $Ads_image , $Ads_id ;
    $sql = "SELECT * FROM Ads WHERE id = '$Ads_id' ";
    $result = mysqli_query($conn, $sql);
    $edit = mysqli_fetch_assoc($result);
    $Ads_name = $edit['linkname'];
    $Ads_image = $edit['image'];
    $Ads_type = $edit['ads_type'];
}

function deleteAds($Ads_id){
    global $conn ;
    $query = ("DELETE FROM Ads WHERE id = '$Ads_id'");
    $result = mysqli_query($conn , $query);
    if($result){
        header("location:ads.php");
        $_SESSION['message'] = "ads successfully deleted";
        exit(0);
    }
}


/*************
 * ****MESSAGE
 * ***** ***/

//initiallizing variables
$isEditingmessage = false;
$message_body = "";
$message = "";

function getAllMessage(){
    global $conn;
    $query = "SELECT * FROM message ";
    $result = mysqli_query($conn , $query);
    $res = mysqli_fetch_all($result , MYSQLI_ASSOC);
    return $res;
}

function getUsernameById($id){
    global $conn;
    $result = mysqli_query($conn , "SELECT username FROM users WHERE id='".$id."' LIMIT 1");
    //return the username
    return mysqli_fetch_assoc($result)['username'];
}

 //return all comment id
function getNotificationCount(){
    global $conn;
    $result = mysqli_query($conn , "SELECT COUNT(*) AS total FROM message ");
    $data = mysqli_fetch_assoc($result);
    return $data['total'];
} 

function getEmail($id){
    global $conn;
    $query = "SELECT * FROM users WHERE id = '$id' LIMIT 1";
    $result = mysqli_query($conn , $query);
    $user_mail = mysqli_fetch_assoc($result)['email'];
    return $user_mail;
}
//message action
if(isset($_POST['sendMessage'])){
    createMessage($_POST);
}

if(isset($_POST['sendUpdate'])){
    if(isset($_GET['edit-message'])){
        $id = $_GET['edit-message'];
        updateMessage($_POST , $id);  
    }  
}

if(isset($_GET['edit-message'])){
    $isEditingmessage = true;
    $id = $_GET['edit-message'];
    editMessage($id);
}

if(isset($_GET['delete-message'])){
    deleteMessage($_GET['delete-message']);
} 

function createMessage($request_values){
    global $conn , $message_body , $errors;
    $user_reg_id = $_SESSION['user']['id'];
    $message_body = $request_values['message'];
    $email = getEmail($_SESSION['user']['id']);
    if(empty($message_body)){
        array_push($errors , "Oops, you need to write a message");
    }
    if(count($errors) == 0){
        $query = "INSERT INTO message(user_id , email , body , created_at) VAlUES('$user_reg_id' , '$email ' , '$message_body' , now())";
        $result = mysqli_query($conn, $query);
        if ($result) {
            header("location:Notifications.php");
            exit(0);
        }
    }
}

function updateMessage($request_values , $id){
    global $conn , $message_body , $errors;
    $message_body = $request_values['message'];
    if(empty($message_body)){
        array_push($errors , "Oops, you need to write a message");
    }
    if (count($errors) == 0) {
        $query = "UPDATE message SET body =  '$message_body' WHERE id = '$id' ";
        $result = mysqli_query($conn, $query);
        if ($result) {
            header("location:Notifications.php");
            exit(0);
        }
    }
}

function editMessage($id){
    global $conn , $message;
    $sql = "SELECT * FROM message WHERE id = '$id' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $message = mysqli_fetch_assoc($result)['body'];
}

function deleteMessage($id){
    global $conn;
    $query = "DELETE FROM message WHERE id = '$id' LIMIT 1";
    $result = mysqli_query($conn , $query);
    if($result){
        header("location:Notifications.php");
        $_SESSION['message'] = "message successfully deleted";
        exit(0);
    }
}




/******
 * GET ALL SUBSCRIBER
 * ****/

function getSubscriber(){
    global $conn;
    $query = "SELECT * FROM  subscribers ";
    $result = mysqli_query($conn , $query);
    $subscribers = mysqli_fetch_all($result , MYSQLI_ASSOC);
    return $subscribers;
} 


//check if user click on deleting subscriber
if(isset($_GET['sub_id'])){
    $id = $_GET['sub_id'];
    deleteSubscriber($id);
}

function deleteSubscriber($id){
    global $conn;
    $query = "DELETE FROM subscribers WHERE id = '$id'";
    $result = mysqli_query($conn , $query);
    if($result){
        $_SESSION['message'] = "Subscriber has been succefully deleted";
        exit(0);
    }
}

/*************
 * *FETCH POST tha has not been send to subscribers 
 * **********/

function getAllNewsletterSendPost(){
    global $conn;
    $sql = "SELECT * FROM posts WHERE published = true AND newsletter != true ";
    $query = mysqli_query($conn , $sql);
    $getmail = mysqli_fetch_all($query , MYSQLI_ASSOC);
    return $getmail;
}

function getSubstractedPostById($post_id){
    global $conn;
    $query = "SELECT * FROM posts WHERE id = '$post_id'";
    $result = mysqli_query($conn , $query);
    $res = mysqli_fetch_assoc($result);
    $sub_result = substr($res['body'] , 0 , 400);
    $dots = "....";
    $result = $sub_result.$dots;
    return $result;
}

function makeDescription($body , $desc_length = 10){
    $body_word = substr_count($body , " ") + 1;
}


//count all the subscriber and return total
function getCountSubscribers(){
    global $conn;
    $sql = "SELECT COUNT(*) AS total FROM subscribers ";
    $result = mysqli_query($conn , $sql);
    $res = mysqli_fetch_assoc($result)['total'];
    return $res;
}

//count all the template and returb the total
function getCountTemplates(){
    global $conn;
    $sql = "SELECT COUNT(*) AS total FROM posts  WHERE published = true AND newsletter != true ";
    $result = mysqli_query($conn , $sql);
    $res = mysqli_fetch_assoc($result)['total'];
    return $res;
}

function getUnplishedArticles(){
    global $conn;
    $query = "SELECT COUNT(*) AS total FROM posts WHERE published != true AND news != true";
    $res = mysqli_query($conn , $query);
    $result = mysqli_fetch_assoc($res);
    return $result['total'];
} 

function getUnplishedNews(){
    global $conn;
    $query = "SELECT COUNT(*) AS total FROM posts WHERE published != true AND news = true";
    $res = mysqli_query($conn , $query);
    $result = mysqli_fetch_assoc($res);
    return $result['total'];
}

function newlyRegisterdToday(){
    global $conn;
    $date = date('Y-m-d');
    $sql = "SELECT COUNT(*) AS total FROM users WHERE created_at >= DATE_SUB(NOW() , INTERVAL 1 DAY)";
    $result = mysqli_query($conn , $sql);
    $res = mysqli_fetch_assoc($result)['total'];
    return $res;
}

function showSearchClick($data){
    global $conn;
    $query = "SELECT * FROM posts WHERE slug LIKE '%$data%' LIMIT 10";
    $res = mysqli_query($conn , $query);
    $EXE_query = mysqli_fetch_all($res , MYSQLI_ASSOC);
    return $EXE_query;
}

function getSearchValues($id){
    global $conn;
    $query = "SELECT * FROM posts WHERE id = '$id' ";
    $EXE_query = mysqli_query($conn , $query);
    return $EXE_query;
}

//when user click on submit form
//grab the data and append to url

if(isset($_POST['search-submit'])){
    $sql = strip_tags(addslashes($_POST['search']));
    $query = makeSlug($sql);
    $url = $_SERVER['SCRIPT_NAME'];
    header("location:".$url."?searh=".$query);
    exit(1);      
}


function hashUrlData($data){
    $encrypt = (($data*123456789*5678)/956783);
    $link = urlencode(base64_encode($encrypt));
    return $link;
}

?>