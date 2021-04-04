<!--includes the head file-->
<?php include ("includes/head-file.php");?>
<!--includes the database connection file-->
<?php include ("../config/config.php"); ?>
<?php include ("includes/Admin_function.php")?>
<!--include the functionality-->
<?php include ("includes/post_function.php")?>
<!--gat all topic from database-->
<?php $topic =  getTopics(); ?>
    <title>Bloomblu | create-news</title>
    <link rel="stylesheet" href="../CSS/Admin_style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.8.0/ckeditor.js"></script>
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
        <?php include ("../includes/errors.php")?>
        <?php include ("includes/message.php")?>
        <div class="create-forms">
        <div class="create_header">
            <h1>Create News</h1>
        </div>  
        <form class="Create_post form" action="<?php $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
        <?php if ($isEditingNews === true) :?>
            <input type="hidden" name="post_id" id="" value="<?php echo $post_id ;?>">
        <?php endif ?>
            <div class="user_form_control">
               <input type="text" name="title" id="" placeholder="Title" value="<?php echo $title ;?>" >
           </div>   
            <div class="user_form_control">
                 <label>Featured-image</label><input type="file" name="featured_image" id="">
            </div>
            <div class="user_form_control">
                <textarea name="body" id="body" cols="30" rows="10"><?php  echo $body; ?></textarea>
            </div>
          <!--Only Admin can published and unpublish post-->
          <?php if($_SESSION['user']['role'] === "Admin"):?>
            <!--if published is true-->
            <?php if($published == true ): ?>
                <label>unpublish</label><input type="checkbox" name="publish" id="published" value="1" checked="checked">
            <?php else: ?>
                <label>publish</label><input type="checkbox" name="publish" id="published" value="1">
            <?php endif ?> 
          <?php endif ?>    
            <!--if is editingPost is true-->
        <?php if($isEditingNews === true):?>
            <button type="submit" name="update_news">UPDATE NEWS</button>
        <?php else: ?>
            <button type="submit" name="create_news">CREATE NEWS</button>
        <?php endif ?>    
        </form>  
        </div>
      </div>
      <!--Only admin user should be able to mange user and post-->
        <?php if($_SESSION['user']['role'] == "Admin"): ?>
         <!--include the side nav section-->
         <?php include ("includes/navbar.php")?>
       <?php endif ?>  
    </div>
        <?php include ("includes/footer.php")?>    
</body>
</html>
<script>
   CKEDITOR.replace('body');
</script>