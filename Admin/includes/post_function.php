<?php

//initialising variable
$isEditingPost = false;
$isEditingNews = false;
$published = 0;
$title = "";
$topic = "";
$textarea = "";
$featured_image = "";
$post_slug = "";
$post_id = 0;
$news_id = 0;
$body = "";


/****************
 * **** Return all post from database
 * *****************/

 //encrypt post data
function getAllPost($pageno){
    global $conn;
    $no_of_posts_per_page = 20;
    $offset = ($pageno-1) * $no_of_posts_per_page;
    if($_SESSION['user']['role'] === "Admin"){
        $sql = "SELECT COUNT(*)FROM posts WHERE published = true AND news != true ";
    }//Author can only see their posts
    if($_SESSION['user']['role'] == 'Author'){
        $user_id = $_SESSION['user']['id'];
        $sql = "SELECT COUNT(*)FROM posts WHERE user_id = '$user_id' AND published = true AND news != true ";
    }
    $result = mysqli_query($conn , $sql);
    $total_rows = mysqli_fetch_array($result)[0];
    $total_page = ceil($total_rows / $no_of_posts_per_page);
    //Only Admin can see all post
    if($_SESSION['user']['role'] === "Admin"){
        $sql = "SELECT * FROM posts WHERE news != true LIMIT $offset , $no_of_posts_per_page";
    }//Author can only see their posts
    if($_SESSION['user']['role'] == 'Author'){
        $user_id = $_SESSION['user']['id'];
        $sql = "SELECT * FROM posts WHERE user_id = '$user_id' AND news != true LIMIT $offset , $no_of_posts_per_page";
    }
    $result = mysqli_query($conn , $sql);
    $post = mysqli_fetch_all($result , MYSQLI_ASSOC );  
    //create an assoc array called final post
    $final_posts = array();
    foreach($post as $posts){
    $post['Author'] = getPostAuthorById($posts['user_id']);
    $post['topic'] = getPostTopic($posts['id']);
    //save all post as an associative array
    array_push($final_posts , $posts);
    }

    return ["total_page" => $total_page , "post" => $final_posts ];
}


function getAllNews($pageno){
    global $conn;
    $no_of_posts_per_page = 20;
    $offset = ($pageno-1) * $no_of_posts_per_page;
    if($_SESSION['user']['role'] === "Admin"){
        $sql = "SELECT COUNT(*)FROM posts WHERE published = true AND news != true ";
    }//Author can only see their posts
    if($_SESSION['user']['role'] == 'Author'){
        $user_id = $_SESSION['user']['id'];
        $sql = "SELECT COUNT(*)FROM posts WHERE user_id = '$user_id' AND published = true AND news != true ";
    }
    $result = mysqli_query($conn , $sql);
    $total_rows = mysqli_fetch_array($result)[0];
    $total_page = ceil($total_rows / $no_of_posts_per_page);
    //Only Admin can see all post
    if($_SESSION['user']['role'] === "Admin"){
        $sql = "SELECT * FROM posts WHERE news = true LIMIT $offset , $no_of_posts_per_page";
    }//Author can only see their posts
    if($_SESSION['user']['role'] == 'Author'){
        $user_id = $_SESSION['user']['id'];
        $sql = "SELECT * FROM posts WHERE user_id = '$user_id' AND news = true LIMIT $offset , $no_of_posts_per_page";
    }
    $result = mysqli_query($conn , $sql);
    $post = mysqli_fetch_all($result , MYSQLI_ASSOC );  
    //create an assoc array called final post
    $final_posts = array();
    foreach($post as $posts){
    $post['Author'] = getPostAuthorById($posts['user_id']);
    $post['topic'] = getPostTopic($posts['id']);
    //save all post as an associative array
    array_push($final_posts , $posts);
    }

    return ["total_page" => $total_page , "post" => $final_posts ];
}


