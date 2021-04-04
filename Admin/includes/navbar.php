<div class="side-navbar">
      <ul>
           <li><i style="font-size:1.2em;color:black; padding-right: 10px;" class="fa fa-plus-square"></i><a href="<?php $config_basedir ?>create_post.php">Create post</a></li>
           <li><i style="font-size:1.2em;color:black; padding-right: 10px;" class="fa fa-pencil-square-o"></i><a href="<?php $config_basedir ?>create-news.php">Create news</a></li>
           <li><i  style="font-size:1.2em;color:black; padding-right: 10px;" class="fa fa-user-plus"></i><a href="<?php $config_basedir ?>user.php">Manage Users</a></li>   
           <li><i style="font-size:1.2em;color:black; padding-right: 10px;" class="fa fa-book"></i><a href="<?php $config_basedir ?>posts.php">Manage Posts
           <span <?php if(getUnplishedArticles() == 0): ?> style="display:none" <?php else: ?> style="color:red"<?php endif ?>>
               <?php echo getUnplishedArticles(); ?>
            </span>
            </a></li>
             <li><i style="font-size:1.2em;color:black; padding-right: 10px;" class="fa fa-pencil-square"></i><a href="<?php $config_basedir ?>Manage-news.php">Manage News
                <span <?php if(getUnplishedNews() == 0): ?> style="display:none" <?php else: ?> style="color:red"<?php endif ?> > 
               <?php  echo getUnplishedNews(); ?>
            </span>
            </a></li>      
           <li><i style="font-size:1.2em;color:black; padding-right: 10px;" class="fa fa-pencil"></i><a href="<?php $config_basedir ?>topics.php">Manage Topics</a></li>
           <li><i style="font-size:1.2em;color:black; padding-right: 10px;" class="fa fa-feed"></i><a href="<?php $config_basedir ?>message.php">Message</a></li>
           <li><i  style="font-size:1.2em;color:black; padding-right: 10px;" class="fa fa-adn"></i><a href="ads.php">Manage Ads</a></li>            
           <li><i  style="font-size:1.2em;color:black;padding-right: 10px;" class="fa fa-unlock-alt"></i><a href="../register.php">Change Password</a></li>
      </ul> 
    <div class="logo-wrapper">
          <img src="../CSS/PIC/admin-icon.png" alt="">
         <p>Power Admin</p>
    </div>    
</div>
<script>
        $(document).ready(function(){
            //this will get the var url at the address
            var url = window.location.href;
            //pass on every "a" tag
            $(".side-navbar a").each(function(){
                //check if its is the same on all address
                if(url == (this.href)){
                    //add class
                    $(this).closest("li").addClass(" active");
                }
            });
       });
</script>