<!--includes the database connection file-->
<?php include ("../config/config.php"); ?>
<!--include the functionality file-->
<?php include ("includes/Post_function.php");?>
<?php include ("includes/Admin_function.php")?>

<?php 

if(isset($_GET['pageno'])){
   $pageno = $_GET['pageno'];
   $post = $post = getAllNews($pageno);
   extract($post);
}else{
   $pageno = 1;
   $post = $post = getAllNews($pageno);
   extract($post);
}

?>
<!--includes the head file-->
<?php include("includes/head-file.php");?>
    <title>BloomBlu | News</title>
    <link rel="stylesheet" href="../CSS/Admin_style.css">
</head>
<body>
     <!--redirect to basedirectory if user is not an Admin or Author-->
    <?php if(isset($_SESSION['user'])): ?>
          <!--check if user is not Admin or Author-->
        <?php if(in_array($_SESSION['user']['role'] , ['Admin','Author'])):?>
               <!--do nothing-->
        <?php else: ?>
              <?php header("location:" .$config_basedir."error.php");?>
        <?php endif ?> 
      <?php else: ?>
            <?php header("location:".$config_basedir."error.php"); ?>
      <?php endif ?>
     <!--include the header navigation section-->
     <?php include ("includes/header.php");?>
<div id="container">
    <div class="Admin-user-tab">
        <!--include the message bar-->
        <?php include ("includes/message.php")?> 
        <div class="news-content-wrapper">  
            <div class="news-container">
               <?php include ("search.php") ;?>
                    <h1 class="news-link"><?php echo getAllPubNewsCount();?></h1><span>published news</span></a>
                    <h1 class="news-link"><?php echo getAllUnPubNewsCount();?></h1><span>unpublish news</span></a>
            </div>
            <div class="news-details-wrapper">
            <?php foreach($post as $key => $posts):?>
            <div class="news-details">
                 <span>
                       <img src="../CSS/MEDIA/<?php echo $posts['images']; ?>" alt="" style="width: 300px; height: 100px;">
                       <p><td><?php echo $posts['title'];?></td></p>
                       <p><?php echo getPostTopic($posts['id']);?></p>
                       <p>created on <?php echo date(" Y-M-d , H:ia" , strtotime($posts['created_at'])); ?></p>
                       <p>Written By:&nbsp<?php echo getPostAuthorById($posts['user_id']); ?></p>
                 </span>
                 <span class="News-action">
                    <div class="publish">
                        <!--Only admin can publish post-->
                    <?php if($_SESSION['user']['role'] == "Admin" ): ?>
                          <?php if($posts['published'] == true): ?>
                          <td>
                             unpublish<a class="fa fa-check btn publish" href="<?php echo $config_basedir ?>Admin/Manage-news.php?unpublish=<?php echo hashUrlData($posts['id']); ?>"></a>
                          </td>
                          <?php else: ?>
                          <td>
                             publish<a class="fa fa-times btn unpublished" href="<?php echo $config_basedir; ?>Admin/Manage-news.php?publish=<?php echo hashUrlData($posts['id']); ?>"></a>
                          </td>
                       <?php endif ?>
                    <?php endif ?>
                    </div>
                    <div class="news-effect">
                         <p><i style="color:black" class="fa fa-eye"></i><?php echo $posts['views']; ?></p>
                         <p><i style="color:black" class="fa fa-share"></i><?php echo returnTotalShare($posts['id']); ?></p>
                         <p><i style="color:black" class="fa fa-comment"></i><?php echo returnTotalComments($posts['id']);?></p>
                         <p><i class="fa fa-thumbs-o-up"></i><?php echo returnTotalLikes($posts['id']);?></p>
                         <p><i class="fa fa-thumbs-o-down"></i><?php echo returnTotalDislikes($posts['id']); ?></p>
                         <p><i style="color:black" class="fa fa-edit"></i>
                            <a href="<?php echo $config_basedir ?>Admin/create-news.php?edit-news=<?php echo hashUrlData($posts['id']);?>"></a>
                         </p>
                         <p><i style="color:black" class="fa fa-trash">M</i>
                            <a href="<?php echo $config_basedir ?>Admin/Manage-news.php?delete-news=<?php echo hashUrlData($posts['id']);?>"></a>
                         </p>
                    </div>
                 </span>
             </div>
            <?php endforeach ?>
            </div>
        </div>

       <!--include pagination-->
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

    </div>
        <!--Only admin user should be able to mange user and post-->
        <?php if($_SESSION['user']['role'] == "Admin"): ?>
            <!--include the side nav section-->
            <?php include ("includes/navbar.php")?>
        <?php endif ?>
    </div><!--container-->
   <?php include ("includes/footer.php"); ?>
</body>
</html>     