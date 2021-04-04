<?php include("config/config.php")?>
<!--include function file for registration-->
<?php include("includes/registration-login.php");?>
<?php include("includes/head-file.php")?>
<meta name="description" content="Sign in to bloomblue to have access to our full blog activities">
    <title>Sign in</title>
    <script src="JS/script.js"></script>
    <link rel="stylesheet" href="CSS/main.css">
    <link rel="stylesheet" href="CSS/public_style.css">
    <link rel="stylesheet" href="CSS/resp_tab.css">
</head>
<body>
<div class="container">     
<!--head navigation ection-->
<?php include ("includes/header.php");?>
<!--include the error file code for form login-->
<?php include ("includes/errors.php")?>
<!--include succes msg-->
<?php include ("includes/message.php")?>
     <?php if($loginUser ===  true ):?>
     <!--when user click on forgrt password-->
     <div class="forms">
        <div class="form-header">
             <h2>Forgot password?</h2>
        </div>
         <form action="<?php $_SERVER['PHP_SELF'] ;?>" method="POST" onsubmit="return verify()">
              <div class="form-control" id="username_div">
                   <input type="email" name="email" id="email" placeholder="Enter u r Email">
                   <div class="icon"><img src="CSS/PIC/top-note.png" alt="icon"></div>
                   <div id="email_error" class="error"></div>
              </div>
              <button type="submit" class="btn" name="forgot_pass" id="submit">Submit</button>
         </form>
        <p>Aready a member?<a href="login.php">login here</a><br>
        <p>Need an account?<a href="register.php">Reister here</a></p>
     </div>
     <?php else:?>
     <div class="forms">
        <div class="form-header">
             <h2>Login</h2>
        </div>
         <form action="<?php $_SERVER['PHP_SELF'] ;?>" method="POST" onsubmit="return validate()">
              <div class="form-control" id="username_div">
                   <input type="text" name="username" id="username" value="<?php echo $username;?>" placeholder="Enter u r Username">
                   <div class="icon"><img src="CSS/PIC/top-note.png" alt="icon"></div>
                   <div id="user_info" class="error"></div>
              </div>
              <div class="form-control" id="password_div">
                   <input type="password" name="password" id="password" placeholder="Enter u r Password">
                   <div  class="icon"><img src="CSS/PIC/top-key.png" alt="icon"></div>
                   <div id="pass_error" class="error"></div>
              </div>
              <button type="submit" class="btn" name="log_in" id="submit">Sign in</button>
         </form>
         <?php if(isset($_SESSION['user'])):?>
             <p>Change password<a href="register.php">click here</a></p>
         <?php else:?> 
            <p>Need an account?<a href="register.php">Register here</a></p>
        <?php endif ?>
            <p>forgot password?<a href="login.php?forgotPass=#">click here</a></p>
     </div>
     
     <?php endif ?>  
<?php include("includes/footer.php");?>
</div>

</body>
</html>
<script>
     function verify(){
            var $valid = true;
            document.getElementById("email_error").innerHTML = "";

            var email = document.getElementById("email").value;

            if(email === ""){
                document.getElementById("email_error").innerHTML = "email is required!";
                $valid = false;
            }
            return $valid
     }// end function
</script>
<script>
     function validate(){
            var $valid = true;
            document.getElementById("user_info").innerHTML = "";
            document.getElementById("pass_error").innerHTML = "";

            var userName = document.getElementById("username").value;
            var password = document.getElementById("password").value;

            if(userName == ""){
                document.getElementById("user_info").innerHTML = "username is required!";
                $valid = false;
            }
            if(password == ""){
                document.getElementById("pass_error").innerHTML = "password is required!";
                $valid = false;
            }
            return $valid
     }// end function
</script>