function getAllPubNewsCount(){
    global $conn;
    $sql = "SELECT COUNT(*) AS total FROM posts WHERE news = true AND published = true ";
    $rs = mysqli_query($conn , $sql);
    $result = mysqli_fetch_assoc($rs);
    return $result['total'];
}

function getAllUnPubNewsCount(){
    global $conn;
    $sql = "SELECT COUNT(*) AS total FROM posts WHERE news = true AND published != true ";
    $rs = mysqli_query($conn , $sql);
    $result = mysqli_fetch_assoc($rs);
    return $result['total'];
}

//recive a user_id and return the author username
function getPostAuthorById($user_id){
    global $conn;
    $sql = "SELECT username FROM users WHERE id = $user_id";
    $result = mysqli_query($conn , $sql );
    $username = mysqli_fetch_assoc($result)['username'];
    return $username;
}

//recieve a post id and return its topic
function getPostTopic($post_id){
    global $conn;
    $sql = "SELECT * FROM topics WHERE id = (SELECT topic_id FROM posts_topics WHERE post_id = $post_id) LIMIT 1";
    $result = mysqli_query($conn , $sql);
    $topic = mysqli_fetch_assoc($result)['name'];
    return $topic;
}

/**********
 * *Post Action
 * ********/

//if user click on create post
if(isset($_POST['create_post'])){
    createPost($_POST);
}

//if user click on edit post 
if(isset($_GET['edit-post'])){
    $isEditingPost = true;
    $encrypt_2 = base64_decode(urldecode($_GET['edit-post']));
    $post_id = ((($encrypt_2*956783)/5678)/123456789);
    editPost($post_id);
}

//if user click on update post
if(isset($_POST['update_post'])){
   updatePost($_POST);
}

//if user click on delete post
if(isset($_GET['delete-post'])){
   $encrypt_2 = base64_decode(urldecode($_GET['delete-post']));
   $post_id = ((($encrypt_2*956783)/5678)/123456789);
   deletePost($post_id);
}


/****************
 * ****Main function
 * **************/

function createPost($request_values){
    global $featured_image,$topic_id, $title , $body, $errors , $published , $conn ;
    //check for valid input
    //escape value from form
    $title = esc($request_values['title']);
    $body = htmlentities(esc($request_values['body']));

    if(isset($request_values['topic_id'])){
        $topic_id = esc($request_values['topic_id']);
    }
    if(isset($request_values['publish'])){
        $published = esc($request_values['publish']);
    }
    //create slug if slug is "Love of my life" return "love of my life
    $post_slug = makeSlug($title);
    //validate form
    if(empty($title)){
        array_push($errors , "Post title is required!");
    }
    if(empty($body)){
        array_push($errors , "Post body is required!");
    }
    if(empty($topic_id)){
        array_push($errors , "post topic is required!");
    }    
    $featured_image = $_FILES['featured_image']['name'];
    if(empty($featured_image)){
        array_push($errors , "Featured image is required!");
    }
    //target the image file directory
    $target = "../CSS/MEDIA/" . basename($featured_image);
    if(!move_uploaded_file($_FILES['featured_image']['tmp_name'],$target)){
        array_push($errors , "Image failed to upload");
    }
    //make sure file is not uploaded twice
    $sql = "SELECT * FROM posts WHERE slug = '$post_slug' LIMIT 1 ";
    $result = mysqli_query($conn , $sql);
    //if post exist
    if(mysqli_num_rows($result) > 0 ){
        array_push($errors , "Post already exist");
    }
    //if no error save post in database
    if(count($errors) == 0){
        $user_id = $_SESSION['user']['id'];
        $query = "INSERT INTO posts ( user_id , title , slug , images , body , published , created_at , updated_at)  
                     VALUES('$user_id' , '$title' , '$post_slug' , '$featured_image' , '$body' , '$published' , now() , now())";
        $result = mysqli_query($conn , $query); 
        //if post created successfully
        if($result){
            $inserted_id = mysqli_insert_id($conn);
            $sql = "INSERT INTO posts_topics (post_id , topic_id) VALUES('$inserted_id' , '$topic_id') ";
            $result = mysqli_query($conn , $sql);
            $_SESSION['message'] = "Post created successfully";
            header("location:".$config_basedir."posts.php");
            exit(0);
        }
    }
}

