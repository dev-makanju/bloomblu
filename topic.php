<!--include the config file for database connection-->
<?php include ("config/config.php");?>
<!--include the head-section-->
<?php include ("includes/head-file.php")?>
<?php include ("includes/public_function.php");?>
<?php $topic = getAllTopic(); ?>
<meta name="description" content="view all our post under your desirable topics">
   <title>Bloomblue | topics</title>
   <link rel="stylesheet" href="CSS/main.css">
   <link rel="stylesheet" href="CSS/public_style.css">
   <link rel="stylesheet" href="CSS/resp_tab.css">
</head>
<body>
<div class="container">  
      <!--include the head navigation section -->
      <?php include ("includes/header.php");?>
        <div class="topic-wrapper">
            <h2>Topics</h2>
                <div class="topic-wrapper-tab">
                <?php foreach($topic as $topics):?>
                    <div class="topic-item">
                       <a href="filtered_post.php?topic=<?php echo $topics['id'];?>">
                           <p><?php echo returnTotalPostByTopicId($topics['id']);?></p>    
                           <?php echo $topics['name'];?>
                       </a>
                    </div>
                <?php endforeach ?>
                </div>
        </div>
      <?php include ("includes/footer.php");?>
</div>      
</body>
</html>
