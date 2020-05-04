<tr>
    <td align="left" valign="top" style="font-size:18px;line-height:29px;color:#4d4d4d;font-weight:bold;font-family: 'Avenir-Medium',sans-serif,arial;padding: 0 0 0 8px;" class="font1">
	<?php _e('Dear administrator','salon-booking-system') ?>,
    </td>
</tr>
<tr>
    <td align="center" valign="top" height="22" style="font-size:1px;line-height:1px;">&nbsp;</td>
</tr>
<tr>
    <td align="left" valign="top" style="font-size:18px;line-height:29px;color:#4d4d4d;font-weight:500;font-family: 'Avenir-Medium',sans-serif,arial;padding: 0 0 0 8px;" class="font1">
	<?php

	    _e('your customers','salon-booking-system');

	    $usermeta = get_user_meta($booking->getUserId());

	    echo ' ' . $usermeta['first_name'][0] . ' ' . $usermeta['last_name'][0] . ' ';

	    _e('has submitted a new review on his last visit at','salon-booking-system');

	    echo ' ' . $plugin->getSettings()->getSalonName() . '.';
	?>
    </td>
</tr>
<tr>
    <td align="center" valign="top" height="22" style="font-size:1px;line-height:1px;">&nbsp;</td>
</tr>
<tr>
    <td align="left" valign="top" style="font-size:18px;line-height:29px;color:#4d4d4d;font-weight:500;font-family: 'Avenir-Medium',sans-serif,arial;padding: 0 0 0 8px;" class="font1">
	<?php

	    $comments = get_comments("post_id=" . $booking->getId());

	    echo (isset($comments[0]) ? '“' .$comments[0]->comment_content . '”' : '');
	?>

	<a href="<?php echo esc_url(add_query_arg(array('p' => $booking->getId()), admin_url('edit-comments.php'))); ?>#salon-review" style="
	    display: inline-block;
	    padding: 16px 12px;
	    margin-bottom: 0;
	    font-size: 12px;
	    font-weight: 400;
	    line-height: 1.42857143;
	    text-align: center;
	    white-space: nowrap;
	    vertical-align: middle;
	    -ms-touch-action: manipulation;
	    touch-action: manipulation;
	    cursor: pointer;
	    -webkit-user-select: none;
	    -moz-user-select: none;
	    -ms-user-select: none;
	    user-select: none;
	    background-image: none;
	    border: 1px solid transparent;
	    color: #fff;
	    background-color: #114566;
	    text-decoration: none;
	    margin-left: 20px">
		<?php _e('READ THE FULL REVIEW','salon-booking-system'); ?>
	</a>
    </td>
</tr>
<tr>
    <td align="center" valign="top" height="22" style="font-size:1px;line-height:1px;">&nbsp;</td>
</tr>