<footer>
<?php if(isset($_SESSION['user'])): ?>
     <h3 class="sub sub">subscribe to have free access to our newsletter</h3>
     <div class="form-form">
          <form action="<?php $_SERVER['PHP_SELF']; ?>" method="Post" onsubmit="return Validate()";>   
            <div class="form-cont">
               <input type="email" name="email" id="email"  placeholder="Enter your email Address">
               <div id="email_error" class="error"></div>
            </div>
            <div class="form-cont">
                <button type="submit" name="submit" class="btn">Subscribe</button>
            </div>
          </form> 
     </div>

<?php endif ?>  

   <p>You can follow us on or social-media pages<br>
         <a target="_blank" href=""><img class="share-icon" src="./CSS/PIC/icon01.png" alt="icon"></a>
         <a target="_blank" href="#"><img class="share-icon" src="./CSS/PIC/icon03.png" alt="icon"></a>
         <a target="_blank" href="http://www.twitter.com/share?text=Visit the link & url=<?php echo $baseUrl.$slug ?>"><img class="share-icon" src="./CSS/PIC/icon05.png" alt="icon"></a>
         <a target="_blank" href="http://www.facebook.com/share.php?u = <?php echo baseUrl.$slug;?>"><img class="share-icon" src="./CSS/PIC/icon04.png" alt="icon"></a>
         <p><a style="color:#fff;" href="#top">back to top</a>|
         <?php if(isset($_SESSION['user'])):?>
            <a class="user-link" href="logout.php">logout</a>
         <?php else: ?>
            <a class="user-link" href="login.php">Sign in</a></p>
         <?php endif ?>   
    </p>
    <p>All content is &copy BloomBlu <?php echo date("Y");?></p>   
</footer>

<script>
    function Validate(){
        var $valid = true;
        document.getElementById("email_error").innerHTML = "";

        var email = document.getElementById("email").value;

        if(email == ""){
            document.getElementById("email_error").innerHTML = "Email is required!";
            $valid = false;
        }
        return $valid;
    }
</script>