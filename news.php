<!--include the database connection file-->
<?php include ("config/config.php");?>
<!--include the function file-->
<?php include ("includes/public_function.php");?>
<!--include the head section -->
<?php include ("includes/head-file.php")?>

<?php
if(isset($_GET['pageno'])){
    $pageno = $_GET['pageno'];
    $result = getPublishedNews($pageno);
    extract($result);
}else{
    $pageno = 1;
    $news = getPublishedNews($pageno);
    extract($news);
}

?>
<meta name="desciption" content="Bloomblue is a blog with more functionality they we offer 24hrs entertainment , sport news">
    <title>BloomBlue | News</title>
    <script src="JS/newsletter.js"></script>
    <script src="JS/cookies.js"></script>
</head>
<body>
<div class="container">    
    <!--main header navigation secion-->
    <?php include ("includes/header.php");?>
    <!--message-->
    <?php include ("includes/message.php"); ?>
    <!--content tab-->
        <h2 class="article">Latest News</h2>
    <hr>
    <!--include the ads gasget -->
    <?php include ("includes/ads01.php")?>  
    <!--more functions to come in here-->
    
    <?php if(empty($news)): ?>
        <h1>No news has been published yet</h1>
    <?php else: ?>

    <?php foreach($news as $new):?>
    <div class="news-wrapper">
        <div>
            <div class="public_post_wrapper"><!--//public post wrapper-->
                <img src="./CSS/PIC/<?php echo $new['images']; ?>" class="news-image-wrapper" alt="">
                  <a href="single_post.php?post-slug=<?php echo $new['slug'];?>">
                      <div class="post-info">
                          <h3><?php echo $new['title'];?></h3>
                          <span class="info">published &nbsp<?php echo time_ago($new['created_at']); ?></span>
                          <span style="color:black" class="details">
                            <?php echo getsubtractContent($new['id']);?>
                        </span>
                              <a href="single_post.php?post-slug=<?php echo $new['slug'];?>">
                                   <span class="read-more">read more...</span>
                              </a>
                      <br>        
                    <span style="display:inline-flex;margin-inline-start:7px;margin: 2px;" class="post-activity-wrapper">
                       <p><i style="color:black;padding: 7px;" class="fa fa-eye"></i><?php echo returnTotalViews($new['id']);?></p>
                       <p><i style="color:black;padding: 7px;" class="fa fa-thumbs-up"></i><?php echo returnTotalLikes($new['id']) ;?></p>
                       <p><i style="color:black;padding: 7px;" class="fa fa-thumbs-o-down"></i><?php echo returnTotalDislikes($new['id']) ;?></p>
                       <p><i style="color:black; padding: 7px;" class="fa fa-comment"></i><?php echo getCommentsCountByPostId($new['id'])?></p>&nbsp
                       <p><i style="color:black;padding: 7px;" class="fa fa-share"></i><?php echo returnTotalShare($new['id']) ;?></p>
                    </span>
                     </div>  
                  </a>
           <h2></h2>  
           </div> 
        </div>   
    </div>    
    <?php endforeach ?>
    <!--Pagin buttons--->  
    <ul class="pagination">
            <li><a href="?pageno=1">First</a></li>
        <li class="<?php if($pageno <= 1){ echo 'disabled'; }?>">
            <a href="<?php if($pageno <= 1){ echo '#'; }else{echo "?pageno=".($pageno - 1);}?>">Prev</a>
        </li>
        <li class="<?php if($pageno >= $total_page){ echo 'disabled'; }?>">
          <a href="<?php if($pageno >= $total_page){ echo '#'; }else{ echo "?pageno=".($pageno + 1); }?>">Next</a>
        </li>
        <li><a href="?pageno=<?php echo $total_page; ?>">Last</a></li>
    </ul>
    
    <!--include the ads gasget -->
    <?php include ("includes/ads03.php")?>  
    <?php endif ?>    
    <!--footer section-->
    <?php include ("includes/footer.php");?>
</body>
</html>