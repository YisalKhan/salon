<tr>
    <td align="left" valign="top" style="font-size:16px;line-height:24px;color:#4d4d4d;font-weight:bold;font-family: 'Avenir-Medium',sans-serif,arial;padding: 10px 0 0 8px;">
	<?php _e('Dear administrator', 'salon-booking-system') ?>,
    </td>
</tr>
<tr>
    <td align="center" valign="top" height="5" style="font-size:1px;line-height:1px;">&nbsp;</td>
</tr>
<tr>
    <td align="left" valign="top" style="font-size:16px;line-height:24px;color:#4d4d4d;font-weight:500;font-family: 'Avenir-Medium',sans-serif,arial;padding: 0 0 0 8px;" class="font1">
	<?php _e('this is an e-mail notification of a new booking', 'salon-booking-system') ?>
	<?php
	    $_text = apply_filters('sln.new_booking.notifications.email.body.title', '', $booking);
	    $_text = $_text ? $_text : _e(' at ', 'salon-booking-system') . $plugin->getSettings()->getSalonName();
	?>
	<?php echo $_text ?>,
	<?php _e('please take note of the following booking details', 'salon-booking-system') ?>.
    </td>
</tr>
<tr>
    <td align="center" valign="top" height="22" style="font-size:1px;line-height:1px;">&nbsp;</td>
</tr>