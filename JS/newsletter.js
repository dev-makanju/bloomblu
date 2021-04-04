//when user click on the subscribe button
//grab the input and send it via ajax to be the database
$(document).ready(function(){
    //validate input
    function isValidEmailaddress(emailAddress){ 
        var partern = new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);
        return partern.test(emailAddress);
    }; 

  //cookie start
  var subscribed = $.cookie('subscribed');
  if(subscribed == 'true'){
        $('#subscribe_box').hide();
  }else{
   //when user click on subscribe form
       $("#subscribe").click(function(){
       //grab the input and store as variables variables
       var value = 1;
       var email = $('#email').val();
       var dataString = 'action=subscribe&email='+email;
       if($.trim(email).length > 0 && isValidEmailaddress(email)){
        $.ajax({
            type: "POST",
            url: "./includes/external_file.php",
            data: dataString,
            cache: false,
        beforeSend: function(){
            $("#email").attr("disabled" , true);
            $("#subscribe").attr("disabled" , true);
            $("#subscribe").val("Loading...");
            $("#subscribe").removeClass();
            $("#subscribe").addClass("btn");
            $("#subscribe").addClass("btn-warning");
        },  
        success: function(data){
            if(data){
                if(data === 'true'){
                     //removeCookie subscribed
                     $('#subscribe_message').removeClass();
                     $('#subscribe_message').addClass('success_message');
                     $('#subscribe_message').html("<span class='glyphicon glyphicon glyphicon-ok'></span> You have successfully subscribed");
                     $('#subscribe_message').fadeIn().delay(5000).fadeOut();
                }else if(data === 'exists'){
                     $('#subscribe_message').removeClass();
                     $('#subscribe_message').addClass("success_message");
                     $('#subscribe_message').html("<span class='glyphicon glyphicon glyphicon-exclamation-sign'></span>You have Already subscribed with us!");
                }else{
                     $('#subscribe_message').removeClass();
                     $('#subscribe_message').addClass("error_message");
                     $('#subscribe_message').html('There is an sql error please notify the Admin about it!');
                     $('#subscribe_message').fadeIn().delay(3000).fadeOut();
                }
              $("#email").attr("disabled" , false);
              $("#subscribe").attr("disabled" , false);
              $('#subscribe').val("Subscribe");
              $('#subscribe_message').removeClass();
              $("#subscribe_message").addClass("btn-success");
            }else{
                $('#email').attr("disabled" , false);
                $('#subscribe').attr("disabled" , false );
                $('#subscribe').removeClass();
                $('#subscribe').addClass('btn');
                $("#subscribe").attr("btn-danger");
                $("#subscribe").val("Subscribe");
                $("#subscribe_message").removeClass();
                $("#subscribe_message").addClass("error_message");
                $("#subscribe_message").html("<span class='glyphicon glyphicon glyphicon-exclamation-sign'></span>There is an error");
                $("#subscribe_message").fadeIn().delay(5000).fadeOut();
            }
        }
        });
        }else{
            $('#subscribe_message').addClass('error_message');
            $("#subscribe_message").html("<span class='glyphicon glyphicon glyphicon-exclamation-sign'></span>input a valid email");
            $('#subscribe_message').fadeIn().delay(5000).fadeOut();
        } 
        return false;
    });
    }    
});
