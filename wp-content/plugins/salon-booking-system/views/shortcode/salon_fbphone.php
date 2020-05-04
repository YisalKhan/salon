<?php 
$plugin = SLN_Plugin::getInstance();
$bb = $plugin->getBookingBuilder();
$current     = $step->getShortcode()->getCurrentStep();
$ajaxEnabled = $plugin->getSettings()->isAjaxEnabled();
 ?>
    <form method="post" action="<?php echo $formAction ?>" role="form" id="salon-step-details">
        <h2 class="salon-step-title"> <?php _e('Please, insert your phone number.', 'salon-booking-system') ?> </h2>

                <div class="row">

    <div class="col-xs-12 field-phone">
       <?php $label = SLN_Enum_CheckoutFields::getLabel('phone');?>
		<label for="login_phone"><?php echo $label; ?></label>
                <?php if (($prefix = $plugin->getSettings()->get('sms_prefix'))): ?>
                    <div class="input-group sln-input-group">
                        <span class="input-group-addon sln-input--addon"><?php echo $prefix ?></span>
                <?php endif?>
                <?php

					SLN_Form::fieldText('login_phone', $bb->get('phone'), array('required' => 'true'));

if (isset($prefix)): ?>
                    </div>
                <?php endif?>
                <?php include "_form_actions.php" ?>
            </div>
            </div>
            <div class="row">
        <div class="col-xs-12"><?php include '_errors.php'; ?></div>
    </div>
</form>