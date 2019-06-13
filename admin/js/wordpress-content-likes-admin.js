(function( $ ) {
	'use strict';

	$( window ).load(function() {
		$('#wp-content-likes-delete-all').on('click', function(e){
			e.preventDefault();
			e.stopPropagation();
			alert("Are you certain? This will delete all plugin data.");
			var deletedata = {
                'action': 'delete_handler',
                'delete_button_id': e.target.id,
            };
            jQuery.ajax({
                url : ajax_wp_content_likes.ajaxurl,
				type : 'POST',
				data: deletedata,
                dataType: 'json',
                success : function( response ){
                },
                complete: function(){
					alert('Deleted');
                }
            });
		})
	});
})( jQuery );
