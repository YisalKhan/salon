<?php
    $updated_message = __('Reservation at [SALON NAME] has been modified', 'salon-booking-system');
    $updated_message = str_replace('[SALON NAME]', '<b style="color:#666666;">' . $plugin->getSettings()->getSalonName() . '</b>', $updated_message);
?>

<tr>
    <td align="left" valign="top" style="font-size:18px;line-height:20px;color:#4d4d4d;font-family: 'Avenir-Medium',sans-serif,arial;padding: 0 0 0 8px;">
	<?php echo $updated_message ?>.
    </td>
</tr>
<tr>
    <td align="center" valign="top" height="5" style="font-size:1px;line-height:1px;">&nbsp;</td>
</tr>