<?php
if(isset($_SESSION['message'])){
?>    
    <div class="message">
        <?php
            echo $_SESSION['message'];

            unset($_SESSION['message']);
        ?>
    </div>   
<?php
}
?>