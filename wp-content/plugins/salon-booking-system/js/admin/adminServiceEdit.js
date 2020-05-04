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
		var $post_name	    = $( '.post_name', $post_row ).text();
		var $unit	    = $( '.sln-service-unit', $post_row ).attr('data-value');
		var $duration	    = $( '.service_duration', $post_row ).text();
		var $price	    = parseFloat($( '.service_price', $post_row ).text());
		var $secondary	    = $( '.secondary', $post_row ).text() === 'YES' ? 1 : 0;

		$('.tags_input', $post_row).each(function(){

		    var terms = $(this),
			    taxname = $(this).attr('id').replace('_' + $post_id, ''),
			    textarea = $('textarea.' + taxname, $edit_row),
			    comma = inlineEditL10n.comma;

		    terms.find( 'img' ).replaceWith( function() { return this.alt; } );
		    terms = terms.text();

		    if ( terms ) {
			    if ( ',' !== comma ) {
				    terms = terms.replace(/,/g, comma);
			    }
			    textarea.val(terms);
		    }

		    textarea.wpTagsSuggest();
		});

		// populate the data
		$( 'input[name="sln_post_title"]', $edit_row ).val( $title );
		$( 'input[name="sln_post_name"]', $edit_row ).val( $post_name );
		$( 'select[name="sln_service_unit"]', $edit_row ).val( $unit );
		$( 'select[name="sln_service_duration"]', $edit_row ).val( $duration );
		$( 'input[name="sln_service_price"]', $edit_row ).val( $price );
		$( 'input[name="sln_service_secondary"]', $edit_row ).prop( 'checked', $secondary ? true : false);
	}
    };

});