<?php
/**
 * @var $confirmation bool
 * @var $plugin SLN_Plugin
 */
$genPhone = $plugin->getSettings()->get('gen_phone');
$genMail = $plugin->getSettings()->getSalonEmail();

$payOffsetEnabled = $plugin->getSettings()->get('pay_offset_enabled');
?>
<div class="sln-alert sln-alert--info <?php if ($confirmation) : ?> sln-alert--topicon<?php endif ?>">
    <?php if ($confirmation) : ?>
        <p><strong><?php _e(
                    'You will receive a confirmation of your booking by email.',
                    'salon-booking-system'
                ) ?></strong></p>
        <p><?php echo sprintf(
                __(
                    'If you don\'t receive any news from us or you need to change your reservation please call the %s or send an e-mail to %s',
                    'salon-booking-system'
                ),
                $genPhone,
                $genMail
            ); ?></p>
    <?php else : ?>
        <?php if ($paymentMethod && $payOffsetEnabled) : ?>
            <p><?php 
                $payLeftTime      = $plugin->getBookingBuilder()->getLastBooking()->getTimeStringToChangeStatusFromPending();
                echo sprintf(
                    __(
                        'You have <strong>%s</strong> to complete your payment before this reservation is canceled',
                        'salon-booking-system'
                    ),
                    $payLeftTime
                ); ?></p>
        <?php endif ?>
        <p><?php echo sprintf(
                __(
                        'You will receive a booking confirmation by email.If you do not receive an email in 5 minutes, check your Junk Mail or Spam Folder. If you need to change your reservation, please call <strong>%s</strong> or send an e-mail to <strong>%s</strong>.',
                    'salon-booking-system'
                ),
                $genPhone,
                $genMail
            ); ?>
        </p>
        <!-- form actions -->
    <?php endif ?>
</div>
