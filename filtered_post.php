<!--include the config file for database connection-->
<?php include ("config/config.php");?>
<!--include the public function-->
<?php include ("includes/public_function.php")?>
<!--include the head-section-->
<?php

if(isset($_GET['pageno'])){
   $topic_id = $_GET['topic'];
   $pageno = $_GET['pageno'];
   $post = getPublishedPostByTopic($topic_id , $pageno);
   extract($post);
}else{
   $pageno = 1;
   $topic_id = $_GET['topic'];
   $post = getPublishedPostByTopic($topic_id , $pageno);
   extract($post);
}

?>
<?php include ("includes/head-file.php")?>
<meta name="description" content="view all our post under your desirable topics">
   <title>BloomBlu | <?php echo getTopicNameById($topic_id);?></title>
</head>
<body>
   <div class="container">
      <!--include the head navigation section -->
      <?php include("includes/header.php");?>
      <!--content-->
      <div class="Topic-content">
            <div class="content-title">
                <h2>Articles on <?php echo getTopicNameById($topic_id); ?></h2>   
            </div>    
            <hr>
            <!--post content wrapper-->
         <?php if($post):?>
            <div class="content">
              <?php foreach($post as $posts):?>
               <div class="content">
                  <img src="CSS/MEDIA/<?php echo $posts['images'];?>" class="image-wrapper" alt=""> 
                  <a href="single_post.php?post-slug=<?php echo $posts['slug'] ;?>">
                      <div class="post-info">
                          <h3><?php echo $posts['title']; ?></h3>
                          <span class="info">published &nbsp<?php echo time_ago($posts['created_at']); ?></span>
                          <span class="read-more">Read more...</span>
                      </div>
                  </a>     
               </div>   
              <?php endforeach ?>            
            </div>
            <!--Pagin buttons--->  
            <ul class="pagination">
                <li><a href="filtered_post.php?<?php echo $_SERVER['QUERY_STRING'];?>&pageno=1">First</a></li>
            <li class="<?php if($pageno <= 1){ echo 'disabled'; }?>">
                <a href="<?php if($pageno <= 1){ echo '#'; }else{echo "filtered_post.php?". $_SERVER['QUERY_STRING'] ."&pageno=".($pageno - 1);}?>">Prev</a>
            </li>
            <li class="<?php if($pageno >= $total_page){ echo 'disabled'; }?>">
                <a href="<?php if($pageno >= $total_page){ echo '#'; }else{ echo "filtered_post.php?".$_SERVER['QUERY_STRING']."&pageno=".($pageno + 1); }?>">Next</a>
            </li>
                <li><a href="filtered_post.php?<?php echo $_SERVER['QUERY_STRING'];?>&pageno=<?php echo $total_page; ?>">Last</a></li>
            </ul>
            <?php include ("includes/ads03.php");?>
            <?php else:?>
                 <h1>No published Articles</h1>
            <?php endif ?>
            <!--end of post content wrapper-->
         </div>
      <?php include ("includes/footer.php");?>
   </div>
</body>   
</html>