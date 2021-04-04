<!--includes the database connection file-->
<?php include("../config/config.php"); ?>
<?php include("includes/post_function.php");?>
<?php include ("includes/Admin_function.php")?>
<?php $subcriber = getSubscriber(); ?>
<!--includes the head file-->
<?php include("includes/head-file.php");?>
    <title>Bloomblue | newsletter</title>
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
                   <?php include ("includes/message.php")?>
                       <div class="Newsletter_header">
                           <a href="newsletter.php"><?php echo getCountSubscribers();?> Subscriber(s)</a>
                           <a href="Template.php"><?php echo getCountTemplates();?> Template(s)</a>
                      </div>
             <?php if($subcriber):?>     
                <?php foreach($subcriber as $subscribers): ?>
                      <div class="subcriber">
                               <p style="color:black">email: <?php echo $subscribers['email'];?></p>
                               <p style="color:black">IP:  <?php echo $subscribers['ip'];?></p>
                               <p style="color:black"><a style="color:black" href="newsletter?sub_id=<?php echo $subscribers['id'];?>">Delete-subscriber</a> |  send Mail<input type="checkbox" value="checked"></p>
                      </div>
                <?php endforeach ?>
                <?php else: ?>
                    <h3>Oops...No subcribers yet!</h3>
                <?php endif ?>        
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