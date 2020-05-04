<table width="148" cellspacing="0" cellpadding="0" border="0">
    <tr>
	<td align="center" valign="top">
	    <table width="148" border="0" cellpadding="0" cellspacing="0">
		<tbody>
		    <?php if ($plugin->getSettings()->get('confirmation') && $booking->hasStatus(SLN_Enum_BookingStatus::PENDING)) : ?>
			<tr>
			    <td align="center" valign="middle" height="48" bgcolor="#00ff00"  style="font-size:14px;line-height:20px;color:#ffffff;font-weight:bold;font-family: 'Avenir-Medium',sans-serif,arial;padding:0;margin:0;cursor:pointer;">
				<a href="<?php echo admin_url() ?>/post.php?post=<?php echo $booking->getId() ?>&action=edit" target="_blank"  style="font-size:14px;line-height:20px;color:#ffffff;font-weight:bold;font-family: 'Avenir-Medium',sans-serif,arial;padding:0;margin:0;text-decoration:none;cursor:pointer;">
				    <?php _e('CONFIRM BOOKING', 'salon-booking-system'); ?>
				</a>
			    </td>
			</tr>
		    <?php endif ?>
		    <tr>
			<td align="center" valign="middle" height="48" bgcolor="#006dbc"  style="font-size:14px;line-height:20px;color:#ffffff;font-weight:bold;font-family: 'Avenir-Medium',sans-serif,arial;padding:0;margin:0;cursor:pointer;">
			    <a href="<?php echo admin_url() ?>/post.php?post=<?php echo $booking->getId() ?>&action=edit" target="_blank"  style="font-size:14px;line-height:20px;color:#ffffff;font-weight:bold;font-family: 'Avenir-Medium',sans-serif,arial;padding:0;margin:0;text-decoration:none;cursor:pointer;">
				<?php _e('MANAGE BOOKING', 'salon-booking-system'); ?>
			    </a>
			</td>
		    </tr>
		</tbody>
	    </table>
	</td>
    </tr>
</table>