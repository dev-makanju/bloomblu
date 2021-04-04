<!--input the serch link-->
<div class="pub_search_wrapper">
     <form action="search_res.php" method="post" style="position:relative;padding:0px;">
        <div class="admin-news-header">
            <input class="inp" type="text" id="search" name="search" placeholder="search a post">
                <button type="submit" class="btn-submit" name="search-submit" ><i class="fa fa-search" style="color:#000"></i></button>
                <div style="position:relative;">
                <div id="display" style="text-align:left;"></div>
            </div>
        </div>
    </form>
</div><!--/public_search_wrapper-->

<script>
    function fill(Value){
        $("#search").val(Value);
        $("#display").hide();
    }

    $(document).ready(function(){

        $("#search").keyup(function(){
            var input = $("#search").val();
            if(input == ""){
                //assign empty var to it
                $('#display').html("");
            }
            else{
                //ajax is called
                $.ajax({
                    type: "post",
                    url: "includes/search-data.php",
                    data:{
                        //display div in search.php
                        search:input
                    },
                success: function(html){
                    //display div in search.php
                    $('#display').html(html).show();
                }
               });
            }
         });
    });

</script>