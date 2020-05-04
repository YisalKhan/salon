<table width="148" cellspacing="0" cellpadding="0" border="0">
    <?php if($booking->hasStatus(SLN_Enum_BookingStatus::PENDING_PAYMENT)): ?>
	<tr>
	    <td align="center" valign="top">
		<table width="148" border="0" cellpadding="0" cellspacing="0">
		    <tbody>
			<tr>
			    <td align="center" valign="middle" height="48" bgcolor="#006dbc"  style="font-size:14px;line-height:20px;color:#ffffff;font-weight:bold;font-family: 'Avenir-Medium',sans-serif,arial;padding:0;margin:0;cursor:pointer;">
				<a href="<?php echo $booking->getPayUrl() ?>" target="_blank"  style="font-size:14px;line-height:20px;color:#ffffff;font-weight:bold;font-family: 'Avenir-Medium',sans-serif,arial;padding:0;margin:0;text-decoration:none;cursor:pointer;">
				    <?php _e('PAY NOW', 'salon-booking-system'); ?>
				    <?php if($booking->getDeposit()): ?>
					(<?php echo $plugin->format()->moneyFormatted($booking->getDeposit()) ?>)
				    <?php else: ?>
					(<?php echo $plugin->format()->moneyFormatted($booking->getAmount()) ?>)
				    <?php endif ?>
				</a>
			    </td>
			</tr>
		    </tbody>
		</table>
	    </td>
	</tr>
	<tr>
	    <td align="center" valign="top" height="32" style="font-size:1px;line-height:1px;">&nbsp;</td>
	</tr>
    <?php endif; ?>

    <?php $customer = isset($customer) && $customer ? $customer : $booking->getCustomer(); ?>

    <tr>
	<td align="center" valign="top">
	    <table width="148" border="0" cellpadding="0" cellspacing="0">
		<tbody>
		<?php if(is_object($customer) && $plugin->getSettings()->getBookingmyaccountPageId()){ ?>
		    <tr>
			<td align="center" valign="middle" height="48" bgcolor="#cdcdcd"  style="font-size:12px;line-height:20px;color:#575757;font-weight:bold;font-family: 'Avenir-Medium',sans-serif,arial;padding:0;margin:0;cursor:pointer;">
			    <a href="<?php echo home_url() . '?sln_customer_login=' . $customer->getHash(); ?>" target="_blank"  style="font-size:12px;line-height:20px;color:#575757;font-weight:bold;font-family: 'Avenir-Medium',sans-serif,arial;padding:0;margin:0;text-decoration:none;cursor:pointer;">
				<?php _e('MANAGE ACCOUNT', 'salon-booking-system'); ?>
			    </a>
			</td>
		    </tr>
		<?php } ?>
		    <tr>
			<td align="center" valign="top" height="32" style="font-size:1px;line-height:1px;">&nbsp;</td>
		    </tr>
		    <?php if($plugin->getSettings()->get('cancellation_enabled') && !$booking->hasStatus(SLN_Enum_BookingStatus::CANCELED)): ?>
			<tr>
			    <td align="center" valign="top">
				<table width="148" border="0" cellpadding="0" cellspacing="0">
				    <tbody>
					<tr>
					    <td align="center" valign="middle" height="48" bgcolor="#ff0000"  style="font-size:12px;line-height:20px;color:#fefefe;font-weight:bold;font-family: 'Avenir-Medium',sans-serif,arial;padding:0;margin:0;cursor:pointer;">
						<a href="<?php echo $booking->getCancelUrl(); ?>" target="_blank"  style="font-size:12px;line-height:20px;color:#fefefe;font-weight:bold;font-family: 'Avenir-Medium',sans-serif,arial;padding:0;margin:0;text-decoration:none;cursor:pointer;">
						    <?php _e('CANCEL BOOKING', 'salon-booking-system'); ?>
						</a>
					    </td>
					</tr>
				    </tbody>
				</table>
			    </td>
			</tr>
		    <?php endif; ?>
		</tbody>
	    </table>
	</td>
    </tr>
</table>