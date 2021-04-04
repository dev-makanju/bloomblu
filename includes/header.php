<?php include ("search_public_data.php")?>
<div class="big-wrapper" style="position:relative">
   <div class="head-section">
         <div class="head" >
              <h1>BloomBlu</h1>
              <div class="res-link-wrapper" id="res-tab">
                 <a href="index.php">Home</a>
                <?php if(isset($_SESSION['user'])):?>
                  <?php if(in_array($_SESSION['user']['role'] , ['Admin','Author'])):?>
                    <a href="Admin/dashboard.php">Admin</a>
                    <a href="news.php">News</a> 
                   <?php else: ?>
                    <a href="news.php">News</a>
                   <?php endif ?>
                <?php else: ?>
                    <a href="news.php">News</a>
                <?php endif ?> 
                 <a href="contact.php">Contact</a>
                 <a href="About.php">About</a>
              </div><!--//nav-link-wrapper-->
              <a  href="javascript:void(0);" class="ham-icon" onclick="myFunction();"><i class="fa fa-bars fa-2x" style="color:#000;font-size:35px;"></i></a>
         </div><!--//head--> 

         <div class="nav-link-wrapper">
              <span><a href="index.php">Home</a></span>
            <?php if(isset($_SESSION['user'])):?>
              <?php if(in_array($_SESSION['user']['role'] , ['Admin','Author'])):?>
                <span><a href="Admin/dashboard.php">Admin</a></span>
                <span><a href="news.php">News</a></span> 
              <?php else: ?>
                <span><a href="news.php">News</a></span>  
              <?php endif ?>  
            <?php else: ?>  
              <span><a href="news.php">News</a></span>  
            <?php endif?>
            <span><a href="contact.php">Contact</a></span>
            <span><a href="About.php">About</a></span>
         </div><!--//nav-link-wrapper-->  
    </div>     
</div>
<?php if(isset($_SESSION['user']['username'])):?>
  <div class="logged_user_info" style="display: flex; flex-direction:column;">
      <div class="pub_log_wrapper" >  
         <div class="pub_logss_wrapper">
            <span class="user_name"><p>Hello &nbsp&nbsp<?php echo $_SESSION['user']['username'];?></p></span>
         </div>
         <div class="pub_logss_wrapper">
            <span class="log-out"><a href="logout.php">Logout</a></span>
         </div>
       </div>
         <div class="pub_log_wrapper"> 
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
           </div>  
   </div>
<?php endif ?>

<!--implementing the toggle navbar effect-->
<script>
    $(document).ready(function(){
            //this will get the var url at the address
            var url = window.location.href;
            //pass on every "a" tag
            $(".nav-link-wrapper a").each(function(){
                //check if its is the same on all address
                if(url == (this.href)){
                    //add class
                    $(this).closest("span").addClass(" active");
                }
            });
       });

    function myFunction(){
        var x = document.getElementById("res-tab");
        if(x.className === "res-link-wrapper"){
            x.className += " tab";
        }else{
          x.className = "res-link-wrapper";  
        }
    }

    //search data input

    function fill(Value){
        $("#search").val(Value);
        $("#display").hide();
    }

    $(document).ready(function(){
        $("#search").keyup(function(){
            var input = $("#search").val();
            if(input == ""){
                //assign empty var to it
                $('#display').html("");
            }
            else{
                //ajax is called
                $.ajax({
                    type: "post",
                    url: "includes/search_public_data.php",
                    data:{
                        //display div in search.php
                        search:input
                    },
                success: function(html){
                    //display div in search.php
                    $('#display').html(html).show();
                }
               });
            }
         });
    });

</script>