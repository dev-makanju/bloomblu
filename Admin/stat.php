<?php include("includes/post_function.php");?>
<?php include ("includes/Admin_function.php")?>
<!--includes the head file-->
<?php include("includes/head-file.php");?>
<!--includes the database connection file-->
<?php include("../config/config.php"); ?>
    <title>Bloomblue | dashboard</title>
    <link rel="stylesheet" href="../CSS/Admin_style.css">
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
           <h4>Total visitor counter for Today <?php echo date("F j , Y ");?>
               <span>5000visitors</span>
           </h4>
           
      </div>
      <?php if($_SESSION['user']['role'] == "Admin"): ?>
      <!--include the side nav section-->
        <?php include ("includes/navbar.php")?>
       <?php endif ?>         
      </div> 
<?php include ("includes/footer.php")?>
</body>
</html>

<script>
       Morris.Bar({
            element : 'chart',
            data:[<?php echo $chart_data; ?>],
            xkey: 'year',
            ykeys:['profit' , 'purchase' , 'sales'],
            labels:['profit' , 'purchase' , 'sales'],
            hideHover:'auto',
       })
</script>