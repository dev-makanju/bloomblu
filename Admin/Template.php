<!--includes the database connection file-->
<?php include("../config/config.php"); ?>
<?php include("includes/post_function.php");?>
<?php include ("includes/Admin_function.php")?>

<?php $emails = getAllNewsletterSendPost();?>
<!--includes the head file-->
<?php include("includes/head-file.php");?>
    <title>Bloomblue | Template</title>
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
                  <?php include ("includes/message.php")?>
                       <div class="Newsletter_header">
                           <a href="newsletter.php"><?php echo getCountSubscribers();?> Subscriber(s)</a>
                           <a href="Template.php"><?php echo getCountTemplates();?> Template(s)</a>
                      </div>
                    <h1 class="tem_h1">click on the email Template to send </h1>
                    <div style="" class="Email_template">
                      <!--check if post there is an unsend published post-->
                    <?php if($emails):?>  
                      <?php foreach ($emails as $email):?>
                            <div class="HTML_template">
                                <h2>Alert!!! new blogpost</h2>
                                <img src="../CSS/PIC/<?php echo $email['images'] ;?>" alt="pic">
                                <h4>Title: <?php echo $email['title'];?></h4>
                                <p><?php echo getSubstractedPostById($email['id'])?></p>
                                <p style="display:inline;justify-content"><a class="" href="newsletter.php">Send mail</a>
                                <a href=""><img style="width:25px;height:25px;" src="../CSS/PIC/icon01.png" alt="pic"></a> 
                                <a href=""><img style="width:25px;height:25px;" src="../CSS/PIC/icon03.png" alt="pic"></a>
                                <a href=""><img style="width:25px;height:25px;" src="../CSS/PIC/icon04.png" alt="pic"></a> 
                                <a href=""><img style="width:25px;height:25px;" src="../CSS/PIC/icon05.png" alt="pic"></a></p>  
                            </div>
                        <?php endforeach ?>
                      <?php else: ?>
                           <h4>Template unavailable!</h4>
                      <?php endif ?>   
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