jQuery(document).ready(function () {

    var $animation_elements = jQuery('.animated');
    var $window = jQuery(window);

    function check_if_in_view() {
	var window_height = $window.height();
	var window_top_position = $window.scrollTop();
	var window_bottom_position = (window_top_position + window_height);

	jQuery.each($animation_elements, function () {
	    var $element = jQuery(this);
	    var element_height = $element.outerHeight();
	    var element_top_position = $element.offset().top;
	    var element_bottom_position = (element_top_position + element_height);

	    //check to see if this current container is within viewport
	    if ((element_bottom_position >= window_top_position) &&
		    (element_top_position <= window_bottom_position)) {
		$element.addClass('animate');
	    } else {
		$element.removeClass('animate');
	    }
	});
    }

    $window.on('scroll resize', check_if_in_view);
    $window.trigger('scroll');

});