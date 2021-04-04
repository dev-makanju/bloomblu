<?php if(count($errors) > 0 ):?> 
    <div class="errors-log">      
        <?php foreach($errors as $error):?>
            <span class="error_info"><p><?php echo $error; ?><p></span>
        <?php endforeach ?>  
    </div>     
<?php endif ?>   
           