<!--includes the database connection file-->
<?php include ("../config/config.php"); ?>
<!--include the functionality file-->
<?php include ("includes/Post_function.php");?>
<?php include ("includes/Admin_function.php")?>
<?php 
if(isset($_GET['pageno'])){
   $pageno = $_GET['pageno'];
   $post = getAllPost($pageno);
   extract($post);
}else{
   $pageno = 1;
   $post = getAllPost($pageno);
   extract($post);
}

?>

<!--includes the head file-->
<?php include("includes/head-file.php");?>
    <title>BloomBlu | posts </title>
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
       <!--search-->
       <?php include ("search.php") ;?>
    <!--include the message bar-->
    <?php include ("includes/message.php")?>

    <div class="tab-scroll" style="height:100%;overflow-y:auto;">

    <table>
           <thead>
                <th>S/N</th>
                <th>Title</th>
                <th>Topic</th>
                <th>Views</th>
                <th>share</th>
                <th>comments</th>
                <th>like</th>
                <th>dislike</th>
                <th>Author</th>
                <th>edit</th>
                <th>delete</th>
                <!--Only Admin can publish post-->
                <?php if($_SESSION['user']['role'] == "Admin"):?>
                <th>Publish</th>
                <?php endif ?>
           </thead>  
        <tbody>    
          <?php foreach($post as $key => $posts):?>
          <tr>
               <td><?php echo $key + 1 ;?></td>
               <td><?php echo $posts['title'];?></td>
               <td><?php echo getPostTopic($posts['id']);?></td>
               <td><?php echo $posts['views'];?></td>
               <td><?php echo returnTotalShare($posts['id']);?></td>
               <td><?php echo returnTotalComments($posts['id']);?></td>
               <td><?php echo returnTotalLikes($posts['id']);?></td>
               <td><?php echo returnTotalDislikes($posts['id']);?></td>
               <td><?php echo getPostAuthorById($posts['user_id']); ?></td>
               <td>
                  <a  class="fa fa-edit btn" href="<?php echo $config_basedir ?>Admin/create_post.php?edit-post=<?php echo hashUrlData($posts['id']);?>"></a>
               </td>
               <td>
                  <a  class="fa fa-trash btn" href="<?php echo $config_basedir ?>Admin/create_post.php?delete-post=<?php echo hashUrlData($posts['id']);?>"></a>
               </td>
               <!--Only admin can publish post-->
               <?php if($_SESSION['user']['role'] == "Admin" ): ?>
                  <?php if($posts['published'] == true): ?>
                    <td>
                        <a class="fa fa-check btn publish" href="<?php echo $config_basedir ?>Admin/posts.php?unpublish=<?php echo hashUrlData($posts['id']); ?>"></a>
                    </td>
                  <?php else: ?>
                     <td>
                        <a class="fa fa-times btn unpublished" href="<?php echo $config_basedir; ?>Admin/posts.php?publish=<?php echo hashUrlData($posts['id']); ?>"></a>
                     </td>
                  <?php endif ?>
               <?php endif ?>
          </tr>
          <?php endforeach ?>
        </tbody>
     </table> 
     <!--End of scroll wrapper->
     <!--display record from database-->
   </div>
       </div> 
      <!--Only admin user should be able to mange user and post-->
      <?php if($_SESSION['user']['role'] == "Admin"): ?>
           <!--include the side nav section-->
           <?php include ("includes/navbar.php")?>
       <?php endif ?>
   </div>
   <!--Pagin buttons---> 
   
         <ul class="pagination" style="margin-top: 100px;">
            <li><a href="?pageno=1">First</a></li>
            <li class="<?php if($pageno <= 1){ echo 'disabled'; }?>">
                <a href="<?php if($pageno <= 1){ echo '#'; }else{echo "?pageno=".($pageno - 1);}?>">Prev</a>
            </li>
            <li class="<?php if($pageno >= $total_page){ echo 'disabled'; }?>">
                <a href="<?php if($pageno >= $total_page){ echo '#'; }else{ echo "?pageno=".($pageno + 1); }?>">Next</a>
            </li>
            <li><a href="?pageno=<?php echo $total_page; ?>">Last</a></li>
        </ul>

   <?php include ("includes/footer.php")?>
</body>
</html>     