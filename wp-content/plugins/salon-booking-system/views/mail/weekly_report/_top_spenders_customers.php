<table width="100%" cellspacing="0" cellpadding="0" border="0">
    <tbody>
	<tr>
	    <td style="font-size:1px;line-height:1px;" class="height5" valign="top" height="43" align="center">&nbsp;</td>
	</tr>
	<tr>
	    <td style="font-size:12px;line-height:14px;color:#4d4d4d;font-weight:900;font-family: 'Avenir-Medium',sans-serif,arial;text-transform:uppercase;" valign="top" align="left">
		<?php _e("Top spenders customers", 'salon-booking-system') ?>:
	    </td>
	</tr>

	<?php if (!empty($stats['customers'])) : ?>

	    <?php $i = 1 ?>

	    <?php foreach($stats['customers'] as $customerID => $amount): ?>
		<tr>
		    <td style="font-size:1px;line-height:1px;" class="height4" valign="top" height="20" align="center">&nbsp;</td>
		</tr>
		<tr>
		    <td style="font-size:16px;line-height:20px;color:#4d4d4d;font-weight:500;font-family: 'Avenir-Medium',sans-serif,arial;" valign="top" align="left">
			<?php echo $i ?> ) <?php echo (new SLN_Wrapper_Customer($customerID))->getName() ?> ( <?php echo $plugin->format()->money($amount, false, false, true) ?> )
		    </td>
		</tr>
		<?php $i++ ?>
		<?php
		    if ($i > 5) {
			break;
		    }
		?>
	    <?php endforeach; ?>

	<?php else: ?>

	    <tr>
		<td style="font-size:1px;line-height:1px;" class="height4" valign="top" height="20" align="center">&nbsp;</td>
	    </tr>
	    <tr>
		<td style="font-size:18px;line-height:20px;color:#4d4d4d;font-weight:500;font-family: 'Avenir-Medium',sans-serif,arial;" valign="top" align="left">
		    -
		</td>
	    </tr>

	<?php endif; ?>

	<tr>
	    <td style="font-size:1px;line-height:1px;" class="height4" valign="top" height="36" align="center">&nbsp;</td>
	</tr>
    </tbody>
</table>