//get a post-id as a parameter
//fetch the post from database
//set the post for editing
function editPost($post_id){
    global $published , $post_id , $featured_image , $topic_id , $title , $body , $error , $conn;
    $sql = "SELECT * FROM posts WHERE id = '$post_id'";
    $result = mysqli_query($conn , $sql);
    $post = mysqli_fetch_assoc($result);
    //set form value on the post form
    $title = $post['title'];
    $body = $post['body'];
    $published = $post['published'];
}

//update post
function updatePost($request_values){
    global $post_id , $featured_image,$topic_id, $title , $body, $errors ,$conn;

    $title = esc($request_values['title']);
    $body = htmlentities(esc($request_values['body']));
    $post_id = esc($request_values['post_id']);
    if(isset($request_values['topic_id'])){
        $topic_id = esc($request_values['topic_id']);
    }
    //create slug if slug is "Love of my life" return "love of my life
    $post_slug = makeSlug($title);
    //validate form
    if(empty($title)){
        array_push($errors , "Post title is required!");
    }
    if(empty($body)){
        array_push($errors , "Post body is required!");
    }
    if(empty($topic_id)){
        array_push($errors , "post topic is required!");
    }    
    $featured_image = $_FILES['featured_image']['name'];
    if(empty($featured_image)){
        array_push($errors , "Featured image is required!");
    }
    //target the image file directory
    $target = "../CSS/MEDIA/" . basename($featured_image);
    if(!move_uploaded_file($_FILES['featured_image']['tmp_name'],$target)){
        array_push($errors , "Image failed to upload");
    }
    //if there is no error in the file
    if(count($errors) == 0){
        $query = "UPDATE posts SET title = '$title' , slug = '$post_slug' , views = 0 , images='$featured_image' , 
                  body = '$body' , published = '$published' , updated_at = now()  WHERE id = $post_id";
                  //attach topic to post on post topic
        if(mysqli_query($conn , $query)){//if post created successfully
            if(isset($topic_id)){
                $inserted_post_id = mysqli_insert_id($conn);
                //create relashionship between post and topic
                $sql = "INSERT INTO post_topic(post_id , topic_id) VALUES( $inserted_post_id , $topic_id )";
                mysqli_query($conn , $sql);
            }     
        }
        $_SESSION['message'] = "Post updated successfully";
        header("location:".$config_basedir."posts.php");
        exit(0);
    }
}

//recieve a post id as parameter
//delete post record from database
function deletePost($post_id){
    global $conn;
    $sql = "DELETE FROM posts WHERE id = '$post_id' ";
    $result = mysqli_query($conn , $sql);
    if($result){
        $_SESSION['message'] = "Post deleted successfully";
        header("location:".$config_basedir."posts.php");
        exit(0);
    }
}

/**********
 * *News  Action
 * ********/

//if user click on create post
if(isset($_POST['create_news'])){
    createNews($_POST);
}

//if user click on edit post 
if(isset($_GET['edit-news'])){
    $isEditingNews = true;
    $encrypt_2 = base64_decode(urldecode($_GET['edit-news']));
    $post_id = ((($encrypt_2*956783)/5678)/123456789);
    editNews($post_id);
}

//if user click on update post
if(isset($_POST['update_news'])){
   updateNews($_POST);
}

//if user click on delete post
if(isset($_GET['delete-news'])){
   $encrypt_2 = base64_decode(urldecode($_GET['delete-news']));
   $news_id = ((($encrypt_2*956783)/5678)/123456789);
   deleteNews($news_id);
}


/****************
 * ****Main function
 * **************/
