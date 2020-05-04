<?php
$showPrices = !$plugin->getSettings()->isHidePrices();
/** @var SLN_Wrapper_Booking $booking */
$depositText = ($booking->getDeposit() && $booking->hasStatus(SLN_Enum_BookingStatus::PAID)) ?
    $plugin->format()->moneyFormatted($booking->getDeposit()) : null;
?>

<tr>
    <td align="center" valign="top" style="border:1px solid #b6b6b6">
	<table width="100%" cellspacing="0" cellpadding="0" border="0">
	    <tr>
		<td align="center" valign="top" width="192" style="border-right:1px solid #b6b6b6" class="pad1">
		    <table width="127" cellspacing="0" cellpadding="0" border="0">
			<tr>
			    <td align="center" valign="top">
				<table width="100%" cellspacing="0" cellpadding="0" border="0">
				    <tr>
					<td align="center" valign="top" height="11" style="font-size:1px;line-height:1px;">&nbsp;</td>
				    </tr>
				    <tr>
					<td align="left" valign="top" style="font-size:14px;line-height:18px;color:#4d4d4d;font-weight:500;font-family: 'Avenir-Medium',sans-serif,arial;">
					    <?php echo __('Date & Time', 'salon-booking-system') ?>
					</td>
				    </tr>
				    <tr>
					<td align="center" valign="top" height="11" style="font-size:1px;line-height:1px;">&nbsp;</td>
				    </tr>
				    <tr>
					<td align="left" valign="top" style="font-size:16px;line-height:20px;color:#4d4d4d;font-weight:bold;font-family: 'Avenir-Medium',sans-serif,arial;">
					    <?php echo __('on', 'salon-booking-system') ?> <?php echo $plugin->format()->date($booking->getDate()); ?>
					</td>
				    </tr>
				    <tr>
					<td align="center" valign="top" height="5" style="font-size:1px;line-height:1px;">&nbsp;</td>
				    </tr>
				    <tr>
					<td align="left" valign="top" style="font-size:16px;line-height:20px;color:#4d4d4d;font-weight:bold;font-family: 'Avenir-Medium',sans-serif,arial;">
					    <?php echo __('at', 'salon-booking-system') ?> <?php echo $plugin->format()->time($booking->getTime()) ?>
					</td>
				    </tr>
				</table>
			    </td>
			</tr>
		    </table>
		</td>
		<td align="center" valign="top" width="192" style="border-right:1px solid #b6b6b6" class="pad1">
		    <table width="167" cellspacing="0" cellpadding="0" border="0">
			<tr>
			    <td align="center" valign="top">
				<table width="100%" cellspacing="0" cellpadding="0" border="0">
				    <tr>
					<td align="center" valign="top" height="11" style="font-size:1px;line-height:1px;">&nbsp;</td>
				    </tr>
				    <tr>
					<td align="left" valign="top" style="font-size:14px;line-height:18px;color:#4d4d4d;font-weight:500;font-family: 'Avenir-Medium',sans-serif,arial;">
					    <?php echo __('Services & Assistants', 'salon-booking-system') ?>
					</td>
				    </tr>
				    <tr>
					<td align="center" valign="top" height="11" style="font-size:1px;line-height:1px;">&nbsp;</td>
				    </tr>
				    <?php foreach($booking->getBookingServices()->getItems() as $bookingService): ?>
					<?php if ($bookingService->getService()->getServiceCategory()): ?>
                                            <tr>
                                                <td align="left" valign="top" style="font-size:16px;line-height:18px;color:#979797;font-family: 'Avenir-Medium',sans-serif,arial;">
                                                    <?php echo $bookingService->getService()->getServiceCategory()->getName(); ?>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
					<tr>
					    <td align="left" valign="top" style="font-size:16px;line-height:20px;color:#4d4d4d;font-weight:bold;font-family: 'Avenir-Medium',sans-serif,arial;">
						<?php echo $bookingService->getService()->getName(); ?>
					    </td>
					</tr>
					<?php if ($bookingService->getAttendant()): ?>
					    <tr>
						<td align="left" valign="top" style="font-size:16px;line-height:18px;color:#4d4d4d;font-weight:500;font-family: 'Avenir-Medium',sans-serif,arial;">
						    ( <?php echo $bookingService->getAttendant()->getName() ?> )
						</td>
					    </tr>
					<?php endif; ?>
					<tr>
					    <td align="center" valign="top" height="7" style="font-size:1px;line-height:1px;">&nbsp;</td>
					</tr>
				    <?php endforeach ?>
				</table>
			    </td>
			</tr>
		    </table>
		</td>
		<td align="center" valign="top" width="198" class="pad1">
		    <table width="175" cellspacing="0" cellpadding="0" border="0">
			<tr>
			    <td align="center" valign="top">
				<table width="100%" cellspacing="0" cellpadding="0" border="0">
				    <tr>
					<td align="center" valign="top" height="11" style="font-size:1px;line-height:1px;">&nbsp;</td>
				    </tr>
				    <?php if ($showPrices): ?>
					<tr>
					    <td align="left" valign="top" style="font-size:14px;line-height:18px;color:#4d4d4d;font-weight:500;font-family: 'Avenir-Medium',sans-serif,arial;">
						<?php echo __('Total amount', 'salon-booking-system') ?>
					    </td>
					</tr>
					<tr>
					    <td align="center" valign="top" height="11" style="font-size:1px;line-height:1px;">&nbsp;</td>
					</tr>
					<tr>
					    <td align="left" valign="top" style="font-size:16px;line-height:20px;color:#4d4d4d;font-weight:bold;font-family: 'Avenir-Medium',sans-serif,arial;">
						<?php echo $plugin->format()->moneyFormatted($booking->getAmount()) ?>
					    </td>
					</tr>
					<tr>
					    <td align="center" valign="top" height="66" style="font-size:1px;line-height:1px;" class="height2">&nbsp;</td>
					</tr>
				    <?php endif; ?>
				    <?php if ($depositText): ?>
					<tr>
					    <td align="left" valign="top" style="font-size:14px;line-height:18px;color:#4d4d4d;font-weight:500;font-family: 'Avenir-Medium',sans-serif,arial;">
						<?php echo __('Already paid', 'salon-booking-system') ?>
					    </td>
					</tr>
					<tr>
					    <td align="center" valign="top" height="5" style="font-size:1px;line-height:1px;">&nbsp;</td>
					</tr>
					<tr>
					    <td align="left" valign="top" style="font-size:16px;line-height:20px;color:#4d4d4d;font-weight:bold;font-family: 'Avenir-Medium',sans-serif,arial;">
						<?php echo $depositText ?>
					    </td>
					</tr>
				    <?php endif; ?>
				</table>
			    </td>
			</tr>
		    </table>
		</td>
	    </tr>
	</table>
    </td>
</tr>