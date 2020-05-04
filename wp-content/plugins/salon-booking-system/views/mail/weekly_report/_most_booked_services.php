<table width="100%" cellspacing="0" cellpadding="0" border="0">
    <tbody>
	<tr>
	    <td style="font-size:12px;line-height:14px;color:#4d4d4d;font-weight:900;font-family: 'Avenir-Medium',sans-serif,arial;text-transform:uppercase;" valign="top" align="left">
		<?php _e("Most booked services", 'salon-booking-system') ?>:
	    </td>
	</tr>

	<?php if (!empty($stats['services'])) : ?>

	    <?php $i = 1 ?>

	    <?php foreach($stats['services'] as $sID => $count): ?>
		<tr>
		    <td style="font-size:1px;line-height:1px;" class="height4" valign="top" height="20" align="center">&nbsp;</td>
		</tr>
		<tr>
		    <td style="font-size:16px;line-height:20px;color:#4d4d4d;font-weight:500;font-family: 'Avenir-Medium',sans-serif,arial;" valign="top" align="left">
			<?php echo $i ?> ) <?php echo $plugin->createService($sID)->getName() ?> ( <?php echo $count ?> )
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
	    <td style="font-size:1px;line-height:1px;" class="height4" valign="top" height="31" align="center">&nbsp;</td>
	</tr>
    </tbody>
</table>