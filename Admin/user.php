<!--includes the database connection file-->
<?php include("../config/config.php"); ?>
<!--include the Admin functionality-->
<?php include("includes/Admin_function.php");?>
<!--includes the head file-->
<?php 

if(isset($_GET['pageno'])){
   $pageno = $_GET['pageno'];
   $user = getAdminUser($pageno);
   extract($user);
}else{
   $pageno = 1;
   $user = getAdminUser($pageno);
   extract($user);
}

?>

<?php $roles = ['Admin','Author']; ?>
<?php include ("includes/head-file.php");?>
    <title>Bloomblue | manage-user</title>
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
           <!--search-->
        <?php include ("search.php") ;?>
        <?php include ("../includes/errors.php");?>
        <?php include ("includes/message.php");?>
         <div class="forms">
          <h1>Create new user</h1>
         <form  action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
        <?php if ($isEditingUser === true) :?>
             <input type="hidden" name="Admin_id" id="" value="<?php $Admin_id ;?>">
        <?php endif ?>
          <div class="user_form_control">
               <input type="" name="username" id="username" placeholder="Enter u r username" value="<?php echo $username; ?>">
            </div>  
            <div class="user_form_control">
               <input type="email" name="email" id="username" placeholder="Enter u r email" value="<?php echo $email; ?>">
            </div>  
            <div class="user_form_control">
               <input type="password" name="password" id="username" placeholder="Enter u r password">
            </div> 
            <div class="user_form_control">
               <input type="password" name="Confirm_password" id="username" placeholder="Confirm u r password">
            </div> 
            <select name="role" id="">
                   <option value="selected dissable">Assign role</option>
               <?php foreach($roles as $key => $role):?>    
                   <option value="<?php echo $role ?>"><?php echo $role ?></option>
               <?php endforeach ?>     
            </select>
        <?php if($isEditingUser === true ):?>               
            <button type="submit" name="update_user">UPDATE USER</button>
        <?php else: ?>
            <button  type="submit" name="create_user">SAVE USER</button>
        <?php endif ?>    
        </form>  
        </div>
      <!--table output for displaying the registerd users detrail-->
      <div class="tab-scroll">
         <table>
            <thead>
                  <th>S/N</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Role</th>
                  <th>edit</th>
                  <th>delete</th>
            </thead>
            <?php foreach ($user as $key => $users):?>
            <tbody>
               <tr>
                  <td><?php echo $key + 1 ;?></td>
                  <td><?php echo $users['username']; ?></td>
                  <td><?php echo $users['email']; ?></td>
                  <td><?php echo $users['role'];?></td>
                  <td><a class="fa fa-pencil btn edit" href="<?php echo $config_basedir ?>Admin/user.php?edit-user=<?php echo hashUrlData($users['id'])?>"></a></td>
                  <td><a class="fa fa-trash btn delete" href="<?php echo $config_basedir ?>Admin/user.php?delete-user=<?php echo hashUrlData($users['id']) ?>"></a></td>
               </tr>      
            </tbody>
            <?php endforeach ?>
         </table>
       


         <!--include pagination-->
       <!--Pagin buttons--->  
      </div>  
     </div>
      <!--Only admin user should be able to mange user and post-->
      <?php if($_SESSION['user']['role'] == "Admin"): ?>
         <!--include the side nav section-->
         <?php include ("includes/navbar.php")?>
       <?php endif ?>
       
</div> 
        <ul class="pagination" style="margin-top: 100px;">
                <li><a href="?pageno=1">First</a></li>
            <li class="<?php if($pageno <= 1){ echo 'disabled'; }?>">
                <a href="<?php if($pageno <= 1){ echo '#'; }else{echo "?pageno=".($pageno - 1);}?>">Prev</a>
            </li>
            <li class="<?php if($pageno >= $total_page){ echo 'disabled'; }?>">
                <a href="<?php if($pageno >= $total_page){ echo '#'; }else{ echo "?pageno=".($pageno + 1); }?>">Next</a>
            </li>
                <li><a href="?pageno=<?php echo $total_page; ?>">Last</a></li>
         </ul>   
      <?php include ("includes/footer.php")?>
</body>
</html>