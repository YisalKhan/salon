
<?php   // algolplus
    $feedback_url = home_url() . '?sln_customer_login=' . $customer->getHash() . '&feedback_id=' . $booking->getId();
?>

<tr>
    <td align="left" valign="top" style="font-size:18px;line-height:29px;color:#4d4d4d;font-weight:500;font-family: 'Avenir-Medium',sans-serif,arial;padding: 0 0 0 8px;" class="font1">
	<?php

	    $msg = "Hi [NAME],
		thank you for visiting us at the shop [DATE].
		We would be very happy to hear from you how was your experience at <b>[SALON NAME].</b>\n
		Plase take two minutes to send us a quick private review.";

	    $msg = __( $msg , 'salon-booking-system');

	    $customer_name = implode(' ', array_filter(array(
		SLN_Enum_CheckoutFields::isHidden('firstname') ? '' : $booking->getFirstname(),
		SLN_Enum_CheckoutFields::isHidden('lastname') ? '' : $booking->getLastname(),
	    )));

	    $msg = str_replace(array('[NAME]', '[SALON NAME]', '[DATE]'), array($customer_name, $plugin->getSettings()->getSalonName(), $plugin->format()->date($booking->getDate())), $msg);
	    $msg = nl2br($msg);

	    echo $msg;
	?>
    </td>
</tr>
<tr>
    <td align="center" valign="top" height="22" style="font-size:1px;line-height:1px;">&nbsp;</td>
</tr>
<tr>
    <td align="left" valign="top" style="font-size:18px;line-height:29px;color:#4d4d4d;font-weight:500;font-family: 'Avenir-Medium',sans-serif,arial;padding: 0 0 0 8px;" class="font1">
	 <?php _e('Click on this button to send us your feedback', 'salon-booking-system'); ?> <br/>
	 <a href="<?php echo $feedback_url ?>"style="
	    text-transform: uppercase;
	    display: inline-block;
	    padding: 10px 20px;
	    margin-bottom: 0;
	    font-size: 12px;
	    font-weight: 400;
	    line-height: 1.42857143;
	    text-align: center;
	    white-space: nowrap;
	    vertical-align: middle;
	    -ms-touch-action: manipulation;
	    touch-action: manipulation;
	    cursor: pointer;
	    -webkit-user-select: none;
	    -moz-user-select: none;
	    -ms-user-select: none;
	    user-select: none;
	    background-image: none;
	    border: 1px solid transparent;
	    border-radius: 3px;
	    color: #fff;
	    background-color: #0d569f;
	    text-decoration: none;"><?php _e('Submit a review','salon-booking-system'); ?></a>
    </td>
</tr>
<tr>
    <td align="center" valign="top" height="22" style="font-size:1px;line-height:1px;">&nbsp;</td>
</tr>