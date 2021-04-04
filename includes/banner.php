<?php
    if(!isset($_SESSION['user'])){
?>
<div class="banner_overlay">
  <div id="banner">
       <!---//backgroung image here-->
  </div><!--//banner -->
  <div class="overlay">
      <div class="overlay_wrapper">

        <div class="public_search">
            <div class="pub_search_wrapper">
             <form action="search_res.php" method="post" style="position:relative;padding:0px;">
               <div class="admin-news-header">
                  <input class="inp" type="text" id="search" name="search" placeholder="search a post">
                  <button type="submit" class="btn-submit" name="search-submit" ><i class="fa fa-search" style="color:#000"></i></button>
                  <div style="position:relative;">
                      <div id="display" style="text-align:left;"></div>
                  </div>
               </div>
             </form>
            </div><!--//public_search_wrapper-->
           <div class="o_wrapper">
               <div class="sub-tab-form">
                 <p id="text"></p>
                 <li style="list-style-type: none;"><span></span><div><h5 style="color:lightblue;">Real Estate</h5></div></li>
               </div><!--sub-tab-form-->
               <div class="sub-tab-form">
                <div class="subscribe_box">
                   <p>sign up for our daily newsletter</p>
                    <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" id="form">   
                      <div class="form-control-tab">
                        <input type="" name="email" id="email"  placeholder="Enter your email Address">
                      </div>
                      <input type="submit" id="subscribe" class="btn" value="Subscribe">
                    </form> 
                  <div id="subscribe_message"></div>
                </div> <!--subscribe bus-->
               </div><!--sub-tab-form2-->
           </div><!--o_wrapper-->   
        </div><!--public_search-->
      </div><!--overlay_wrapper-->
  </div><!--overlay-->

</div><!--banner overlay-->

<?php
}
?>
<div id="content">
        <a href="topic.php">
          <span style="color:#000;font-size:60px;" class="material-icons">topic</span><br>
          Topics</a>
        <a href="Register.php">
        <span style="color:#000;font-size:60px;" class="material-icons">app_registration</span><br>
          Sign up</a>
        <a href="Login.php">
        <span style="color:#000;font-size:60px;" class="material-icons">login</span><br>
          Sign in</a>
</div>

<script>
      var name = "One day we all goona have a story to tell. \n Maybe good or bad, \n it's all in our hands. \n\n~RamRam";
      var i = 0;

      function typeName(){     
        if(i < name.length){  
            var nameHeader = document.getElementById("text");
            nameHeader.innerHTML = nameHeader.innerHTML + name.charAt(i);
            i = i + 1;
            setTimeout(typeName , 50);
        }
      }
      window.onload= typeName;
</script>