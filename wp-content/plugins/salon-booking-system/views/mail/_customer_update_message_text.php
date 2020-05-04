<?php

    $updated_message = isset($updated_message) && !empty($updated_message) ? $updated_message : $plugin->getSettings()->get('booking_update_message');

    $updated_message = str_replace(
	array('[NAME]', '[SALON NAME]', '\\\\r\\\\n', '\\r\\n', '\\\\n', '\\n'), array(
	    ($customer = $booking->getCustomer()) ? $customer->getName() : '',
	    $plugin->getSettings()->get('gen_name') ? $plugin->getSettings()->get('gen_name') : get_bloginfo('name'),
	    '<br/>',
	    '<br/>',
	    '<br/>',
	    '<br/>'
	),
	$updated_message
    );
?>

<tr>
    <td align="left" valign="top" style="font-size:16px;line-height:24px;color:#4d4d4d;font-family: 'Avenir-Medium',sans-serif,arial;padding: 10px 0 20px 18px;">
	<?php echo $updated_message . '' ?>
    </td>
</tr>
<tr>
    <td align="center" valign="top" height="5" style="font-size:1px;line-height:1px;">&nbsp;</td>
</tr>