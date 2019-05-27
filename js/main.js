$('.playgame').on('click',function(){
    location.href="home.php";
});
$('.feedbutton').on('click',function(){
    $.ajax({
        url: 'feed.php',
        type: 'post',
        success:function(responseText){
        var res = $.parseJSON(responseText);
            if(res.success == 1)
            {
                location.reload();
            }
        }
    });
});
$(window).on('load',function(e){
    var page = $('.pagename').val();
    if(page == 'home')
    {
        var game_over_reason = $('.game_over_reason').val();
        if(game_over_reason != '')
        {
            smoke.confirm(game_over_reason, function(e){
                if(e)
                {
                    location.href = 'home.php';
                }
                else
                {
                    location.href = 'index.php';
                }
                }, {
                ok: "playagain",
                cancel: "Not intrested"
            });
        }
    }
});
