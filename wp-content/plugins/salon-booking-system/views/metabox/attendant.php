<?php
$helper->showNonce($postType);
/** @var SLN_Repository_ServiceRepository $sRepo */
$sRepo = $plugin->getRepository(SLN_Plugin::POST_TYPE_SERVICE);
$services = $sRepo->getAll();

usort($services, function ($service1, $service2) {

    $service1Title = strtolower($service1->getName());
    $service2Title = strtolower($service2->getName());

    if ($service1Title === $service2Title) {
	return 0;
    }

    return $service1Title > $service2Title ? 1 : -1;
});

?>
<div class="row sln-service-price-time">
    <div class="col-xs-12 col-sm-6 col-md-3 form-group sln-input--simple">
            <label for="_sln_attendant_email"><?php echo __('E-mail', 'salon-booking-system') ?></label>
            <input type="text" name="_sln_attendant_email" id="_sln_attendant_email" value="<?php echo $attendant->getEmail() ?>" class="form-control">
    </div>
    <div class="col-xs-12 col-sm-6 col-md-3 form-group sln-select">
            <label for="_sln_attendant_phone"><?php echo __('Phone', 'salon-booking-system') ?></label>
            <input type="text" name="_sln_attendant_phone" id="_sln_attendant_phone" value="<?php echo $attendant->getPhone() ?>" class="form-control">
    </div>

    <div class="col-xs-12 col-md-6 form-group sln-select sln-select--multiple sln-select2-selection__search-primary">
            <label><?php echo __('Limit reservations to the following services', 'salon-booking-system') ?></label>
            <select class="sln-select select2-hidden-accessible" multiple="multiple" data-placeholder="<?php _e('Select or search one or more services', 'salon-booking-system')?>"
                    name="_sln_attendant_services[]" id="_sln_attendant_services" tabindex="-1" aria-hidden="true">
                <?php foreach ($services as $service) : ?>
                    <?php if (!$service->isAttendantsEnabled()) continue; ?>
                    <option
                        class="red"
                        value="sln_attendant_services_<?php echo $service->getId() ?>"
                        data-price="<?php echo $service->getPrice(); ?>"
                        <?php echo $attendant->hasService($service) ? 'selected="selected"' : '' ?>
                        ><?php echo $service->getName(); ?>
                        (<?php echo $plugin->format()->money($service->getPrice()) ?>)
                    </option>
                <?php endforeach ?>
            </select>
            <p><?php echo __('Use this option only if this assistant is able to provide specific services. If not leave it blank', 'salon-booking-system') ?></p>
    </div>
</div>
<div class="row sln-service-price-time">
    <div class="col-xs-12 col-md-6 form-group sln-checkbox">
        <?php SLN_Form::fieldCheckbox('_sln_attendant_multiple_customers', $attendant->canMultipleCustomers(), array()) ?>
        <label for="_sln_attendant_multiple_customers"><?php _e('Multiple Customers per Session', 'salon-booking-system'); ?></label>
        <!--<p><?php //_e('Select this if you want this service considered as secondary level service','salon-booking-system'); ?></p>-->
    </div>
</div>
<?php echo $plugin->loadView(
    'settings/_tab_booking_rules',
    array(
        'availabilities' => $attendant->getMeta('availabilities'),
        'base' => '_sln_attendant_availabilities',
    )
); ?>
<?php echo $plugin->loadView(
    'settings/_tab_booking_holiday_rules',
    array(
        'holidays' => $attendant->getMeta('holidays'),
        'base' => '_sln_attendant_holidays',
    )
); ?>

<div class="sln-clear"></div>
<?php do_action('sln.template.attendant.metabox',$attendant); ?>
