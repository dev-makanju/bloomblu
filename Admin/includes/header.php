<?php include ("search-data.php")?>
<?php $user_infos = getAllUserInfo(); ?>
<div class="header">
<?php if($_SESSION['user']['role'] == "Admin"):?>
    <h1><span class="global_head">Welcome</span> Admin</h1>
<?php else: ?>
    <h1><span class="global_head">Welcome</span> Author</h1>
<?php endif ?>    
    <div class="header-user-info">
         <span>
             <?php foreach($user_infos as $user_info):?>
             <?php if(empty($user_info['image'])):?>   
             <a href="profile.php"><img style="background:#fff;border-radius:30%;width
             30px;float:left;height:35px; margin-right: 5px;" src="../CSS/PIC/smallprofile.jpg.png"></a>
             <?php else:?>
             <a href="profile.php"><img style="background:#fff;border-radius:30%;width
             30px;float:left;height:35px; margin-right: 5px;" src="../CSS/MEDIA/<?php echo $user_info['image'];?>"></a>
             <?php endif ?>   
             <?php endforeach ?>
             <?php echo $_SESSION['user']['username'] ;?><a href="../logout.php"> &nbsplogout</a>
        </span>
    </div>
</div>
<div class="nav">
    <ul>
        <li><i style="color:black;" class="fa fa-dashboard"></i><a href="dashboard.php">Dashboard</a></li>
        <li><i style="color:black;" class="fa fa-home"></i><a href="<?php echo $config_basedir ?>index.php">Home</a></li>
        <!--Only display when is Aurtor -->
        <?php if($_SESSION['user']['role'] == "Author"):?>
        <li><i style="color:black;" class="fa fa-text-editor"></i><a href="create-news.php">Create News</a></li>
        <li><i style="color:black;" class="fa fa-text-editor"></i><a href="create_post.php">Create post</a></li>
        <li><i style="color:black;" class="fas fa-pencil"></i><a href="posts.php">Manage Post</a></li>
        <?php endif ?>
        <?php if(($_SESSION['user']['role'] == "Admin")):?>
           <li><i style="color:black;" class="fa fa-user-circle-o"></i><a href="profile.php">Profile</a></li>
        <?php endif ?>
           <li><?php if(!empty(getNotificationCount())):?>
             <i style="color:black;" class="fa fa-bell"></i>
             <?php else: ?>
             <i style="color:black;" class="fa fa-bell-slash"></i>
             <?php endif ?>   
             <span <?php if(getNotificationCount() == 0):?> 
                   class="bell" <?php else: ?> 
                   class="notify-bell" <?php endif ?> >
               <?php echo getNotificationCount(); ?> 
             </span>
                <a href="Notifications.php">Notification
            <span style="color:red;font-weight:bold;">
           </span></a>
        </li>
    </ul>
     <div class="ram">
          <p>~RamRam</p>
     </div>
</div>
<div class="big-wrapper">
<div id="responsive-tab-admin">
       <div class="head" >
            <h3>~RamRam</h3>
              <div class="res-link-wrapper" id="res-tab">
                 <a href="<?php echo $config_basedir ?>index.php">Home</a>
                 <a href="dashboard.php">Dashboard</a>
                 <a href="create_post.php">Create Post</a>
                 <a href="create-news.php">Create News</a>
                 <a href="<?php $config_basedir ?>posts.php">Manage post 
                 <span <?php if( getUnplishedArticles() == 0):?> style="display:none;" <?php else: ?> style="color:red" <?php endif ?> > 
                      <?php echo getUnplishedArticles()?>
                 </span></a>
                 <?php if($_SESSION['user']['role'] == "Admin"):?>
                 <a href="<?php $config_basedir ?>Manage-news.php">Manage News 
                 <span <?php if( getUnplishedNews() == 0):?> style="display:none;" <?php else: ?> style="color:red" <?php endif ?>style="color:red"> <?php echo getUnplishedNews(); ?>
                </span><?php  ?></a>
                <?php endif ?>
                 <a href="topics.php">Manage Topics</a>
                 <?php if($_SESSION['user']['role'] == "Admin"):?>
                 <a href="<?php $config_basedir ?>user.php">Manage User</a>
                 <a href="ads.php">Manage Ads</a>
                 <?php endif ?>
                 <a href="About.php">Change password</a>
                 <?php if($_SESSION['user']['role'] == "Admin"):?>
                 <a href="profile.php">Profile</a>
                 <?php endif ?>
                 <a href="Notifications.php">Notifications</a>
              </div>
            <a  href="javascript:void(0);" class="ham-icon" onclick="myFunction()"><img style="width:30px;height:30px;"src="../CSS/PIC/hanmburger.png" alt=""></a>
         </div><!--//head-->
</div> <!--//responsive-tab-admin-->         
</div><!--//big wrapper-->
<script>
        $(document).ready(function(){
            //this will get the var url at the address
            var url = window.location.href;
            //pass on every "a" tag
            $(".nav a").each(function(){
                //check if its is the same on all address
                if(url == (this.href)){
                    //add class
                    $(this).closest("li").addClass(" active");
                }
            });
       });
</script>
<script>
    function myFunction(){
        var x = document.getElementById("res-tab");
        if(x.className === "res-link-wrapper"){
            x.className += " tab";
        }else{
          x.className = "res-link-wrapper";  
        }
    }
</script>