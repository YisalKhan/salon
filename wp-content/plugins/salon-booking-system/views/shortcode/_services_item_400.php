<label for="<?php echo SLN_Form::makeID('sln[services][' . $service->getId() . ']') ?>" class="row sln-service sln-service--<?php echo $service->getId(); ?>">
	<div class="col-xs-12 sln-service__header">
        <div class="row sln-steps-info sln-service-info">
            <div class="col-xs-2 col-xs-push-10 sln-checkbox sln-steps-check sln-service-check">
                <?php /*
                        <span class="service-checkbox <?php echo  $bb->hasService($service) ? 'is-checked' : '' ?>">
                        </span>
                        */ ?>
                <div class="sln-checkbox">
                    <?php SLN_Form::fieldCheckbox(
                        'sln[services][' . $service->getId() . ']',
                        $bb->hasService($service),
                        $settings
                    ) ?>
            <label for="<?php echo SLN_Form::makeID('sln[services][' . $service->getId() . ']') ?>"></label>
                </div>
                <!-- .sln-service-check // END -->
            </div>
		<?php
		    $thumb = has_post_thumbnail($service->getId()) ? get_the_post_thumbnail(
			$service->getId(),
			'thumbnail'
		    ) : '';
		?>

		<?php if ($thumb): ?>
            <div class="col-xs-10 col-xs-pull-2">
                <h3 class="sln-steps-name sln-service-name"><?php echo $service->getName(); ?></h3>
            </div>
            <div class="col-xs-10">
			<?php if($showPrices): ?>
			            <h3 class="sln-steps-price sln-service-price">
			                <?php echo $plugin->format()->moneyFormatted($service->getPrice())?>
			                <!-- .sln-service-price // END -->
			            </h3>
			<?php endif ?>
            </div>
		<?php else: ?>
            <div class="col-xs-10 col-xs-pull-2">
                <h3 class="sln-steps-name sln-service-name"><?php echo $service->getName(); ?></h3>
            </div>
            <div class="col-xs-10">
<?php if($showPrices): ?>
            <h3 class="sln-steps-price sln-service-price">
                <?php echo $plugin->format()->moneyFormatted($service->getPrice())?>
                <!-- .sln-service-price // END -->
            </h3>
<?php endif ?>
            </div>
		<?php endif; ?>
        <?php if ($thumb): ?>
            <div class="col-xs-10  col-sm-push-0 sln-steps-thumb sln-service-thumb">
                <?php echo $thumb ?>
            </div>
        <?php else: ?>
        <?php endif; ?>
                <!-- .sln-service-info // END -->
        </div>
    </div>
    <div class="col-xs-12">
        <div class="row sln-steps-description sln-service-description">
            <div class="col-xs-12">
                    <p><?php echo $service->getContent() ?></p>
                    <?php if ($service->getDuration()->format('H:i') != '00:00'): ?>
                        <span class="sln-steps-duration sln-service-duration"><small><?php echo __('Duration', 'salon-booking-system')?>:</small> <?php echo $service->getDuration()->format(
                                'H:i'
                            ) ?></span>
                    <?php endif ?>
                <!-- .sln-service-info // END -->
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-xs-12">
                    <span class="errors-area" data-class="sln-alert sln-alert-medium sln-alert--problem">
                        <?php if($serviceErrors) foreach ($serviceErrors as $error): ?>
                            <div class="sln-alert sln-alert-medium sln-alert--problem"><?php echo $error ?></div>
                        <?php endforeach ?>
                    </span>
            <div class="sln-alert sln-alert-medium sln-alert--problem" style="display: none" id="availabilityerror"><?php _e('Not enough time for this service','salon-booking-system') ?></div>
        </div>
    </div>
    <div class="sln-service__fkbkg"></div>
</label>
