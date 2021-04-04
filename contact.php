<!--include the database connection file-->
<?php include ("config/config.php");?>
<!--include the public function-->
<?php include ("includes/public_function.php");?>
<!--include the head section -->
<?php include ("includes/head-file.php")?>
<meta name="desciption" content="Write a message to us about your problem on this site">
    <title>BloomBlue | Contact</title>
</head>
<body>
<div class="container">
<a href="top"></a> 
    <!--main header navigation secion-->
    <?php include ("includes/header.php");?>
    <!--main banner page-->
    <!--include the error file code for form login-->
    <div id="About_page">
       <?php include ("includes/errors.php")?>
       <?php include ("includes/message.php")?>
        <div class="cont">
           <h2 class="cont_head" align="center" style="color:black;">How can we help you ?</h2>
           <form action="<?php $_SERVER['SCRIPT_NAME']; ?>" method="post">
                <input type="text" name="cont_name" placeholder="Enter u r name" value="<?php echo $username ?>">
                <input type="email" name="cont_email" placeholder="Enter u r email" value = "<?php echo $email ?>">
                <textarea  name="cont_body" id="" cols="30" rows="10" placeholder="Enter u r message" value = "<?php echo $body ?>"></textarea>
                <button class="cont_submit" id="submit_msg" name="submit_msg">Sumit</button>
           </form>
        </div>
    </div>
    <!--footer section-->
    <?php include ("includes/footer.php");?>
</div>    
</body>
</html>