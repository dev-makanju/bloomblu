<?php
//get all published post and return them as an associtaive array

function getPublishedPost($pageno){
    global $conn;
    $no_of_posts_per_page = 10;
    $offset = ($pageno-1) * $no_of_posts_per_page;
    $sql = "SELECT COUNT(*)FROM posts WHERE published = true AND news != true ";
    $result = mysqli_query($conn , $sql);
    $total_rows = mysqli_fetch_array($result)[0];
    $total_page = ceil($total_rows / $no_of_posts_per_page);
    $sql = "SELECT * FROM posts WHERE published = true AND news != true LIMIT $offset , $no_of_posts_per_page";
    $result = mysqli_query($conn , $sql);
    //get all post as an assc array
    $post = mysqli_fetch_all($result , MYSQLI_ASSOC);

    $final_post = array();
    foreach($post as $posts){
        $posts['topic'] = getPostTopic($posts['id']); 
        array_push($final_post , $posts);
    }
    return ['total_page'=>$total_page , 'posts'=>$final_post];
}

//recieve a post id and return its topic
function getPostTopic($post_id){
    global $conn;
    $sql = "SELECT * FROM topics WHERE id = (SELECT topic_id FROM posts_topics WHERE post_id = $post_id) LIMIT 1";
    $result = mysqli_query($conn , $sql);
    $topic = mysqli_fetch_assoc($result);
    return $topic;
}

function getPublishedNews($pageno){
    global $conn;
    $no_of_posts_per_page = 10;
    $offset = ($pageno-1) * $no_of_posts_per_page;
    $sql = "SELECT COUNT(*)FROM posts WHERE published = true AND news = true";
    $result = mysqli_query($conn , $sql);
    $total_rows = mysqli_fetch_array($result)[0];
    $total_page = ceil($total_rows / $no_of_posts_per_page);
    $sql = "SELECT * FROM posts WHERE published = true  AND news = true LIMIT $offset , $no_of_posts_per_page";
    $result = mysqli_query($conn , $sql);
    //get all post as an assc array
    $post = mysqli_fetch_all($result , MYSQLI_ASSOC);

    $final_post = array();
    foreach($post as $posts){
        $posts['topic'] = getPostTopic($posts['id']); 
        array_push($final_post , $posts);
    }
    return ['total_page'=>$total_page , 'news'=>$final_post];
}

function getsubtractContent($new_id){
    global $conn;
    $sql = "SELECT * FROM posts WHERE id=$new_id";
    $rs = mysqli_query($conn , $sql);
    $result = mysqli_fetch_assoc($rs)['body'];
    $offset = substr($result , 0 , 1000);
    return $offset;
}


/*************************
 * **Recieve an id and return all published post with the id
 * **********************/

function getPublishedPostByTopic($topic_id , $pageno){
    global $conn;
    $no_of_posts_per_page = 2;
    $offset = ($pageno-1) * $no_of_posts_per_page;
    $sql = "SELECT COUNT(*)FROM posts WHERE published = true";
    $result = mysqli_query($conn , $sql);
    $total_rows = mysqli_fetch_array($result)[0];
    $total_page = ceil($total_rows / $no_of_posts_per_page);
    $sql = "SELECT * FROM  posts ps WHERE ps.id IN (SELECT pt.post_id FROM posts_topics pt WHERE pt.topic_id = $topic_id GROUP BY pt.post_id HAVING COUNT(1) = 1) LIMIT $offset , $no_of_posts_per_page ";
    $result = mysqli_query($conn , $sql);
    //fetch all posts as an associative array called $posts
    $posts = mysqli_fetch_all($result , MYSQLI_ASSOC);
    $final_posts = array();
    foreach ($posts as $post ){
        $post['topic'] = getPostTopic($post['id']);
        array_push($final_posts , $post);
    }
    return['total_page'=>$topic_id , 'post'=>$final_posts];
}

