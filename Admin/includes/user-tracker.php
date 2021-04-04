<?php 
/*******
*Return all daily user
**************** */
function getTotalVisitTodayCount(){
    global $conn;
    $sql = "SELECT COUNT(*) AS total FROM website_visitor_counter WHERE DATE(date) = CURDATE()";
    $result = mysqli_query($conn , $sql);
    $rs = mysqli_fetch_assoc($result)['total'];
    return $rs;    
}

function getRegUserTodayCount(){
    global $conn;
    $sql = "SELECT COUNT(*) AS total FROM users WHERE DATE(updated_at) = CURDATE()";
    $rs = mysqli_query($conn , $sql);
    $result = mysqli_fetch_assoc($rs);
    return $result['total'];
}

function getAllRegUserCount(){
    global $conn;
    $sql = "SELECT COUNT(*) AS total FROM users ";
    $rs = mysqli_query($conn , $sql);
    $result = mysqli_fetch_assoc($rs);
    return $result['total'];
}

function getActiveRegUserCount(){
    global $conn;
    $sql = "SELECT COUNT(*) AS total FROM users WHERE updated_at >= DATE_SUB(NOW() , INTERVAL 2 MONTH) ";
    $rs = mysqli_query($conn , $sql);
    $result = mysqli_fetch_assoc($rs);
    return $result['total'];
}

function getAllArtCount(){
    global $conn;
    $sql = "SELECT COUNT(*) AS total FROM posts WHERE news != true ";
    $rs = mysqli_query($conn , $sql);
    $result = mysqli_fetch_assoc($rs);
    return $result['total'];
}

function getAllNewsCount(){
    global $conn;
    $sql = "SELECT COUNT(*) AS total FROM posts WHERE news = true ";
    $rs = mysqli_query($conn , $sql);
    $result = mysqli_fetch_assoc($rs);
    return $result['total'];
}

function getCountAllOnlineUser(){
    global $conn;
    $cur_time = date('U');
    $sql = "SELECT COUNT(*) AS total FROM users WHERE last_login_time > '$cur_time()' ";
    $rs = mysqli_query($conn , $sql);
    $result = mysqli_fetch_assoc($rs);
    return $result['total'];
}

function getCountAllAlthur(){
    global $conn;
    $sql = "SELECT COUNT(*) AS total FROM users WHERE role ='Author'";
    $rs = mysqli_query($conn , $sql);
    $result = mysqli_fetch_assoc($rs);
    return $result['total'];
}

function getCountAllAdmin(){
    global $conn;
    $sql = "SELECT COUNT(*) AS total FROM users WHERE role ='Admin'";
    $rs = mysqli_query($conn , $sql);
    $result = mysqli_fetch_assoc($rs);
    return $result['total'];
}

/*******
**INTIALIZING VARIABLES 
*/
//initialise current month

$current_month = date('m');

function current_Month(){
    global $conn;
    //check database if current month is thesame as last entry month;
    $sql = "SELECT * FROM website_visitor_counter ORDER BY id DESC LIMIT 1";
    $rs = mysqli_query($conn,$sql);
    $result = mysqli_fetch_assoc($rs)['date'];
    $pre_year = date("Y" , strtotime($result));
    $pre_month = date("m" , strtotime($result));//fetch last entry month
    $curr_month_date = date('m');//fetch current date 
    if($pre_month != $curr_month_date){
        //select count from website monthly counter
        //update visitor table on previous month
        $sql = "SELECT COUNT(*) AS total FROM website_visitor_counter";
        $result = mysqli_query($conn , $sql);
        $total = mysqli_fetch_assoc($result)['total'];
        update_Table($total , $pre_month , $pre_year);
    }
}

function update_Table($total , $pre_month , $pre_year){
    global $conn;
    $sql = "INSERT INTO visitor_table ( month_name , year_name , monthly_count) VALUES('$pre_month','$pre_year','$total')";
    $result = mysqli_query($conn , $sql);
    if($result){
        //delete all previous entry from website counter
        $sql = "DELETE  FROM website_visitor_counter ";
        $res = mysqli_query($conn , $sql);
    }else{
        echo "error not working";
    }
}

//morris bar
function showChart(){
    global $conn;
    $curr_year = date('y');
    $chart_data = '';
    $sql = "SELECT * FROM visitor_table  WHERE year_name = $curr_year ";
    $rs = mysqli_query($conn , $sql);

    while($row = mysqli_fetch_array($rs)){
        $chart_data .= "{ year: '".$row['month_name']."', monthly_visitors: '".$row['monthly_count']."'}";
    }

    $chart_data = substr($chart_data , 0 , -2);
}
/******
*GET URI IDENTIFIERs 
*CHRCk if user has visited 
*update ads for click
*******/

if(isset($_GET['AdsClick'])){
    $id = $_GET['AdsClick'];
    updateAdsClick($id);
} 

function updateAdsClick($id){
      global $conn;
      $id = md5($id);
      $sql = "SELECT * FROM ads_table WHERE visitor_id = '$cookie_value' AND ads_id = $id";
      $rs = mysqli_query($conn , $rs);
      if(!mysqli_nums_rows($rs) > 0){
          $sql = " INSERT INTO ads_table ( visitor_id , ads_id) VALUES($cookie_value , $id) ";
          $result = mysqli_query($conn , $sql);
      }
      $sql = "UPDATE ads SET click=click+1 WHERE id =$id";
      $result=mysqli_query($conn , $sql);
}





?>