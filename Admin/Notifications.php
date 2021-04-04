<!--includes the head file-->
<?php include ("includes/head-file.php");?>
<!--includes the database connection file-->
<?php include ("../config/config.php"); ?>
<?php include ("includes/Admin_function.php")?>
<!--include the functionality-->
<?php include ("includes/post_function.php")?>
<?php $message = getAllMessage();?>
    <title>Bloomblu | Notifications</title>
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
                <!--serch implementation-->
                <?php include ("search.php") ;?>
            <div class="notify-tab">
                <div class="header">
                    <h1>Notifications</h1>
                </div>
                <!--include the message bar-->
            <?php include ("includes/message.php")?>
            <?php include ("../includes/errors.php")?>
            <?php foreach($message as $messages): ?>
                <div class="notify-info">
                          <img src="../CSS/PIC/smallprofile.jpg.png" class="profile_pic" alt="icon">
                       <div class="notify-details">
                          <span class="comment-name"><?php echo getUsernameById($messages['user_id']);?></span><br>
                          <span class="comment-date"><?php echo $messages['created_at']?></span>
                          <p> <?php echo $messages['body']?> </p>
                          <p style="color:black">contact me @ <a href="mailto:<?php echo $messages['email'] ?>"><?php echo $messages['email'] ?></a></p>
                          <?php if(($_SESSION['user']['id']) == $messages['user_id']):?>
                          <span>&nbsp&nbsp<a class="fa fa-pencil btn edit" href="message.php?edit-message=<?php echo $messages['id'] ?>"></a>  &nbsp &nbsp
                          <a class="fa fa-trash" href="Notifications.php?delete-message=<?php echo $messages['id']?>"></a></span>
                          <?php endif ?>  
                       </div> 
                </div>
                <?php endforeach ?>
            </div>    
        </div>  
          <!--Only Admin user shoulde be able to manage topics and user-->
          <?php if($_SESSION['user']['role'] == "Admin"):?>
               <!--include the side nav section-->
              <?php include ("includes/navbar.php")?>  
            <?php endif ?> 
     </div>
        <?php include ("includes/footer.php")?>
</body>
</html>