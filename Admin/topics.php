<!--includes the database connection file-->
<?php include("../config/config.php"); ?>
<!--include the function file-->
<?php include("includes/Admin_function.php");?>
<!--display all topic from database-->
<?php

 if(isset($_GET['pageno'])){
    $pageno = $_GET['pageno'];
    $topic = getAllTopics($pageno); 
    extract($topic);
 }else{
    $pageno = 1;
    $topic = getAllTopics($pageno); 
    extract($topic);
 }

?> 
<!--includes the head file-->
<?php include("includes/head-file.php");?>
    <title>Bloomblue | topics</title>
    <link rel="stylesheet" href="../CSS/Admin_style.css">
</head>
<body><!--redirect to basedirectory if user is not an Admin or Author-->
      <?php if(isset($_SESSION['user'])): ?>
          <!--check if user is not Admin or Author-->
         <?php if(in_array($_SESSION['user']['role'] , ['Admin','Author'])):?>
               <!--do nothing-->
         <?php else: ?>
              <?php header("location:" .$config_basedir."error.php");?>
        <?php endif ?> 
      <?php else: ?>
            <?php header("location:".$config_basedir."index.php"); ?>
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
            <h1>Create Topic</h1>
        </div>   
        <form class="Admin_user form" action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
            <?php if ($isEditingTopic === true) :?>
                <input type="hidden" name="topic_id" id="" value="<?php echo $topic_id  ;?>">
            <?php endif ?>
               <div class="user_form_control">
                   <input type="" name="topic" id="topic" placeholder="Topic" value="<?php echo $topic_name ;?>">
               </div>  
            <?php if($isEditingTopic === true):?>
               <button type="submit" name="update_topic">UPDATE TOPIC</button>
           <?php else: ?>
               <button type="submit" name="create_topic">SAVE TOPIC</button>
           <?php endif ?>    
          </form> 
        </div>  
        <table class="table" style="width:70%">
            <thead>
                <th>S/N</th>
                <th>Topic</th>
                <th colspan="2">Action</th>
            </thead>
            <?php foreach($topic as $key => $topics):?>
            <tbody>
                <tr>
                    <td><?php echo $key + 1 ;?></td>
                    <td><?php echo $topics['name']; ?></td>
                    <td><a class="fa fa-pencil btn edit" href="<?php echo $config_basedir ?>Admin/topics.php?edit-topic=<?php echo hashUrlData($topics['id']); ?>"></a></td>
                    <td><a class="fa fa-trash" href="<?php echo $config_basedir ?>Admin/topics.php?delete-topic=<?php echo hashUrlData($topics['id']); ?>"></a></td>
                </tr>
            </tbody>
            <?php endforeach ?>
        </table>

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
   </div> 
    <?php include ("includes/footer.php")?>
</body>
</html>