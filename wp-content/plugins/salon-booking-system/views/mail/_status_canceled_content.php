<tr>
    <td align="left" valign="top" style="font-size:18px;line-height:20px;color:#4d4d4d;font-weight:bold;font-family: 'Avenir-Medium',sans-serif,arial;padding: 0 0 0 8px;">
	<?php _e('Dear', 'salon-booking-system') ?> <?php echo $booking->getDisplayName() ?>,
    </td>
</tr>
<tr>
    <td align="center" valign="top" height="5" style="font-size:1px;line-height:1px;">&nbsp;</td>
</tr>
<tr>
    <td align="left" valign="top" style="font-size:18px;line-height:29px;color:#4d4d4d;font-weight:500;font-family: 'Avenir-Medium',sans-serif,arial;padding: 0 0 0 8px;" class="font1">
	<?php echo sprintf(__('the following booking at %s has been canceled', 'salon-booking-system'), $plugin->getSettings()->getSalonName() ) ?>.
    </td>
</tr>
<tr>
    <td align="center" valign="top" height="22" style="font-size:1px;line-height:1px;">&nbsp;</td>
</tr>