function createNews($request_values){
    global $featured_image, $title , $body, $errors , $published , $conn ;
    //check for valid input
    //escape value from form
    $title = esc($request_values['title']);
    $body = htmlentities(esc($request_values['body']));

    if(isset($request_values['topic_id'])){
        $topic_id = esc($request_values['topic_id']);
    }
    if(isset($request_values['publish'])){
        $published = esc($request_values['publish']);
    }
    //create slug if slug is "Love of my life" return "love of my life
    $post_slug = makeSlug($title);
    //validate form
    if(empty($title)){
        array_push($errors , "News title is required!");
    }
    if(empty($body)){
        array_push($errors , "News body is required!");
    }   
    $featured_image = $_FILES['featured_image']['name'];
    if(empty($featured_image)){
        array_push($errors , "Featured image is required!");
    }
    //target the image file directory
    $target = "../CSS/MEDIA/" . basename($featured_image);
    if(!move_uploaded_file($_FILES['featured_image']['tmp_name'],$target)){
        array_push($errors , "Image failed to upload");
    }
    //make sure file is not uploaded twice
    $sql = "SELECT * FROM posts WHERE slug = '$post_slug' AND news != false LIMIT 1 ";
    $result = mysqli_query($conn , $sql);
    //if post exist
    if(mysqli_num_rows($result) > 0 ){
        array_push($errors , "News already exist");
    }
    //if no error save post in database
    if(count($errors) == 0){
        $user_id = $_SESSION['user']['id'];
        $query = "INSERT INTO posts ( user_id , title , slug , images , body , published , news , created_at , updated_at)  
                     VALUES('$user_id' , '$title' , '$post_slug' , '$featured_image' , '$body' , '$published' , 1 , now() , now())";
        $result = mysqli_query($conn , $query); 
        //if post created successfully
        if($result){
            $_SESSION['message'] = "News created successfully";
            header("location:".$config_basedir."Manage-news.php");
            exit(0);
        }else{
            echo "error";
        }
    }
}

//get a post-id as a parameter
//fetch the post from database
//set the post for editing
function editNews($post_id){
    global $published , $post_id,$featured_image,$topic_id, $title , $body , $error , $conn;
    $sql = "SELECT * FROM posts WHERE id = '$post_id' AND news = true";
    $result = mysqli_query($conn , $sql);
    $post = mysqli_fetch_assoc($result);
    //set form value on the post form
    $title = $post['title'];
    $body = $post['body'];
    $published = $post['published'];
}

//update post
function updateNews($request_values){
    global $post_id , $featured_image, $title , $body, $errors ,$conn;
    $title = esc($request_values['title']);
    $body = htmlentities(esc($request_values['body']));
    $post_id = esc($request_values['post_id']);
    //create slug if slug is "Love of my life" return "love-of-my-life
    $post_slug = makeSlug($title);
    //validate form
    if(empty($title)){
        array_push($errors , "News title is required!");
    }
    if(empty($body)){
        array_push($errors , "News body is required!");
    }
    $featured_image = $_FILES['featured_image']['name'];
    if(empty($featured_image)){
        array_push($errors , "Featured image is required!");
    }
    //target the image file directory
    $target = "../CSS/MEDIA/" . basename($featured_image);
    if(!move_uploaded_file($_FILES['featured_image']['tmp_name'],$target)){
        array_push($errors , "Image failed to upload");
    }
    //if there is no error in the file
    if(count($errors) == 0){
        $query = "UPDATE posts SET title = '$title' , slug = '$post_slug' , views = 0 , images='$featured_image' , 
                  body = '$body' , published = '$published' , news = 1 , updated_at = now()  WHERE id = $post_id";
                  //attach topic to post on post topic
        $_SESSION['message'] = "News updated successfully";
        header("location:".$config_basedir."Manage-news.php");
        exit(0);
    }
}

//recieve a post id as parameter
//delete post record from database
function deleteNews($news_id){
    global $conn;
    $sql = "DELETE FROM posts WHERE id = '$news_id' AND news = true ";
    $result = mysqli_query($conn , $sql);
    if($result){
        $_SESSION['message'] = "News deleted successfully";
        header("location:".$config_basedir."Manage-news.php");
        exit(0);
    }
}


