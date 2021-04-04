<?php include ("includes/post_function.php");?>
<?php include ("includes/Admin_function.php")?>
<?php include ("includes/user-tracker.php")?>
<!--includes the head file-->
<?php include("includes/head-file.php");?>
<!--includes the database connection file-->
<?php include("../config/config.php"); ?>
<?php ///invoke the function 
   echo current_Month();
   echo showChart();
?>
    <title>Bloomblue | dashboard</title>
    <link rel="stylesheet" href="../CSS/Admin_style.css">
    <script src="../CSS/JS/jquery-3.2.1.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/rapheal/2.3.0/rapheal.min.js"></script>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
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
                  <!--input the serch link-->
                 <?php include ("search.php");?>
                 <?php include ("includes/message.php")?>
                  <div class="dash_tab">
                     <?php if($_SESSION['user']['role'] == "Admin"):?> 
                     <a class="tab_tab_dashboard" href="user.php">
                     <span style="color:#000;font-size:60px;" class="material-icons">app_registration</span>
                          <?php echo newlyRegisterdToday(); ?>Registered Users Today
                     </a>
                     <?php else: ?>
                        <a class="tab_tab_dashboard" href="Manage-news.php">
                            Manage news
                         </a>
                     <?php endif ?>   
                     <a class="tab_tab_dashboard" href="<?php echo $config_basedir . "Admin"; ?>/posts.php">
                         <span style="color:#000;font-size:60px;" class="material-icons">publish</span>
                         <?php echo getPublishedPostCount();?> Published post
                     </a>
                     <?php if($_SESSION['user']['role'] == "Admin"):?>
                     <a class="tab_tab_dashboard" href="newsletter.php"> 
                         <span style="color:#000;font-size:60px;" class="material-icons">mail</span>
                         Newsletter</a>
                     <?php else: ?>
                        <a class="tab_tab_dashboard" href="profile.php">Profile</a>
                     <?php endif ?>
                  </div>   
                  <!--website stat -->
                  <!--show web stat to only Admin user-->
                  <?php if($_SESSION['user']['role'] == "Admin"):?>
                  <marquee behavior="scroll" direction="left" scrollamount="3" onmouseover="stop();" onmouseout="start();"  onmousemove="stop();" >
                   <p><span style="color:#000;font-size:18px;font-weight:bold;">
                    <span style="color:red"><?php echo getTotalVisitTodayCount();?></span> guest and <span style="color:red"><?php echo getRegUserTodayCount();?></span> registered users visited this website today ,this website statistics gives you total understanding of the website daily activities</span>
                    </p>
                   </marquee> 
                   <!--miain statistic div-->
                  <div class="statbar">
                        <div class="show-graph">
                            <div class="chart" id="chart">
                              <!--show chart content-->
                            </div>
                        </div>
                        <!--wrap innerContenet in two seperate div-->
                        <div class="activity-bar">
                              <li class="red"><?php echo getAllRegUserCount();?> Member</li>
                              <li class="orange"><?php echo getActiveRegUserCount();?> Active Members</li>
                              <li class="green"><?php echo getAllArtCount();?> articles</li>
                              <li class="blue"><?php echo getAllNewsCount();?>news post</li>
                              <li class="white"><?php echo getCountAllOnlineUser();?>Online users</li>
                              <li class="yellow"><?php echo getCountAllAdmin();?>Admin users</li>
                              <li class="deep-blue"><?php echo getCountAllAlthur();?>Authurs</li>
                        </div>
                  </div>
                  <?php endif  ?>
                  
                     <h2>~Available Ads</h2>
                     <?php include ("includes/Ads-gallery.php")?>    
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
<script>
    Morris.Bar({
        element : 'chart',
        data: [<?php echo $chart_data; ?>],
        xkey: 'year',
        ykeys: ['monthly_visitors'],
        label: ['monthly_visitors'],
        hiddenHover: 'auto',
    });
</script>