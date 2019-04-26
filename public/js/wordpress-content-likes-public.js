(function($) {
    let _is_cookie_set = false;
    let cur_url = $(location).attr('href');
    let sub_cur_url = cur_url.substr(cur_url.lastIndexOf("/") -15);
    $( document ).ready(function() {
        if (readCookie('hasVoted' + sub_cur_url ) && vote_cookie == 1){
            $('.social-likes').addClass( 'active' );
            _is_cookie_set = true;
        }

    //     console.log(like_count);
    //     console.log(vote_cookie);
    var like_count = 20;

    var vote_cookie = 1;

        if (like_count !== undefined  && like_count >= 1){
            var like_count_div = '<div class="likes-count">' + like_count + '</div>';
           $('.social-likes').append(like_count_div);
        }

       $('.social-likes').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            var postid;
            var clicktype;
            var newclicktype;
            var result;
            var counter;

            var $button = $(this);
            clicktype = $button.attr('clicktype');

            if (clicktype ==  0){
                newclicktype = 1;
            } else if (clicktype == 1){
               newclicktype = 2;
               $('.social-likes').removeClass( 'active' );

            } else if (clicktype == 2){
                newclicktype = 1;
                $('.social-likes').addClass( 'active' );
            }

            if (readCookie('hasVoted' + sub_cur_url) === null ){
                createCookie('hasVoted', 1, 60);
                $('.social-likes').toggleClass( 'active' );
             }

            if ( typeof($('body[class*="postid"]')) != undefined){
                 postid = $('body[class*="postid"]').attr('class').split('postid-');
                 postid = postid[1].split(" ")[0];
            }

            var likedata = {
                'action': 'like_handler',
                'postid': postid,
                'voted': _is_cookie_set,
                'vote': clicktype,
                'newvote': newclicktype
            };

            jQuery.post({
                url : ajax_object.ajaxurl,
                type : 'POST',
                data : likedata,
                dataType: 'json',
                success : function( response ){
                     $button.attr('clicktype', newclicktype);
                     if (response == 1 ) {
                         $('.social-likes').append('<div class="likes-count">' + response + '</div>');
                     } else {
                        $('.likes-count').html('<div>' +  response + '</div>');
                    }
                }
            });
        });
     });

 function createCookie(name, value, days) {
    var expires = '',
        date = new Date();
    if (days) {
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = '; expires=' + date.toGMTString();
    }
    document.cookie = name + sub_cur_url + '=' + value + expires + '; path=cur_url';
}

function readCookie(name) {
    var nameEQ = name + '=',
        allCookies = document.cookie.split(';'),
        i,
        cookie;
    for (i = 0; i < allCookies.length; i += 1) {
        cookie = allCookies[i];
        while (cookie.charAt(0) === ' ') {
            cookie = cookie.substring(1, cookie.length);
        }
        if (cookie.indexOf(nameEQ) === 0) {
            return cookie.substring(nameEQ.length, cookie.length);
        }
    }
    return null;
}

function eraseCookie(name) {
    createCookie(name, '', -1);
}

}) (jQuery);