//RETURN TOPIC NAME BY ID
function getpostTopicDetails($id){
    global $conn;
    $sql = "SELECT * FROM topics ts WHERE ts.id = ( SELECT pt.topic_id FROM posts_topics pt WHERE post_id = '$id' ) LIMIT 1";
    $result = mysqli_query($conn , $sql);
    $topic = mysqli_fetch_assoc($result);
    return $topic;
}

function getTopicNameById($id){
    global $conn;
    $sql = "SELECT name FROM topics WHERE id = $id";
    $result = mysqli_query($conn , $sql);
    $topic = mysqli_fetch_assoc($result);
    return $topic['name'];
}


//get published post by unique slug
function getPost($post_slug){
    global $conn;
    $sql = "SELECT * FROM posts WHERE slug='$post_slug' AND published=true";
    $result = mysqli_query($conn , $sql);
    //fetch all post as an associative array
    $post = mysqli_fetch_assoc($result);    
    //get the topic to which the post belong
    if($post){
        //get the post id and return the topic name
        $post['topic'] = getPostTopic($post['id']);
    }   
    return $post; 
}

//********RETURNING FUNCTIONS *************/

function returnTotalShare($id){
    global $conn;
    $sql = "SELECT share FROM posts WHERE id = '$id' ";
    $result = mysqli_query($conn , $sql);
    $topic = mysqli_fetch_assoc($result);
    return $topic['share'];
}

function returnTotalViews($id){
    global $conn;
    $sql = "SELECT views FROM posts WHERE id = '$id' ";
    $result = mysqli_query($conn , $sql);
    $topic = mysqli_fetch_assoc($result);
    return $topic['views'];
}

function returnTopicId($id){
    global $conn;
    $sql = "SELECT id FROM topics WHERE id = '$id' ";
    $result = mysqli_query($conn , $sql);
    $topic = mysqli_fetch_assoc($result);
    return $topic['id'];
}

function returnAuthorNameBypostid($id){
    global $conn;
    $sql = "SELECT us.username FROM users us WHERE us.id = (SELECT ps.user_id FROM posts ps WHERE id = $id)";
    $rs = mysqli_query($conn , $sql);
    $result = mysqli_fetch_assoc($rs);
    return $result['username'];
}

function getAuthorDetailsByPostId($id){
    global $conn;
    $sql = "SELECT * FROM users WHERE id = (SELECT ps.user_id FROM posts ps WHERE id = $id AND public = true)";
    $rs = mysqli_query($conn ,$sql);
    $result = mysqli_fetch_assoc($rs);
    return $result;  
}

function updatePostView($cookie , $post_id){
    global $conn;
    $sql = "SELECT * FROM post_read_counter WHERE visitor_id = '$cookie' AND post_id='$post_id'";
    $res = mysqli_query($conn , $sql);
    if(mysqli_num_rows($res) == 0){     
        $sql = "INSERT INTO post_read_counter(visitor_id,post_id) VALUES('$cookie' ,'$post_id')";
        $rs = mysqli_query($conn , $sql);
        $res = "UPDATE posts SET Views=Views+1 , created_at=created_at WHERE id = '$post_id' ";
        $result = mysqli_query($conn , $res);
    }
}

//return all topics

function getAllTopic(){
    global $conn;
    $sql = "SELECT * FROM topics";
    $result = mysqli_query($conn , $sql);
    //fetch all topics as an assoc array
    $topics = mysqli_fetch_all($result , MYSQLI_ASSOC);
    return $topics;
}



/*******************
 * **COMMENT 
 * ************** */
 /*************************************************************/

/***************************
 * **comment section 
 * ************************/

//recieve a post id and return all comment under it
function returnPostId($post_slug){
    global $conn;
    $sql = "SELECT id FROM posts WHERE slug = '$post_slug' AND published = true LIMIT 1";
    $result = mysqli_query($conn , $sql);
    return $result;
}

