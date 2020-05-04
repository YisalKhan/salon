<tr>
    <td align="left" valign="top" style="font-size:16px;line-height:24px;color:#4d4d4d;font-weight:500;font-family: 'Avenir-Medium',sans-serif,arial;padding: 10px 0 0 8px;" class="font1">
	<?php
	    $msg = $plugin->getSettings()->get('follow_up_message');
	    $msg = str_replace(array('[NAME]', '[SALON NAME]'), array($customer->getName(), $plugin->getSettings()->getSalonName()), $msg);
	    $msg = nl2br($msg);

	    echo $msg;
	?>
    </td>
</tr>
<tr>
    <td align="center" valign="top" height="22" style="font-size:1px;line-height:1px;">&nbsp;</td>
</tr>

