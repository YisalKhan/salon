<?php
$current_user = wp_get_current_user();
$plugin       = SLN_Plugin::getInstance();
$values       = array(
    'firstname' => $current_user->user_firstname,
    'lastname'  => $current_user->user_lastname,
    'email'     => $current_user->user_email,
    'phone'     => get_user_meta($current_user->ID, '_sln_phone', true),
    'address'   => get_user_meta($current_user->ID, '_sln_address', true),
);
foreach (SLN_Enum_CheckoutFields::toArray('customer',false) as $field => $settings) {
    $values[$field] = !get_user_meta($current_user->ID, '_sln_' . $field, true) && null !== $settings['default'] && $settings['type'] !== 'checkbox' ? $settings['default'] : get_user_meta($current_user->ID, '_sln_' . $field, true) ;
}
$errors = isset($sln_update_profile) && is_array($sln_update_profile) && isset($sln_update_profile['errors']) ? $sln_update_profile['errors'] : array();

?>
<form method="post"  role="form" id="salon-my-account-profile-form" >
    <input type="hidden" name="action" value="sln_update_profile">
    <?php wp_nonce_field('slnUpdateProfileNonce', 'slnUpdateProfileNonceField');?>
    <div class="container-fluid">
        <div class="row">
            <?php foreach (array_merge(SLN_Enum_CheckoutFields::toArray('defaults',false),SLN_Enum_CheckoutFields::toArray('customer',false)) as $field => $settings): ?>
                <?php if (SLN_Enum_CheckoutFields::isHidden($field)) {
                            continue;
                    }

                    $type  = $settings['type'];
                    $width = $settings['width'];
            ?>
            <?php if ($field === 'password') {
                    echo '</div><div class="row">';
            }
            // close previous row & open next ?>
                <div class="col-xs-12 col-md-<?php echo $field == 'address' ? 12 : $width ?> <?php echo 'field-' . $field ?> <?php if ($type !== 'checkbox') {echo 'sln-input sln-input--simple';}?> <?php echo $type ? 'sln-' . $type : '' ?>">
                        <label for="<?php echo SLN_Form::makeID('sln[' . $field . ']') ?>"><?php echo $settings['label'] ?></label>
                        <?php if (($field == 'phone') && ($prefix = $plugin->getSettings()->get('sms_prefix'))): ?>
                            <div class="input-group sln-input-group">
                                    <span class="input-group-addon sln-input--addon"><?php echo $prefix ?></span>
                        <?php endif?>
                        <?php
                            if (strpos($field, 'password') === 0) {
                                SLN_Form::fieldText('sln[' . $field . ']', '', array('type' => 'password'));
                            } else if (strpos($field, 'email') === 0) {
                                SLN_Form::fieldText('sln[' . $field . ']', $values[$field], array('required' => SLN_Enum_CheckoutFields::isRequired($field), 'type' => 'email'));
                            } else {
                                if ($type) {
                                    $additional_opts = array(
                                        'sln[' . $field . ']', $values[$field],
                                        array('required' => SLN_Enum_CheckoutFields::isRequired($field)),
                                    );
                                    $method_name = 'field' . ucfirst($type);
                                    if ($type === 'checkbox') {
                                        $additional_opts = array_merge(array_slice($additional_opts, 0, 2), array(''), array_slice($additional_opts, 2));
                                        $method_name     = $method_name . 'Button';
                                    }

                                    if ($type === 'select') {
                                        $additional_opts = array_merge(array_slice($additional_opts, 0, 1), array($settings['options']), array_slice($additional_opts, 1));

                                    }
                                    call_user_func_array(array('SLN_Form', $method_name), $additional_opts);
                                } else {
                                    SLN_Form::fieldText('sln[' . $field . ']', $values[$field], array('required' => SLN_Enum_CheckoutFields::isRequired($field)));
                                }
                            }
                        ?>
                        <?php if (($field == 'phone') && isset($prefix)): ?>
                        </div>
                        <?php endif?>
                </div>

            <?php endforeach?>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-6 sln-form-actions">
                <div class="sln-btn sln-btn--emphasis sln-btn--medium sln-btn--fullwidth">
                   <input type="submit" id="sln-accout-profile-submit" name="sln-accout-profile-submit" value="<?php _e('Update Profile','salon-booking-system');?>">
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">            
                <div class="row sln-box--main" <?php    if(!$errors) echo 'style="display:none;"' ?>>
                    <div class="statusContainer col-md-12">
                        <?php if ($errors): ?>
                            <?php foreach ($errors as $error): ?>
                                <div class="sln-alert sln-alert--problem"><?php echo $error ?></div>
                            <?php endforeach ?>
                        <?php endif ?>
                    </div>
                </div>            
        </div>
    </div>
</form>
