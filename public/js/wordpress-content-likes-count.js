(function($) {
    'use strict';

    $(document).ready(function() {

        var postid;
        var user;

        if ( $('body[class*="postid"]').length){
            postid = $('body[class*="postid"]').attr('class').split('postid-');
            postid = postid[1].split(" ")[0];
       }

       if ( $('body[class*="page-id"]').length){
            pageid = $('body[class*="page-id"]').attr('class').split('page-id-');
            pageid = pageid[1].split(" ")[0];
       }

       var user = getCookie('hasVoted');

        var data = {
            'action': '_s_export_liked_count',
            'get_count': postid ? postid : pageid,
            'wp_content_user' : user
        }

        jQuery.post({
            url: liked_count.ajax_url,
            data: data,
            method: 'POST',
            dataType: 'json',
            success: function(res) {
                if (res.LIKE != 0){
                    var like_count_div = '<div class="likes-count">' + res.LIKE + '</div>';
                    $('.social-likes').append(like_count_div);
                    if (res.VOTE == 1 ){
                        $('.social-likes').addClass( 'active' );
                    }
                }
                else {
                   var like_count_div = '<div class="likes-count"></div>';
                   $('.social-likes').append(like_count_div);
                   $('.likes-count').hide();
               }
            }
        });

        function getCookie(name) {
            var v = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
            return v ? v[2] : null;
        }
    });
})(jQuery);
