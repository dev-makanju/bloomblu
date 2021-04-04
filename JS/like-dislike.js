$(document).ready(function(){
    //if user click on the like button
    $('.like-btn').on('click' , function(){
        var post_id = $(this).data('id');
        $clicked_btn = $(this);

        if($clicked_btn.hasClass('fa-thumbs-o-up')){
            action = 'like';
        }else if($clicked_btn.hasClass('fa-thumbs-up')){
            action = 'unlike';
        }
        $.ajax({
            url: 'single_post.php',
            type: 'POST',
            data: {
                'action': action,
                'post_id': post_id
            },
            success: function(data){
                res = JSON.parse(data);
                if(action == 'like'){
                    $clicked_btn.removeClass('fa-thumbs-o-up');
                    $clicked_btn.addClass('fa-thumbs-up');
                }else if(action == 'unlike'){
                    $clicked_btn.removeClass('fa-thumbs-up');

                    $clicked_btn.addClass('fa-thumbs-o-up');
                }
                //display the number of likes and dislike
                $clicked_btn.siblings('span.likes').text(res.likes);
                $clicked_btn.siblings('span.dislikes').text(res.dislikes);

                //change button styling based on user reaction to a post
                $clicked_btn.siblings('i.fa-thumbs-down').removeClass('fa-thumbs-down').addClass('fa-thumbs-o-down');                          
            }
        });
    }); 
    //if user clicked on the dislike button
    
    $('.dislike-btn').on('click' , function(){
        var post_id = $(this).data('id');
        $clicked_btn = $(this);

        if($clicked_btn.hasClass('fa-thumbs-o-down')){
            action = 'dislike';
        }else if($clicked_btn.hasClass('fa-thumbs-down')){
            action = 'undislike';
        }
        $.ajax({
            url: 'single_post.php',
            type: 'POST',
            data: {
                'action': action,
                'post_id': post_id
            },
            success: function(data){
                res = JSON.parse(data);
                if(action == 'dislike'){
                    $clicked_btn.removeClass('fa-thumbs-o-down');
                    $clicked_btn.addClass('fa-thumbs-down');
                }else if(action == 'undislike'){
                    $clicked_btn.removeClass('fa-thumbs-down');

                    $clicked_btn.addClass('fa-thumbs-o-down');
                }
                //display the number of likes and dislike
                $clicked_btn.siblings('span.likes').text(res.likes);
                $clicked_btn.siblings('span.dislikes').text(res.dislikes);

                //change button styling based on user reaction to a post
                $clicked_btn.siblings('i.fa-thumbs-up').removeClass('fa-thumbs-up').addClass('fa-thumbs-o-up');                          
            }
        });
    }); 
});