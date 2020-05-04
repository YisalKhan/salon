<?php
/**
 * @var $plugin SLN_Plugin
 * @var $helper SLN_Admin_Settings
 */
$daysBeforeRescheduling = $plugin->getSettings()->get('days_before_rescheduling');
?>

<h2 class="sln-box-title"><?php _e('Booking rescheduling','salon-booking-system');?></h2>
<div class="row">
    <div class="col-xs-12 col-sm-8 col-md-4 form-group sln-checkbox">
        <?php $helper->row_input_checkbox(
            'rescheduling_disabled',
            __('Disable reschedule', 'salon-booking-system'),
            array('help' => __('Select this option if you want disable the RESCHEDULE feature.','salon-booking-system'))
        ); ?>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-4 form-group sln-select ">
        <label><?php _e('Time in advance','salon-booking-system');?></label>
        <?php $field = "salon_settings[days_before_rescheduling]"; ?>
        <?php echo SLN_Form::fieldSelect(
            $field,
            array(
                '1'  => '1 ' . __('day', 'salon-booking-system'),
                '2'  => '2 ' . __('days', 'salon-booking-system'),
                '3'  => '3 ' . __('day', 'salon-booking-system'),
                '7'  => '1 ' . __('week', 'salon-booking-system'),
                '14' => '2 ' . __('weeks', 'salon-booking-system'),
            ),
            $daysBeforeRescheduling,
            array(),
            true
        ) ?>
        <p class="help-block"><?php _e('How many days before the appointment the rescheduling is still allowed', 'salon-booking-system') ?></p>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-4 sln-box-maininfo  align-top">
        <p class="sln-box-info"><?php _e('Users once logged in inside the MY ACCOUNT BOOKING page will be able to see the list of their upcoming confirmed or paid reservations and eventually RESCHEDULE them. An email notification will be sent to you and to the customers.','salon-booking-system');?></p>
    </div>
</div>