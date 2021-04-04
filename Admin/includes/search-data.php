<?php
//function implementation
$con = mysqli_connect("localhost" , "root", "" , "bloomblu");
if (mysqli_connect_errno()){
    echo"Unable to connect to the datatbase";
}


if(isset($_POST['search'])){
    global $con;
    $Name = strip_tags(addslashes($_POST['search']));
    $query = "SELECT * FROM posts WHERE title LIKE '%$Name%' LIMIT 10";
    $EXE_query = mysqli_query($con , $query);

    //creating unordered list to display
    echo '<ul>';

    while($Result = mysqli_fetch_array($EXE_query)){
        //creating an unorderd list
        //calling javascript function named as "fill" founf in script.js file
        //by passing fetch result as parameter.
        ?>
        <li onclick='fill("<?php echo $Result['title'] ?>") '>
            <a href="search-result.php?search=<?php echo  $Result['id']; ?> ">
               <!---Asigning search result in "Search box" in search.php-->
               <?php echo $Result['title']; ?>
            </a>
        </li>
<?php   

    }
}


//when user click form submit alternatively
//grab the user input 
//append it to the url
//send a queryString to search-result.php



?>

</ul>