<?php $Author_bio_info = getAuthorDetailsByPostId($post['id']); ?>
<!--writer bio details--->
<div class="writer-bio-details">
     <span>
         <?php if(!empty($Author_bio_info['image'])): ?>
              <img src="./CSS/MEDIA/<?php echo $Author_bio_info['image']; ?>" alt="icon">
         <?php else: ?> 
              <img src="./CSS/PIC/profile.png" alt="icon">
         <?php endif ?>  
        
          
          <?php if(!empty($Author_bio_info['bio'])):?>
             <p><span class="info-dets">bio:</span><?php echo $Author_bio_info['bio'] ;?></p>
          <?php endif ?>
          <?php if(!empty($Author_bio_info['email'])):?>
          <p><span class="info-dets">Contact:</span>
              <a style="color:blue"href="mailto:<?php echo $Author_bio_info['email'] ?>"><?php echo $Author_bio_info['email'] ?></a>
          </p>
          <?php endif ?>
      </span>
</div>

<!----All comment and reply------>
<?php $comments = getAllComments($post['id']);?>

<h2>Post a comment</h2>
<div class="col-md-3 col-md-offset-3 comments-section">
       <?php if(isset($_SESSION['user'])):?>
        <form class="clearfix" action="<?php $_SERVER['SCRIPT_NAME'];?>" method="post" id="comment_form_<?php echo $post['id'];?>" data-id="<?php echo $post['id'];?>">
              <h4 style="font-size:18px;">post a comment:</h4>
              <textarea name="comment_text" id="comment_text" class="comment-textarea" cols="30" rows="10"></textarea><br>
              <button class="submit-comment" id="submit_comment" data-id="<?php echo $post['id'];?>">submit comm      ent</button>
        </form>
        <?php else: ?>
            <p class="info-dets">You need to be logged in to post a comment on this post, Kindly click to <a style="color: black;" href="Login.php">sign in</a>
              .Need an account? you can also <a style="color: black;" href="register.php">register</a> with us.</p>   
        <?php endif ?> 
        <!--display total number of content in this post-->
       <!--get comment count-->
        <h2><span id="comments_count"><?php echo count($comments); ?></span>Comments(s)</h2>
        <hr>
    <!--comment wrapper-->
  <div class="comment-scroll-wrapper">  
    <div id="comments-wrapper">   
       <?php if($comments): ?>
          <!--Display comments-->
          <?php foreach($comments as $comment):?>
            <!--comments-->
             <div class="comment clearfix">
                <img src="CSS/PIC/smallprofile.jpg.png" class="profile_pic" alt="icon">
                <div class="comment-details wrapper">
                    <span class="comment-name"><?php echo getUsernameById($comment['user_id']);?></span>
                    <span class="comment-date"><?php echo date(" F j,Y", strtotime($comment['created_at']));?></span>
                    <p><?php echo $comment['body'];?></p>
                    <?php if(isset($_SESSION['user'])): ?>
                    <a class="reply-btn" href="#" data-id="<?php echo $comment['id'];?>">reply</a>
                    <?php endif ?>
                </div> 
                <!--reply form-->
                <form action="<?php $_SERVER['PHP_SELF']; ?>" class="reply_form clearfix" id="comment_reply_form_<?php echo $comment['id'];?>" data-id="<?php echo $comment['id'];?>">
                      <textarea class="comment-textarea" name="reply_text" id="reply_text" cols="30" rows="10"></textarea><br>
                      <button class="submit-reply">Submit reply</button>
                </form>
                <!--reply-->
                <!--//get all replies-->
                <?php $replies = getRepliesByCommentId($comment['id']);?>
                <div class="replies_wrapper_<?php echo $comment['id'];?>">
                  <?php if(isset($replies)):?>
                    <?php foreach($replies as $reply):?>  
                    <div class="comment reply clearfix">
                      <img src="CSS/PIC/smallprofile.jpg.png" class="profile_pic" alt="icon">
                      <div class="comment-details">
                        <span class="comment-name"><?php echo getUsernameById($reply['user_id']);?></span>
                        <span class="comment-date"><?php echo date("F j , Y" , strtotime($reply['created_at']));?></span>
                        <p><?php echo $reply['body']?></p>
                        <a class="reply-btn" href="#">reply</a>
                      </div> 
                    </div>
                    <?php endforeach ?>
                  <?php endif ?>
                </div>  
             </div>
            <!--//comment clearfix-->  
          <?php endforeach ?>
      <?php else: ?>
          <h2>Be the first to comment on this post.</h2>
      <?php endif ?>  
    </div><!--comment_wrapper-->
 </div>   
 </div><!--//comment scroll wrapper-->