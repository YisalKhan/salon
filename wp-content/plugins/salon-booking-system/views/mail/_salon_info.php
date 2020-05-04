<table width="100%" cellspacing="0" cellpadding="0" border="0">
    <tr>
	<td align="left" valign="top" style="font-size:14px;line-height:18px;color:#4d4d4d;font-weight:bold;font-family: 'Avenir-Medium',sans-serif,arial;">
	    <?php echo $plugin->getSettings()->getSalonName() ?>
	</td>
    </tr>
    <tr>
	<td align="center" valign="top" height="2" style="font-size:1px;line-height:1px;">&nbsp;</td>
    </tr>
    <tr>
	<td align="left" valign="top" style="font-size:14px;line-height:18px;color:#979797;font-weight:500;font-family: 'Avenir-Medium',sans-serif,arial;">
	    <?php echo $plugin->getSettings()->get('gen_address') ?>
	</td>
    </tr>
    <tr>
	<td align="center" valign="top" height="11" style="font-size:1px;line-height:1px;">&nbsp;</td>
    </tr>
    <tr>
	<td align="left" valign="top" style="font-size:14px;line-height:18px;color:#979797;font-weight:500;font-family: 'Avenir-Medium',sans-serif,arial;">
	    <a href="tel:<?php echo $plugin->getSettings()->get('gen_phone') ?>" target="_blank" style="text-decoration:underline;color:#979797;"><?php echo $plugin->getSettings()->get('gen_phone') ?></a>
	</td>
    </tr>
    <tr>
	<td align="center" valign="top" height="4" style="font-size:1px;line-height:1px;">&nbsp;</td>
    </tr>
    <tr>
	<td align="left" valign="top" style="font-size:14px;line-height:18px;color:#979797;font-weight:500;font-family: 'Avenir-Medium',sans-serif,arial;">
	    <a href="mailto:<?php echo $plugin->getSettings()->getSalonEmail() ?>" target="_blank" style="text-decoration:underline;color:#979797;"><?php echo $plugin->getSettings()->getSalonEmail() ?></a>
	</td>
    </tr>
</table>