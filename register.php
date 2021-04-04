<!--include the config file to ascess the database-->
<?php include ("config/config.php")?>
<?php include ("includes/registration-login.php");?>
<!--inckude the head secion-->
<?php include("includes/head-file.php")?>
<meta name="description" content="Register with bloomblue to have acess to our full blog activities">
    <title>Register</title>
    <link rel="stylesheet" href="CSS/main.css">
    <link rel="stylesheet" href="CSS/public_style.css">
    <link rel="stylesheet" href="CSS/resp_tab.css">
</head>
<body>
<div class="container">
<!--include the header section of the page-->
<?php include ("includes/header.php");?>
<!--include the error file code for form login-->
<?php include ("includes/errors.php")?>
<!--include success msg-->
<?php include ("includes/message.php")?>
    <!--forom for password change-->
    <!--if user is logged in -->
    <?php if(isset($_SESSION['user'])):?>
    <div class="forms">
        <div class="form-header">
          <h2>Change Password</h2>
        </div>
         <form action="<?php $_SERVER['PHP_SELF'] ;?>" method="POST" onsubmit="return verify()">
              <div class="form-control" id="password_div">
                   <input type="text" name="oldPassword" id="oldPassword" placeholder="Enter ur old password">
                   <div id="oldPass" class="error"></div>
              </div>
              <div class="form-control" id="password_div">
                   <input type="password" name="newPassword" id="newPassword" placeholder="Enter ur new Password">
                   <div id="newPass" class="error"></div>
              </div>
              <div class="form-control" id="password_div">
                   <input type="password" name="conf_Password" id="conf_Password" placeholder="Confirm u r Password">
                   <div id="confPass" class="error"></div>
              </div>
              <button type="submit" class="btn" name="Change_pass" id="submit">Sign in</button>
         </form>
    </div>
    <!--if user is not set always display register-->
    <?php else: ?>
<!-- Mother wrapper for the form register-->
    <div class="forms">
        <div class="form-header">
             <h2>Register</h2>
        </div>
        <form action="<?php $_SERVER['SCRIPT_NAME']; ?>" method="POST" onsubmit="return validate()">
             <div class="form-control">
                 <label>Username</label>
                 <input type = "text" name="username" id="username" value="<?php echo $username;?>" placeholder="Enter u r username">
                 <div id="user_info" class="error"></div>
             </div>
             <div class="form-control">
                 <label>Email</label>
                 <input type = "email" name="email" id="email" value="<?php echo $email;?>" placeholder="Enter u r email">
                 <div id="email_info" class="error"></div>
             </div>
             <div class="form-control">
                 <label>Password</label>
                 <input type = "password" name="password" id="password" placeholder="Enter u r password">
                 <div id="pass_info" class="error"></div>
             </div>
             <div class="form-control">
                 <label>Confirm password</label>
                 <input type = "password" name="confirm_pass" id="confirm_pass"  placeholder="Confirm u r password">
                 <div id="conf_pass" class="error"></div>
             </div>
             <button type="submit" class="btn" name="submit" id="submit">Sign up</button>
        </form>
        <p>Already a member?<a href="Login.php">Login here</a></p>
    </div>
    <?php endif ?>
<?php include ("includes/footer.php");?>
</div>
<div class="alert-overlay" id="alert-overlay">
     <div class="alert-box">
          <div class="left-head">
               <i class="fa fa-times"></i>
          </div>
          <div class="alert-con-wrapper">
             <div class="success-icon">
                <i class="fa fa-check"></i>
             </div>
             <div class="description">
               <h2 class="succ">Success!!!</h2>
               <p>You have successfully created an account</p>
             </div>
             <div class="animation-area">
               <ul class="box-area">
                  <li></li>
                  <li></li>
                  <li></li>
                  <li></li>
                  <li></li>
                  <li></li>
               </ul>
             </div>
           </div>
     </div>
</div>

<script>
       function responsePopup(){
             var overlay = document.getElementById("alert-overlay");
       }

       responsePopup()
</script>
</body>
</html>

<script>
    function verify(){
        var $valid = true;
        document.getElementById("oldPass").innerHTML = "";
        document.getElementById("newPass").innerHTML = "";
        document.getElementById("confPass").innerHTML = "";

        var oldPassword = document.getElementById("oldPassword").value;
        var newPassword = document.getElementById("newPassword").value;
        var confirmPassword = document.getElementById("conf_Password").value;

        if(oldPassword == ""){
            document.getElementById("oldPass").innerHTML = "Old password is required!";
            $valid = false;
        }

        if(newPassword == ""){
            document.getElementById("newPass").innerHTML = "New password is required!";
            $valid = false;
        }

        if(confirmPassword == ""){
            document.getElementById("confPass").innerHTML = "Confirm password is required!";
            $valid = false;
        }else if(newPassword !== confirmPassword){
            document.getElementById("confPass").innerHTML = "Confirm password is not a match!";
            $valid = false;
        }
        return $valid;
    }
</script>
<script>
    function validate(){
        var $valid = true;
        document.getElementById("user_info").innerHTML = "";
        document.getElementById("email_info").innerHTML = "";
        document.getElementById("pass_info").innerHTML = "";
        document.getElementById("conf_pass").innerHTML = "";
        emailRE = /^.+@.+\..{2,4}$/;

        var username = document.getElementById("username").value;
        var email = document.getElementById("email").value;
        var password = document.getElementById("password").value;
        var confirmPassword = document.getElementById("confirm_pass").value;

        if(username == ""){
            document.getElementById("user_info").innerHTML = "Username is required!";
            $valid = false;
        }

        if(email == ""){
            document.getElementById("email_info").innerHTML = "Email is required!";
            $valid = false;
        }

        if(password == ""){
            document.getElementById("pass_info").innerHTML = "Password is required!";
            $valid = false;
        }

        if(confirmPassword == ""){
            document.getElementById("conf_pass").innerHTML = "Confirm password is required!";
            $valid = false;
        }else if(confirmPassword !== password){
            document.getElementById("conf_pass").innerHTML = "Confirm password is not a match!";
            $valid = false;
        }

        return $valid;
    }
</script>
