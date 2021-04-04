<!--includes the head file-->
<?php include ("includes/head-file.php");?>
<!--includes the database connection file-->
<?php include ("../config/config.php"); ?>
<?php include ("includes/Admin_function.php")?>
    <title>Bloomblu | message</title>
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
           <!--message forms and designs--> 
        <div id="container">  
               <div class="Admin-user-tab">
                   <!--search-->
                   <?php include ("search.php") ;?>
                   <div class="notify-tab">
                       <div class="header">
                           <h1>Send a message</h1>
                       </div>
                       <?php include ("../includes/errors.php")?>
                        <div class="notify-info">
                            <form action="<?php $_SERVER['SCRIPT_NAME'];?>" method="post">
                                <textarea name="message" id="" cols="30" rows="10"><?php echo $message ?></textarea>
                                <?php if($isEditingmessage === true ):?>
                                  <button type="submit" name="sendUpdate">Update</button>
                                <?php else:?>
                                  <button type="submit" name="sendMessage">Send</button>
                                <?php endif?>             
                            </form>    
                        </div> 
                   </div>
               </div>     
            <!--Only Admin user should be able to manage topics and user-->
            <?php if($_SESSION['user']['role'] == "Admin"):?>
               <!--include the side nav section-->
              <?php include ("includes/navbar.php")?>  
            <?php endif ?> 
      </div>
        <?php include ("includes/footer.php")?>
</body>
</html>