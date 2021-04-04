<!--include the config file for database connection-->
<?php include ("config/config.php");?>
<!--include the public function-->
<?php include ("includes/public_function.php");?>
<?php
if(isset($_GET['post-slug'])){
      $post = getPost($_GET['post-slug']);
}
$topic = getAllTopic();
if (isset($post)) {
    if ($_COOKIE['visitor']) {
        updatePostView($_COOKIE['visitor'], $post['id']);
    } else {
        //set cookie for post views
        if (!isset($_SESSION['visitor'])) {
            //set new cookie
            $name = 'visitor';
            $value = session_id();
            setcookie($name, $value, time() + (3600 * 24 * 30));
            updatePostView($_COOKIE['visitor'], $post['id']);
        } else {
            //use-ip
            $cookie_key = $_SERVER['REMOTE_ATTR'];
            updatePostView($cookie_key, $post['id']);
        }
    }
}
?>
<!--include the head-section-->
<?php include ("includes/head-file.php")?>
   <title>Bloomblu | <?php if(isset($post)){ echo $post['title']; } ?></title>
   <link rel="stylesheet" href="CSS/main.css">
   <link rel="stylesheet" href="CSS/public_style.css">
   <link rel="stylesheet" href="CSS/resp_tab.css">
   <script src="CSS/JS/script.js"></script>
   <script src="JS/like-dislike.js"></script>
   <!--//FB share-->
   <meta proterty="og:url" content="127.0.0.1/Bloomblue/single-post.php">
   <meta proterty="og:type" content="website">
   <meta proterty="og:title" content="<?php echo $post['title']; ?>">
   <meta proterty="og:description" content="<?php echo $post['body']; ?>">
   <meta proterty="og:image" content="<?php echo $post['images']; ?>">
   <meta proterty="og:image:width" content="800">
   <meta proterty="og:image:height" content="600">
   <!--//FB share-->
</head>
<body>

<div class="container">  
<a href="top"></a> 
      <!--include the head navigation section -->
      <?php include ("includes/header.php");?>
      <?php if(isset($post)): ?>
         <div class="post-container">
           <div class="post-content">
               <div class="full-post-content">
                 <?php if($post['published'] == false):?>
                     <?php include ("includes/post-error.php"); ?>
                 <?php else:?>
                  <img src="CSS/MEDIA/<?php echo $post['images'];?>"  class="post-image" alt="">
                  <div class="post-overlay">
                      <div class="post-overlay-wrapper">
                            <h2 class="post-title"><?php echo $post['title'];?></h2>
                            <p><span class="post-dets">Written by &nbsp</span><?php echo returnAuthorNameBypostid($post['id']);?></p>on 
                            <p><?php echo date("d, M, Y  H:ia" ,strtotime($post['created_at']));?></p>
                      </div>
                  </div>
         
                 <?php endif ?>
                        <div class="main-post">
                          <?php echo  html_entity_decode($post['body']); ?>
                        </div>
               </div>
               <!--create the like and dislike action-->
            <div class="post-action" style="display:flex">
               <div class="post-action-page" style="display:inline-flex">
                   <!--if user liked-->
                  <p><i style="font-size:1.2em;color:black;" class="fa fa-eye"></i><?php echo returnTotalViews($post['id']) ?></p>&nbsp
                  <?php if(isset($_SESSION['user'])): ?>
                       <i <?php if(userLiked($post['id'])): ?>
                           class="fa fa-thumbs-up like-btn"
                         <?php else: ?>
                           class="fa fa-thumbs-o-up like-btn"
                         <?php endif ?>  
                           data-id="<?php echo $post['id'] ?>">
                       </i>
                  <?php endif ?>
                     <span class="likes">
                      <?php if(!isset($_SESSION['user'])):?>
                         <i class="fa fa-thumbs-o-up"></i>
                      <?php endif  ?>
                      <?php echo returnTotalLikes($post['id']); ?>
                     </span>
                   &nbsp&nbsp&nbsp
                  
                   <!--if user dislike post , style button differently-->
                  <?php if(isset($_SESSION['user'])): ?> 
                     <i <?php if(userDisliked($post['id'])): ?>
                        class="fa fa-thumbs-down dislike-btn"
                      <?php else: ?>
                        class="fa fa-thumbs-o-down dislike-btn"
                      <?php endif ?>
                      data-id="<?php echo $post['id'] ?>">
                    </i> 
                  <?php endif ?>    
                       
                  <span class="dislikes">
                        <?php if(!isset($_SESSION['user'])):?>
                           <i class="fa fa-thumbs-o-down"></i>
                        <?php endif  ?>
                        <?php echo returnTotalDislikes($post['id'])?>
                  </span>
                  &nbsp&nbsp
                  <!--post action details-->
                  <p><i style="font-size:1.2em;color:black;" class="fa fa-share"></i><?php echo returnTotalShare($post['id']) ?></p>
                  &nbsp&nbsp

                  <?php
                  $newLink = "single-post.php?post-slug=";
                  $baseURl = $config_basedir.$newLink;
                  
                  ?>
                     <a href="whatsapp://send?text=<URL>" data-action="share/whatsapp/share" onClick="javascript:window.open(this.href, '' , 'menubar=no,toolbar=no,resizeable=yes,scrollbars=yes,height=300,width=600'); return false;" target="_blank" title="Share on whatsapp"><img class="share-icon" src="./CSS/PIC/icon01.png" alt="icon"></a>
                     <a href="#"><img class="share-icon" src="./CSS/PIC/icon03.png" alt="icon"></a>
                     <a target="_blank" href="http://www.twitter.com/share?text=Visit the link & url=<?php echo $baseURl.$post['slug'] ?>"><img class="share-icon" src="./CSS/PIC/icon05.png" alt="icon"></a>
                     <a target="_blank" href="http://www.facebook.com/share.php?u=<?php echo $baseURl.$post['slug'] ?>"><img class="share-icon" src="./CSS/PIC/icon04.png" alt="icon"></a>
               </div>
            </div>
               <?php if($post['published'] != false):?>
                  <?php include ("includes/comment.php");?>
                <?php endif ?>

               <h2 class="Topics">Topics</h2>
            
               <?php foreach($topic as $topics):?>
                   <ul class="pagination" style="display: inline-flex;">
                       <li>
                       <a href="filtered_post.php?topic=<?php echo $topics['id']?>"><?php echo returnTotalPostByTopicId($topics['id']); echo $topics['name'] ;?></a>
                     </li>
                  </ul>
               <?php endforeach ?>
               <?php include ("includes/ads03.php");?>
           </div>
         </div>
         <?php else: ?>
            <h2 style="text-align:center" >This Page is not Available</h2>
         <?php endif ?>
      <!--include the footer page-->
      <?php include ("includes/footer.php");?>
</div>      
</body>
</html>
