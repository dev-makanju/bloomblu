$(document).ready(function(){
    //when user click on submit comment to add comment under post
    $(document).on('click' , '#submit_comment' , function(e){
        e.preventDefault();
        //get the post id from the form data-id attribute
        var post_id = $(this).data('id');
        //grab the comment text from form element
        var comment_text = $('#comment_text').val();
        var url = $('#comment_form').attr('action');

        //stop executing if no value is enter
        if(comment_text === "")return;
        $.ajax({
            url:url,
            type:"html",
            data:{
                post_id:post_id,
                comment_text:comment_text,
                comment_posted:1
            },
            success: function(data){
                var response = JSON.parse(data);
                if(data === "error"){
                    alert("There was an error adding comment, please try again");
                }else{
                    $('#comments-wrapper').prepend(response.comment);
                    $('#comments_count').text(response.comments_count);
                    $('#comment_text').val('');
                }
            }
       });
    });

    //when user clicks on submit reply to add replied under comment
    $(document).on('click' , '.reply-btn' , function(e){
        e.preventDefault();
        //get the comment id from the reply  button's data-id attribute 
        var comment_id = $(this).data('id');
        //show/hide the appropriate reply form (from the reply btn(this)
        //go to the parent elament (comment-details)
        //and then its sibling which is form element with id comment_reply_form + comment_id
        $(this).parent().siblings('form#comment_reply_form_' + comment_id).toggle(500);
        $(document).on('click' , '.submit-reply', function(e){
            e.preventDefault();
            //elements
            var reply_textarea = $(this).siblings('textarea');//reply element
            var reply_text = $(this).siblings('textarea').val();
            var url = $(this).parent().attr('action');
            
            if(reply_text === "")return;
            $.ajax({
                url:url,
                type:"POST",
                data:{
                    comment_id:comment_id,
                    reply_text:reply_text,
                    reply_posted:1
                },
                success: function(data){
                    if(data === "error"){
                        alert("there was an error adding reply");
                    }else{
                        $('.replies_wrapper_' + comment_id).append(data);
                        reply_textarea.val("");
                    }
                }
            });
        });
    });
}); 