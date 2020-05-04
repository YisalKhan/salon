
<?php $_remind_message = $plugin->getSettings()->get('booking_update_message'); ?>

<?php if ($_remind_message): ?>

    <?php
	$_remind_message = str_replace(
	    array('[NAME]', '[SALON NAME]', '\\\\r\\\\n', '\\r\\n', '\\\\n', '\\n', "\r\n", "\n"),
	    array(
		($customer = $booking->getCustomer()) ? $customer->getName() : '',
		'<b style="color:#666666;">' . $plugin->getSettings()->getSalonName() . '</b>',
		'<br/>',
		'<br/>',
		'<br/>',
		'<br/>',
		'<br/>',
		'<br/>',
	    ),
	    $_remind_message
	);
    ?>

<?php else: ?>

    <?php
    	$_remind_message = __('Reminder: Your booking at ', 'salon-booking-system') . '<b style="color:#666666;">' . $plugin->getSettings()->getSalonName() . '.</b>';
    ?>

<?php endif; ?>

<tr>
    <td align="left" valign="top" style="font-size:16px;line-height:24px;color:#4d4d4d;font-weight:500;font-family: 'Avenir-Medium',sans-serif,arial;padding: 10px 0 20px 8px;" class="font1">
	<?php echo $_remind_message ?>
    </td>
</tr>
<tr>
    <td align="center" valign="top" height="22" style="font-size:1px;line-height:1px;">&nbsp;</td>
</tr>

