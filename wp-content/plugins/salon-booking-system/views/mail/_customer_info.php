<table width="194" cellspacing="0" cellpadding="0" border="0">
    <tr>
	<td align="center" valign="top" height="8" style="font-size:1px;line-height:1px;">&nbsp;</td>
    </tr>
    <tr>
	<td align="left" valign="top" style="font-size:16px;line-height:20px;color:#4d4d4d;font-weight:bold;font-family: 'Avenir-Medium',sans-serif,arial;padding: 0 0 0 9px;" class="pad0">
	    <?php echo __('Customer details', 'salon-booking-system') ?>:
	</td>
    </tr>
    <tr>
	<td align="center" valign="top" height="24" style="font-size:1px;line-height:1px;">&nbsp;</td>
    </tr>
    <?php if (!SLN_Enum_CheckoutFields::isHidden('firstname') || !SLN_Enum_CheckoutFields::isHidden('lastname')): ?>
    <tr>
	<td align="left" valign="top" style="font-size:14px;line-height:20px;color:#4d4d4d;font-weight:500;font-family: 'Avenir-Medium',sans-serif,arial;padding: 0 0 0 9px;" class="pad0">
	    <?php
		echo implode(' ', array_filter(array(
		    SLN_Enum_CheckoutFields::isHidden('firstname') ? '' : $booking->getFirstname(),
		    SLN_Enum_CheckoutFields::isHidden('lastname') ? '' : $booking->getLastname(),
		)));
	    ?>
	</td>
    </tr>
    <?php endif; ?>
    <tr>
	<td align="left" valign="top" style="font-size:14px;line-height:24px;color:#4d4d4d;font-weight:500;font-family: 'Avenir-Medium',sans-serif,arial;padding: 0 0 0 9px;" class="pad0">
	    <a href="mailto:<?php echo $booking->getEmail() ?>" target="_blank" style="text-decoration:none;color:#4d4d4d;">
		<?php echo $booking->getEmail() ?>
	    </a>
	</td>
    </tr>
    <?php if (!SLN_Enum_CheckoutFields::isHidden('phone')): ?>
	<tr>
	    <td align="left" valign="top" style="font-size:14px;line-height:20px;color:#4d4d4d;font-weight:500;font-family: 'Avenir-Medium',sans-serif,arial;padding: 0 0 0 9px;" class="pad0">
		<a href="tel:<?php echo $booking->getPhone() ?>" target="_blank" style="text-decoration:none;color:#4d4d4d;">
		    <?php echo $booking->getPhone() ?>
		</a>
	    </td>
	</tr>
    <?php endif; ?>
    <?php if (!SLN_Enum_CheckoutFields::isHidden('address')): ?>
	<tr>
	    <td align="left" valign="top" style="font-size:14px;line-height:20px;color:#4d4d4d;font-weight:500;font-family: 'Avenir-Medium',sans-serif,arial;padding: 0 0 0 9px;" class="pad0">
		<?php echo $booking->getAddress() ?>
	    </td>
	</tr>
    <?php endif; ?>
    <tr>
	<td align="center" valign="top" height="18" style="font-size:1px;line-height:1px;">&nbsp;</td>
    </tr>
    <tr>
	<td align="left" valign="top" style="font-size:16px;line-height:20px;color:#4d4d4d;font-weight:500;font-family: 'Avenir-Medium',sans-serif,arial;padding: 0 0 0 9px;" class="pad0">
	    <?php echo __('Customer notes', 'salon-booking-system') ?>:
	</td>
    </tr>
    <tr>
	<td align="center" valign="top" height="17" style="font-size:1px;line-height:1px;">&nbsp;</td>
    </tr>
    <tr>
	<td align="left" valign="top" style="font-size:13px;line-height:20px;color:#4d4d4d;font-weight:bold;font-family: 'Avenir-Medium',sans-serif,arial;padding: 0 0 0 9px;" class="pad0">
	    <?php echo esc_attr($booking->getNote()) ?>
	</td>
    </tr>
</table>