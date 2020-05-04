 <?php

    $additional_fields = SLN_Enum_CheckoutFields::toArray('additional',false);

    $_additional_fields = array();

    $customer = $booking->getCustomer();

    $customer_fields = array_keys(SLN_Enum_CheckoutFields::toArray('customer-not-hidden'));

    foreach ($additional_fields as $field => $settings) {

	$is_customer_field = in_array($field, $customer_fields);

	$value = $is_customer_field  ? (
                !empty($customer) && !is_null($customer->getMeta($field))? $customer->getMeta($field)  : ( null !== $settings['default'] && $settings['type'] !== 'checkbox' ? $settings['default'] : '')
            ) : (
                !is_null($booking->getMeta($field))? $booking->getMeta($field)  : ( null !== $settings['default'] && $settings['type'] !== 'checkbox' ? $settings['default'] : '')
            );

	if(SLN_Enum_CheckoutFields::isHidden($field) || empty($value) ) {
	    continue;
	}

	$_additional_fields[] = array(
	    'label' => $settings['label'],
	    'value' => $value,
	);
    }

?>

<?php if($_additional_fields): ?>
    <table width="198" cellspacing="0" cellpadding="0" border="0" class="width">
	<?php foreach ($_additional_fields as $field): ?>
	    <tr>
		<td align="center" valign="top" height="54" style="font-size:1px;line-height:1px;" class="height0">&nbsp;</td>
	    </tr>
	    <tr>
		<td align="left" valign="top" style="font-size:14px;line-height:17px;color:#4d4d4d;font-weight:500;font-family: 'Avenir-Medium',sans-serif,arial;">
		    <?php echo $field['label'] ?>
		</td>
	    </tr>
	    <tr>
		<td align="left" valign="top" style="font-size:14px;line-height:20px;color:#4d4d4d;font-weight:bold;font-family: 'Avenir-Medium',sans-serif,arial;">
		    <?php echo $field['value'] ?>
		</td>
	    </tr>
	<?php endforeach; ?>
    </table>
<?php endif; ?>
