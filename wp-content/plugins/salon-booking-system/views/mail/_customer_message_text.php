<tr>
    <td align="left" valign="top" style="font-size:16px;line-height:24px;color:#4d4d4d;font-weight:bold;font-family: 'Avenir-Medium',sans-serif,arial;padding: 10px 0 0px 8px;">
    <?php _e('Dear', 'salon-booking-system') ?> <?php echo $booking->getFirstname() ?>,
    </td>
</tr>
<tr>
    <td align="center" valign="top" height="5" style="font-size:1px;line-height:1px;">&nbsp;</td>
</tr>
<tr>
    <td align="left" valign="top" style="font-size:16px;line-height:24px;color:#4d4d4d;font-weight:500;font-family: 'Avenir-Medium',sans-serif,arial;padding: 0 0 0 8px;" class="font1">
	<?php _e('please take note of the details of your reservation at', 'salon-booking-system') ?>
	<br>
	<?php echo $plugin->getSettings()->getSalonName() ?>.
    </td>
</tr>
<tr>
    <td align="center" valign="top" height="22" style="font-size:1px;line-height:1px;">&nbsp;</td>
</tr>
<tr>
    <td align="left" valign="top" style="font-size:18px;line-height:29px;color:#4d4d4d;font-weight:500;font-family: 'Avenir-Medium',sans-serif,arial;padding: 0 0 0 8px;" class="font1">
	<?php if ( $plugin->getSettings()->get('confirmation') && $booking->hasStatus(SLN_Enum_BookingStatus::PENDING) ) : ?>
	    <?php echo __('Your booking is pending, please await our confirmation.','salon-booking-system') ?>
	<?php endif ?>
    </td>
</tr>
<tr>
    <td align="center" valign="top" height="22" style="font-size:1px;line-height:1px;">&nbsp;</td>
</tr>