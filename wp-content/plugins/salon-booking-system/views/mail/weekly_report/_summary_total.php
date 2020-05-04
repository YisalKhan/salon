<table width="100%" cellspacing="0" cellpadding="0" border="0">
    <tbody><tr>
	    <td style="border-right:1px solid #b6b6b6" class="pad1" width="192" valign="top" align="center">
		<table class="width1" width="156" cellspacing="0" cellpadding="0" border="0">
		    <tbody><tr>
			    <td valign="top" align="center">
				<table width="100%" cellspacing="0" cellpadding="0" border="0">
				    <tbody><tr>
					    <td style="font-size:1px;line-height:1px;" valign="top" height="26" align="center">&nbsp;</td>
					</tr>
					<tr>
					    <td style="font-size:14px;line-height:18px;color:#4d4d4d;font-weight:500;font-family: 'Avenir-Medium',sans-serif,arial;" class="font-total" valign="top" align="left">
						<?php _e("TOTAL RESERVATIONS", 'salon-booking-system') ?>
					    </td>
					</tr>
					<tr>
					    <td style="font-size:1px;line-height:1px;" valign="top" height="10" align="center">&nbsp;</td>
					</tr>
					<tr>
					    <td style="font-size:60px;line-height:65px;color:#004664;font-weight:900;font-family: 'Avenir-Medium',sans-serif,arial;text-align:center;" class="font-one" valign="top" align="left">
						<?php echo $stats['total']['count'] ?>
					    </td>
					</tr>
					<tr>
					    <td style="font-size:1px;line-height:1px;" valign="top" height="14" align="center">&nbsp;</td>
					</tr>
					<tr>
					    <td style="font-size:14px;line-height:16px;color:#4d4d4d;font-weight:500;font-family: 'Avenir-Medium',sans-serif,arial;text-align:center;" valign="top" align="left">
						<?php _e("CANCELLED", 'salon-booking-system') ?>
					    </td>
					</tr>
					<tr>
					    <td style="font-size:1px;line-height:1px;" valign="top" height="4" align="center">&nbsp;</td>
					</tr>
					<tr>
					    <td style="font-size:30px;line-height:33px;color:#bd1515;font-weight:900;font-family: 'Avenir-Medium',sans-serif,arial;text-align:center;" valign="top" align="left">
						<?php echo $stats['canceled'] ?>
					    </td>
					</tr>
					<tr>
					    <td style="font-size:1px;line-height:1px;" valign="top" height="14" align="center">&nbsp;</td>
					</tr>
				    </tbody></table>
			    </td>
			</tr>
		    </tbody></table>
	    </td>
	    <td style="border-right:1px solid #b6b6b6" class="pad1" width="192" valign="top" align="center">
		<table class="width2" width="194" cellspacing="0" cellpadding="0" border="0">
		    <tbody><tr>
			    <td valign="top" align="center">
				<table width="100%" cellspacing="0" cellpadding="0" border="0">
				    <tbody><tr>
					    <td style="font-size:1px;line-height:1px;" valign="top" height="27" align="center">&nbsp;</td>
					</tr>
					<tr>
					    <td style="font-size:14px;line-height:18px;color:#4d4d4d;font-weight:500;font-family: 'Avenir-Medium',sans-serif,arial;text-align:center;" class="font-total" valign="top" align="left">
						<?php _e("TOTAL AMOUNT", 'salon-booking-system') ?>
					    </td>
					</tr>
					<tr>
					    <td style="font-size:1px;line-height:1px;" valign="top" height="14" align="center">&nbsp;</td>
					</tr>
					<tr>
					    <td style="font-size:49px;line-height:51px;color:#1da23c;font-weight:900;font-family: 'Avenir-Medium',sans-serif,arial;text-align:center;letter-spacing: -2.2px;" class="font-two" valign="top" align="left">
						<?php echo $plugin->format()->money($stats['total']['amount'], false, false, true) ?>
					    </td>
					</tr>
					<tr>
					    <td style="font-size:1px;line-height:1px;" class="height1" valign="top" height="90" align="center">&nbsp;</td>
					</tr>
				    </tbody></table>
			    </td>
			</tr>
		    </tbody></table>
	    </td>
	    <td class="pad1" width="198" valign="top" align="center">
		<table class="width3" width="175" cellspacing="0" cellpadding="0" border="0">
		    <tbody><tr>
			    <td style="padding:0 12px 0 0;" class="no-padd" valign="top" align="center">
				<table width="100%" cellspacing="0" cellpadding="0" border="0">
				    <tbody><tr>
					    <td style="font-size:1px;line-height:1px;" valign="top" height="32" align="center">&nbsp;</td>
					</tr>
					<tr>
					    <td style="font-size:14px;line-height:18px;color:#4d4d4d;font-weight:500;font-family: 'Avenir-Medium',sans-serif,arial;text-align:center;letter-spacing: 0.1px;" class="font-total1" valign="top" align="center">
						<?php _e("PAID ONLINE", 'salon-booking-system') ?>
					    </td>
					</tr>
					<tr>
					    <td style="font-size:1px;line-height:1px;" valign="top" height="5" align="center">&nbsp;</td>
					</tr>
					<tr>
					    <td style="font-size:16px;line-height:20px;color:#ee5206;font-weight:900;font-family: 'Avenir-Medium',sans-serif,arial;text-align:center;padding: 0 13px 0 0;" valign="top" align="left">
						<?php echo $plugin->format()->money($stats['paid']['amount'], false, false, true) ?>
					    </td>
					</tr>
					<tr>
					    <td style="font-size:1px;line-height:1px;" class="height2" valign="top" height="29" align="center">&nbsp;</td>
					</tr>
					<tr>
					    <td style="font-size:14px;line-height:18px;color:#4d4d4d;font-weight:500;font-family: 'Avenir-Medium',sans-serif,arial;text-align:center;letter-spacing: 0.1px;padding: 0 13px 0 0;" class="font-total1" valign="top" align="left">
						<?php _e("PAID LATER", 'salon-booking-system') ?>
					    </td>
					</tr>
					<tr>
					    <td style="font-size:1px;line-height:1px;" valign="top" height="5" align="center">&nbsp;</td>
					</tr>
					<tr>
					    <td style="font-size:16px;line-height:20px;color:#ee5206;font-weight:900;font-family: 'Avenir-Medium',sans-serif,arial;text-align:center;padding: 0 13px 0 0;" valign="top" align="left">
						<?php echo $plugin->format()->money($stats['pay_later']['amount'], false, false, true) ?>
					    </td>
					</tr>
					<tr>
					    <td style="font-size:1px;line-height:1px;" valign="top" height="53" align="center">&nbsp;</td>
					</tr>
				    </tbody>
				</table>
			    </td>
			</tr>
		    </tbody>
		</table>
	    </td>
	</tr>
    </tbody>
</table>