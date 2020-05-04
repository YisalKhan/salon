jQuery(function ($) {

    // we create a copy of the WP inline edit post function
    var $wp_inline_edit = inlineEditPost.edit;

    // and then we overwrite the function with our own code
    inlineEditPost.edit = function( id ) {

	// "call" the original WP edit function
	// we don't want to leave WordPress hanging
	$wp_inline_edit.apply( this, arguments );

	// now we take care of our business

	// get the post ID
	var $post_id = 0;
	if ( typeof( id ) == 'object' ) {
		$post_id = parseInt( this.getId( id ) );
	}

	if ( $post_id > 0 ) {
		// define the edit row
		var $edit_row = $( '#edit-' + $post_id );
		var $post_row = $( '#post-' + $post_id );

		// get the data
		var $title	    = $( '.row-title', $post_row ).text();
		var $email	    = $( '.sln_email', $post_row ).text();
		var $phone	    = $( '.sln_phone', $post_row ).text();
		var $services   = [];

		$.each($( '.sln-service', $post_row ), function () {
		    $services.push($(this).attr('data-id'));
		});

		// populate the data
		$( 'input[name="sln_post_title"]', $edit_row ).val( $title );
		$( 'input[name="sln_email"]', $edit_row ).val( $email );
		$( 'input[name="sln_phone"]', $edit_row ).val( $phone );
		$( 'select[name="sln_services[]"]', $edit_row ).val( $services );
	}
    };

});