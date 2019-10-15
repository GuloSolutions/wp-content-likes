(function($) {
    'use strict';

    $(document).ready(function() {

        var postid;

        if ( $('body[class*="postid"]').length){
            postid = $('body[class*="postid"]').attr('class').split('postid-');
            postid = postid[1].split(" ")[0];
       }

       if ( $('body[class*="page-id"]').length){
            pageid = $('body[class*="page-id"]').attr('class').split('page-id-');
            pageid = pageid[1].split(" ")[0];
       }

        var data = {
            'action': '_s_export_liked_count',
            'get_count': postid ? postid : pageid
        }

        jQuery.post({
            url: liked_count.ajax_url,
            data: data,
            method: 'POST',
            success: function(res) {
                if (res !== null){
                    var like_count_div = '<div class="likes-count">' + res + '</div>';
                    $('.social-likes').append(like_count_div);
                }
                else {
                   var like_count_div = '<div class="likes-count"></div>';
                   $('.social-likes').append(like_count_div);
                   $('.likes-count').hide();
               }
            }
        });
    });
})(jQuery);