function getAllComments($post_id){
    global $conn;
    $sql = "SELECT * FROM comments WHERE post_id = '$post_id' ORDER BY created_at DESC";
    $result = mysqli_query($conn, $sql);
    $comments = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $comments;
}

//recieve a user_id and return the username

function getUsernameById($id){
    global $conn;
    $result = mysqli_query($conn , "SELECT username FROM users WHERE id='".$id."' LIMIT 1");
    //return the username
    return mysqli_fetch_assoc($result)['username'];
}


//recieve a comment id and return the username
function getRepliesByCommentId($id){
    global $conn;
    $sql = "SELECT * FROM replies WHERE comment_id = '$id' ";
    $result = mysqli_query($conn , $sql);
    $replies = mysqli_fetch_all($result , MYSQLI_ASSOC );
    return $replies;
}


//recieve a post id and return the total number of comment on that id
function getCommentsCountByPostId($post_id){
    global $conn;
    $result = mysqli_query($conn , "SELECT COUNT(*) AS total FROM comments WHERE id = '$post_id'");
    $data = mysqli_fetch_assoc($result);
    return $data['total'];
}

//if the user click submit on comment function
//************************************* */
if(isset($_POST['comment_posted'])){
    publishComment($_POST);
}   
//************************************* */


function publishComment($request_values){
    global $conn ;
    //grab comment that were submitted through ajax call
    $comment_text = $request_values['comment_text'];
    $post_id = $request_values['post_id'];
    //insert comment into database
    $result = mysqli_query($conn , "INSERT INTO comments (post_id , user_id , body , created_at , updated_at) 
                   VALUES('$post_id' , '".$_SESSION['user']['id']."' , '$comment_text' , now() , null)");
    //query same comment from database to send back for display
    $inserted_id = $conn->insert_id;
    $res = mysqli_query($conn , "SELECT * FROM comments WHERE id = $inserted_id ");
    $inserted_comment = mysqli_fetch_assoc($res);
    //if all inserted comment was successfull get that same comment from database and return it
    if($result){
        $comment ="<div class='comment clearfix'>
                        <img src='CSS/PIC/smallprofile.jpg.png' class='profile_pic' alt='icon'>
                        <div class='comment-details'>
                            <span class='comment-name'>". getUsernameById($inserted_comment['user_id'])."</span>
                            <span class='comment-date'>". date(" F j,Y", strtotime($inserted_comment['created_at']))."</span>
                            <p>". $inserted_comment['body']."</p>
                            <a class='reply-btn' href='#' data_id='" . $inserted_comment['id'] ."'>reply</a>
                        </div> 
                        
                       <form action='single_post.php' class='reply_form clearfix' id='comment_reply_form_".  $inserted_comment['id']."' data_id='". $inserted_comment['id']."'>
                            <textarea class='form-cont' name='reply_text' id='reply_text' cols='30' rows='10'></textarea>
                            <button class='btn btn-primary btnn-xs pull-right submit-reply'>Submit reply</button>
                       </form>
                    </div>";
        $comment_info = array(
           'comment'=> $comment,
           'comments_count'=> getCommentsCountByPostId($post_id)
        );
        echo json_encode($comment_info);
        exit();
    }else{
        echo "error";
        exit();
    }
}

//process reply form data
if(isset($_POST['reply_posted'])){
    global $conn;
    //grab the reply that was submitted through ajax
    $reply_text = $_POST['reply_text'];
    $comment_id = $_POST['comment_id'];
    //insert into database
    $sql = "INSERT INTO replies (user_id , comment_id , body , created_at , updated_at) VALUES('".$_SESSION['user']['id']."' , '$comment_id' , '$reply_text' , now() , null)";
    $result = mysqli_query($conn , $sql);
    $inserted_id = $conn->insert_id;
    $res = mysqli_query($conn , "SELECT * FROM replies WHERE id=$inserted_id");
    $inserted_reply = mysqli_fetch_assoc($res);
    //if insert was successfull get that same from database and return it
    if($result){
        $reply = "<div class='comment reply clearfix'>
        <img src='CSS/PIC/smallprofile.jpg.png' class='profile_pic' alt='icon'>
        <div class='comment-details'>
            <span class='comment-name'>". getUsernameById($inserted_reply['user_id']) ."</span>
            <span class='comment-date'>". date('F j , Y' , strtotime($inserted_reply['created_at']))."</span>
            <p>". $inserted_reply['body'] ."</p>
            <a class='reply-btn' href='#'>reply</a>
        </div> 
      </div>";
      echo $reply;
      exit();
    }else{
        echo "error";
        exit();
    }
}

/*******************************
 * *>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
 * CONTACT US FUNCTIONALITY
 * *>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
 * ******************************/
//declaring variable 
$username = "";
$email = "";
$body = "";
$errors = array();
//Action
if(isset($_POST['submit_msg'])){
      sendMessage($_POST);
}

//function code
function sendMessage($request_values)
{
    global $conn , $username , $email , $errors ,$body;
    $username = esc($request_values['cont_name']);
    $email = esc($request_values['cont_email']);
    $body = htmlentities(esc($request_values['cont_body']));
    //validate form
    if(empty($username)){
        array_push($errors , "name is required!");
    }
    if(empty($email)){
        array_push($errors , "email is required! ");
    }
    if(empty($body)){
        array_push($errors , "message body is required!");
    }
    //dump message into the database
    $sql = "INSERT INTO message (name , email , body , created_at) VAlUES('$username' , '$email' , '$body' , now()";
    $result = mysqli_query($conn , $sql);
    if($result){
        $_SESSION['message'] = "Message successfully sent";
        header("location:".$config_basedir."contact.php");
        exit(0);
    }
}


/*************
 * *USER_RATING_AcTION
 * ***********/

if(isset($_POST['action'])){
    $post_id = $_POST['post_id'];
    $action = $_POST['action'];
    $user_id = $_SESSION['user']['id'];
    switch ($action){
        case 'like':
            $sql="INSERT INTO rating_info (user_id , post_id , rating_action) 
                  VALUES($user_id , $post_id , 'like') ON DUPLICATE KEY UPDATE rating_action='like'";
        break;
        case 'dislike':   
            $sql="INSERT INTO rating_info (user_id , post_id , rating_action) 
                  VALUES($user_id , $post_id , 'dislike') ON DUPLICATE KEY UPDATE rating_action='dislike'";
        break;
        case 'unlike':
            $sql = "DELETE FROM rating_info WHERE user_id = $user_id AND post_id = $post_id"; 
        break;
        case 'undislike':
            $sql = "DELETE FROM rating_info WHERE user_id = $user_id AND post_id = $post_id";
        break;
        default:
    break;              
    }
    $result = mysqli_query($conn , $sql) ;
    echo getRating($post_id);
    exit(0);
} 

//get total number of like for a particular post
function returnTotalLikes($p_id){
    global $conn;
    $sql = "SELECT COUNT(*) FROM rating_info WHERE post_id = $p_id AND rating_action='like'";
    $rs = mysqli_query($conn , $sql);
    $result = mysqli_fetch_array($rs);
    return $result[0];
}

//get total number of dislike for a particular post
function returnTotalDislikes($p_id){
    global $conn;
    $sql = "SELECT COUNT(*) FROM rating_info WHERE post_id = $p_id AND rating_action='dislike'";
    $rs = mysqli_query($conn , $sql);
    $result = mysqli_fetch_array($rs);
    return $result[0];
} 

function getRating($id){
    global $conn;
    $rating = array();
    $likes_query = "SELECT COUNT(*) FROM rating_info WHERE post_id = $id AND ratin_action = 'like'";
    $dislikes_query = "SELECT COUNT(*) FROM rating_info WHERE post_id = $id AND ratin_action = 'dislike'";
    $likes_rs = mysqli_query($conn , $likes_query);
    $dislikes_rs = mysqli_query($conn , $dislikes_query);
    $likes = mysqli_fetch_array($likes_rs);
    $dislikes = mysqli_fetch_array($dislikes_rs);
    $rating =[
        'likes' => $likes[0],
        'dislike' => $dislikes[0]
    ];
    return $json_encode($rating);
}

//check if user already like post or not
function userLiked($post_id){
    global $conn;
    $sql = "SELECT * FROM  rating_info WHERE user_id = '".$_SESSION['user']['id']."' AND post_id = $post_id AND rating_action = 'like' ";
    $result = mysqli_query($conn , $sql);
    if(mysqli_num_rows($result) > 0){
        return true;
    }else{
        return false;
    }
}

//check if user already dilike post
function userDisliked($post_id){
    global $conn;
    $sql = "SELECT * FROM  rating_info WHERE user_id = '".$_SESSION['user']['id']."' AND post_id = $post_id AND rating_action = 'dislike' ";
    $result = mysqli_query($conn , $sql);
    if(mysqli_num_rows($result) > 0){
        return true;
    }else{
        return false;
    }
}

//return time ago
function time_Ago($time){
    $time_ago = strtotime($time);
    $cur_time = time();
    $time_elapse = $cur_time - $time_ago;
    $seconds = $time_elapse;
    $minutes = round($time_elapse / 60);
    $hour = round($time_elapse / 3600);
    $days = round($time_elapse / 86400);
    $weeks = round($time_elapse / 604800);
    $months = round($time_elapse / 2600640);
    $years = round($time_elapse / 31207680);
    //seconds
    if ($seconds <= 60) {
        return "Just now";
    } elseif ($minutes <= 60) {
        if ($minutes == 1) {
            return "One minute ago";
        } else {
            return "$minutes minutes ago";
        }
    }
    //hours
    elseif($hour <= 24){
        if($hour == 1 ){
            return "An hour ago";
        }else{
            return "$hour hours ago";
        }

    }
    //DayS
    elseif ($days <= 7) {
        if ($days==1) {
            return "Yesterday";
        } else {
            return "$days days ago";
        }
    }
    //WEEKS
    elseif ($weeks <= 4.3) {
        if ($weeks == 1) {
            return "A week ago";
        } else {
            return "$weeks weeks ago";
        }
    }
    //Month
    elseif ($months <= 12) {
        if ($months === 1) {
            return "A month ago";
        } else {
            return "$months months ago";
        }
    }
    //YEARS
    else {
        if ($years == 1) {
            return "A year Ago";
        } else {
            return "$years years ago";
        }
    }
}


function getAds($ads_type){
    global $conn;
    $sql = "SELECT * FROM Ads WHERE ads_type ='$ads_type'";
    $rs = mysqli_query($conn , $sql);
    $result = mysqli_fetch_assoc($rs);
    return $result;
}


function returnTotalPostByTopicId($topic_id){
    global $conn;
    $sql = "SELECT COUNT(*) AS total FROM posts_topics WHERE topic_id = $topic_id ";
    $rs = mysqli_query($conn , $sql);
    $result = mysqli_fetch_assoc($rs);
    return $result['total'];
}


function esc(String $value){
    global $conn;
    $val = trim($value);
    $val = mysqli_real_escape_string($conn,$value);
    return $val;
}

function showSearchClick($data){
    global $conn;
    $query = "SELECT * FROM posts WHERE slug LIKE '%$data%' LIMIT 10";
    $res = mysqli_query($conn , $query);
    $EXE_query = mysqli_fetch_all($res , MYSQLI_ASSOC);
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


function makeSlug(String $string){
    $string = strtolower($string);
    $slug = preg_replace('/[^A-Za-z0-9-]+/','-',$string);
    return $slug;
}

?>