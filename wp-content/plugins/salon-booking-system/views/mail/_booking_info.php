<table width="100%" cellspacing="0" cellpadding="0" border="0">
    <tr>
	<td align="left" valign="top" style="font-size:16px;line-height:18px;color:#4d4d4d;font-weight:500;font-family: 'Avenir-Medium',sans-serif,arial;">
	    <?php echo __('Booking id', 'salon-booking-system') ?>:
	    <span style="font-family: 'Avenir-Medium',sans-serif,arial;font-weight:bold;">
		<?php echo $booking->getId() ?>
	    </span>
	</td>
    </tr>
    <tr>
	<td align="center" valign="top" height="20" style="font-size:1px;line-height:1px;" class="height1">&nbsp;</td>
    </tr>
    <tr>
	<td align="left" valign="top" style="font-size:16px;line-height:18px;color:#4d4d4d;font-weight:500;font-family: 'Avenir-Medium',sans-serif,arial;">
	    <?php echo __('Status', 'salon-booking-system') ?>: <span style="font-family: 'Avenir-Medium',sans-serif,arial;font-weight:bold; font-size:12px;"><?php echo strtoupper(SLN_Enum_BookingStatus::getLabel($booking->getStatus())) ?></span>
	</td>
    </tr>
</table>