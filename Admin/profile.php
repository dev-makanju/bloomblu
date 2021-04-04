<!--includes the head file-->
<?php include ("includes/head-file.php");?>
<!--includes the database connection file-->
<?php include ("../config/config.php"); ?>
<?php include ("includes/Admin_function.php")?>
<!--include the functionality-->
<?php include ("includes/post_function.php")?>
    <title>Bloomblu | Profile</title>
    <link rel="stylesheet" href="../CSS/Admin_style.css">
    <script src="../JS/script.js"></script>
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
             <!--search-implemetation-->
             <?php include ("search.php") ;?>
              <div class="header">   
                   <h1>Profile page</h1>
              </div>
              <?php if($editUser === true):?>
               <!--When user click on edit profile-->
               <div class="profile_tab">
                  <div class="pro" style="position:relative">
                   <span> 
                    <div class="pic" onClick="triggerClick()">
                        <h4>Update picture</h4>
                    </div>
                    <?php foreach($user_infos as $user_info):?>
                        <?php if(empty($user_info['image'])):?>    
                           <img class="real_pic" src="../CSS/PIC/profile.png" onClick="triggerClick()" id="profileDisplay">
                        <?php else:?>
                           <img class="real_pic" src="../CSS/MEDIA/<?php echo $user_info['image'];?>" onClick="triggerClick()" id="profileDisplay">
                        <?php endif ?>     
                    <?php endforeach ?> 
                   </span> 
                  </div> 
                   <div class="profile-details">
                        <form action="<?php $_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data">
                            <input type="file" name="profileImage" onChange="displayImage(this)" id="profileImage" class="profile_file" style="display:none">
                            <label>Bio</label>
                            <textarea name="user_bio" id="" cols="30" rows="10"><?php echo $bio ;?></textarea>
                            <label>State</label>
                            <input type="" name="User_state" value="<?php echo $state ;?>" placeholder="Your state">
                            <label>Nationality</label>
                            <input type="text" name="User_nationality"  value="<?php echo $nationality ;?>" placeholder="Your country">
                            <label>Current city</label>
                            <input type="text" name="user_city" value="<?php echo $location ;?>"  placeholder="where do you currently live"> 
                            <button class="update_link" type="submit" name="update_profile">Update Profile</button> 
                        </form>
                   </div>
              </div>
              <?php else: ?>
                <?php foreach($user_infos as $user_info):?>
              <div class="profile_tab">
                   <div class="profile_image">
                       <?php if(empty($user_info['image'])):?>
                          <img class="real_profile_pic" src="../CSS/PIC/profile.png" alt="">
                       <?php else: ?>  
                          <img class="real_profile_pic" src="../CSS/MEDIA/<?php echo $user_info['image'];?>" alt="">
                       <?php endif ?>
                   </div>
                   <div class="profile-details">
                           <label>Bio</label>
                           <?php if(empty($user_info['bio'])):?>
                              <p style="color:grey">hey you haven't update your bio.</p>
                           <?php else:?> 
                              <p><?php echo $user_info['bio'] ;?></p>
                           <?php endif ?>  
                           <label>State</label>
                           <?php if(empty($user_info['state'])):?>
                              <p style="color:grey">what is the name of your city?</p>
                           <?php else:?> 
                           <p><?php echo $user_info['state'] ;?></p>
                           <?php endif ?>
                           <label>Nationality</label>
                           <?php if(empty($user_info['nationality'])):?>
                              <p style="color:grey">You are a citizen of which country? update your nationality.</p>
                           <?php else:?> 
                           <p><?php echo $user_info['nationality'] ;?></p>
                           <?php endif ?>
                           <label>Current city</label>
                           <?php if(empty($user_info['location'])):?>
                              <p style="color:grey"> your current location</p>
                           <?php else:?> 
                           <p><?php echo $user_info['location'] ;?></p>
                           <?php endif ?>
                           <h5>make your profile public:
                             <?php if($user_info['public'] == true): ?>
                                <td>
                                    <a class="fa fa-check btn publish" href="<?php echo $config_basedir ?>Admin/profile.php?unpublic=<?php echo hashUrlData($user_info['id']); ?>"></a>
                                 </td>
                             <?php else: ?>
                                <td>
                                    <a class="fa fa-times btn unpublished" href="<?php echo $config_basedir; ?>Admin/profile.php?public=<?php echo hashUrlData($user_info['id']); ?>"></a>
                                </td>
                           </h5>     
                             <?php endif ?> 
                   </div>
                   <div class="update_link">
                       <a class="prof_button" href="profile.php?edit-profile=<?php echo hashUrlData($user_info['id']); ?>"><button>Edit Profile</button></a>
                   </div>
              </div>
                <?php endforeach ?>   
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