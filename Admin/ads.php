<!--includes the database connection file-->
<?php include("../config/config.php"); ?>
<!--include the Admin functionality-->
<?php include("includes/Admin_function.php");?>
<!--includes the head file-->
<?php include("includes/head-file.php");?>
<?php $Ads_info = getAllads();?>
<?php $ads = ['Gold' , 'silver' , 'premium']?>
    <title>Bloomblu | adverticement</title>
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
     <!--include the head nav section-->
     <?php include ("includes/header.php");?>
     <div id="container">
         <div class="ads-tab">
               <!--search-->
               <?php include ("search.php") ;?>
        <?php include ("../includes/errors.php");?>
        <?php include ("includes/message.php");?>
            <div class="Ads-forms">
             <h2 style="text-align:center"><span style="color:red">Adv</span><span style="color:white">ert</span><span style="color:green">ice</span><span style="color:brown">ment</span></h2>
             <div class="form-group text-center" style="position:relative;">
                  <span class="img-div">
                       <div class="text-center img-placeholder" onClick="triggerClick()">
                           <h4>update your ads</h4>
                       </div>
                       <img class="Ads-gadget" src="" onClick="triggerClick()" id="profileDisplay">
                  </span>
                    <form class="ads-form" action="<?php $_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data" >
                         <input type="file" name="AdsImage" onChange="displayImage(this)" id="profileImage" class="form-control" style="display:none">
                         <input type="" name="link" id="link" placeholder="Add a link" class="Ads-links" value="<?php echo $Ads_name ;?>">
                           <select name="Ads_id" id="">
                             <option value="selected disabled">--select Advert type</option>
                               <?php foreach($ads as $ads_type):?>
                             <option value="<?php echo $ads_type; ?>"><?php echo $ads_type; ?></option>
                               <?php endforeach ?>
                          </select>
                         <div id="error" style="color:red;"></div>
                         <label><p>click the box above to upload your ads<p></label>
                         <?php if($isEditingAds === true):?>  
                             <button type="submit" name="update_ads">Update ads</button>
                         <?php else :?> 
                              <?php if(getAllAdsCount() > 2):?>
                                  <?php array_push($errors ,"You have already use all your ads option"); ?>
                              <?php else: ?> 
                                <button type="submit" name="save_ads">Save ads</button>
                              <?php endif ?>   
                         <?php endif ?> 
                   </form>  
             </div>
           </div>
           <p style="color:black"><span style="color:red">Note:</span>You can't have more than 3 at a time ads on this site to avoid overpolulation of brands.</p>
           <h1>Available Ads</h1>
           <!--includes the ads gadget-->
           <?php if($Ads_info):?>
            <div class="gallery-tab">
           <?php foreach($Ads_info as $Ads_infos):?> 
               <div class="ads-link-wrapper">
                  <div class="image">
                    <a href="ads.php?id=<?php echo $Ads_infos['id'];?>"><img class="banner-image" src="../CSS/MEDIA/<?php echo $Ads_infos['image']?>" alt=""></a>
                </div> 
                <a class="fa fa-pencil btn edit" href="ads.php?edit-ads=<?php echo $Ads_infos['id'];?>"></a> &nbsp&nbsp&nbsp<a  class="fa fa-trash" href="ads.php?delete-ads=<?php echo $Ads_infos['id'];?>"></a>
           </div>
           <?php endforeach ?>
           </div>
           <?php endif ?>
           <p style='text-align'>supported by RamRam ads</p>
         </div>
        <!--include the side nav section-->
        <?php include ("includes/navbar.php")?>
     </div>
     <!--include the footer section -->  
      <?php include ("includes/footer.php")?>
</body>
</html> 
