<!--include the database connection file-->
<?php include ("config/config.php");?>
<!--include the function file-->
<?php include ("includes/public_function.php");?>
<!--include the head section -->
<?php include ("includes/head-file.php")?>
<?php include ("includes/external_file.php");?>

<?php
if(isset($_GET['pageno'])){
    $pageno = $_GET['pageno'];
    $result = getPublishedPost($pageno);
    extract($result);
}else{
    $pageno = 1;
    $news = getPublishedPost($pageno);
    extract($news);
}
?>

<meta name="desciption" content="Bloomblue is a blog with mor functionality they we offer 24hrs entertainment , sport news">
    <title>BloomBlue | Home</title>
</head>
<body>
<div class="container">
<a href="top"></a> 
<script> (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){ (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o), m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m) })(window,document,'script','//www.google-analytics.com/analytics.js','ga'); ga('create', 'UA-69951031-1', 'auto'); ga('send', 'pageview'); </script>
    <!--main header navigation secion-->
    <?php include ("includes/header.php");?>
    <!--main banner page-->
    <?php include ("includes/banner.php")?>
    <!--content tab-->
        <h2 class="article">Recent Articles</h2>
    <hr>
    <!--more functions to come in here-->
    <?php include ('includes/ads01.php')   ?>
    <div class="content-tab-ta">
    <?php if($posts):?>    
       <?php foreach($posts as $post):?>
        <div>
            <div class="public_post_wrapper">
                <img src="CSS/MEDIA/<?php echo $post['images'];?>" class="image-wrapper" alt="">
                   <a href="single_post.php?post-slug=<?php echo $post['slug'];?>">
                      <div class="post-info">
                          <h3><?php echo $post['title']; ?></h3>
                          <span class="info">published &nbsp<?php echo time_ago($post['created_at']); ?></span>
                          <span class="read-more">Read more...</span>
                      </div>  
                   </a>
                   <span style="display:inline-flex;margin-inline-start:7px;margin: 2px;" class="post-activity-wrapper">
                       <p><i style="color:black ;padding:5px;" class="fa fa-eye"></i><?php echo returnTotalViews($post['id']);?></p>&nbsp
                       <p><i style="color:black ;padding:5px;" class="fa fa-thumbs-up"></i><?php echo returnTotalLikes($post['id'])?></p>&nbsp
                       <p><i style="color:black ;padding:5px;" class="fa fa-thumbs-o-down"></i><?php echo returnTotalDislikes($post['id'])?></p>&nbsp
                       <p><i style="color:black ;padding:5px;" class="fa fa-comment"></i><?php echo getCommentsCountByPostId($post['id'])?></p>&nbsp
                       <p><i style="color:black ;padding:5px;" class="fa fa-share"></i><?php echo returnTotalShare($post['id'])?></p>
                       <?php  $result = getpostTopicDetails($post['id']);?>
                       <p style="color:blue;"><a href="filtered_post.php?topic=<?php echo $result['id']; ?>">(~<?php echo $result['name'];?>~)</a></p>
                   </span>  
            </div><!--//public post wrapper-->   
            <?php endforeach ?> 
         

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

        <?php
            include ("includes/ads03.php");
        ?> 
        <?php else: ?>    
            <h2 style="margin: 20px 20px;">No post have been published yet</h2>
        <?php endif ?>   
        </div>   
    </div>  
    <!--footer section-->
    <?php include ("includes/footer.php");?>
</div>   
</body>
</html>