//if user click on publish checkbox
if(isset($_GET['publish']) || isset($_GET['unpublish'])){
    $message = "";
    if(isset($_GET['publish'])){
        $message = "Post successfully published";
        $post_id = $_GET['publish'];
        $encrypt_2 = base64_decode(urldecode($post_id));
        $post_id = ((($encrypt_2*956783)/5678)/123456789);
    }else{
        $message = " Post successfully unpublished ";
        $post_id = $_GET['unpublish'];
        $encrypt_2 = base64_decode(urldecode($post_id));
        $post_id = ((($encrypt_2*956783)/5678)/123456789);
    }
    togglePublishedPost($post_id , $message);
}

function togglePublishedPost($post_id , $message){
    global $conn;
    $sql = "UPDATE posts SET published = !published WHERE id = $post_id";
    $result = mysqli_query($conn , $sql);
    if($result){
         //for future references  
    }
}

//if user want post to be public or not
if(isset($_GET['public']) || isset($_GET['unpublic'])){
    if(isset($_GET['public'])){
        $encrypt_2 = base64_decode(urldecode($_GET['public']));
        $user_id = ((($encrypt_2*956783)/5678)/123456789);
    }else{
        $user_id = $_GET['unpublic'];
        $encrypt_2 = base64_decode(urldecode($_GET['unpublic']));
        $user_id = ((($encrypt_2*956783)/5678)/123456789);
    }
    togglePublicUser($user_id);
}

function togglePublicUser($user_id){
    global $conn;
    $sql = "UPDATE users SET public = !public WHERE id = $user_id";
    $result = mysqli_query($conn , $sql);
}

//return all topic name from database
function getTopics(){
    global $conn;
    $sql = "SELECT * FROM topics";
    $result = mysqli_query($conn , $sql);
    $topic = mysqli_fetch_all($result , MYSQLI_ASSOC);
    return $topic;
}

//get total number of published post
function getPublishedPostCount(){
    global $conn;
    $sql = "SELECT COUNT(*) AS total FROM posts WHERE published = true ";
    $result = mysqli_query($conn , $sql);
    $data = mysqli_fetch_assoc($result);
    return $data['total'];
}

function getCommentsCountByPostId(){
   global $conn;
   $sql = "SELECT COUNT(*) AS total FROM comments ";
   $result = mysqli_query($conn , $sql);
   $data = mysqli_fetch_assoc($result);
   return $data['total'];
}

function returnTotalShare($id){
    global $conn;
    $sql = "SELECT share FROM posts WHERE id = '$id' ";
    $result = mysqli_query($conn , $sql);
    $topic = mysqli_fetch_assoc($result);
    return $topic['share'];
}

function returnTotalComments($id){
    global $conn;
    $sql = "SELECT COUNT(*)AS total FROM comments WHERE id = '$id' ";
    $result = mysqli_query($conn , $sql);
    $topic = mysqli_fetch_assoc($result);
    return $topic['total'];
}

function returnTotalLikes($p_id){
    global $conn;
    $sql = "SELECT COUNT(*) FROM rating_info WHERE post_id = $p_id AND rating_action='like'";
    $rs = mysqli_query($conn , $sql);
    $result = mysqli_fetch_array($rs);
    return $result[0];
}

function returnTotalDislikes($p_id){
    global $conn;
    $sql = "SELECT COUNT(*) FROM rating_info WHERE post_id = $p_id AND rating_action='dislike'";
    $rs = mysqli_query($conn , $sql);
    $result = mysqli_fetch_array($rs);
    return $result[0];
} 

function getTotalClickcountById($id){
    global $conn;
    $sql = "SELECT * FROM ads WHERE id = $id";
    $rs = mysqli_query($conn , $sql);
    $result = mysqli_fetch_assoc($rs)['click'];
    return $result;
}

?>