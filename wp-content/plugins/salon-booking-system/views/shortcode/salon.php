<?php
/**
 * @var string               $content
 * @var SLN_Shortcode_Salon $salon
 */
//$labels = array(
//    'date'      => __('date', 'salon-booking-system'),
//    'services'  => __('services', 'salon-booking-system'),
//    'secondary' => __('secondary', 'salon-booking-system'),
//    'details'   => __('details', 'salon-booking-system'),
//    'summary'   => __('summary', 'salon-booking-system'),
//    'thankyou'  => __('thankyou', 'salon-booking-system'),
//);

$style = $salon->getStyleShortcode();
$cce = !$plugin->getSettings()->isCustomColorsEnabled();
$class = SLN_Enum_ShortcodeStyle::getClass($style);
?>
<div id="sln-salon" class="sln-bootstrap container-fluid <?php
            echo $class;
            if(!$cce) {
              echo ' sln-customcolors';
            }
            echo ' sln-step-' . $salon->getCurrentStep(); ?>">
    <?php

    $args = array(
        'label'        => __('Book an appointment', 'salon-booking-system'),
        'tag'          => 'h1',
        'textClasses'  => 'sln-salon-title',
        'inputClasses' => '',
        'tagClasses'   => 'sln-salon-title',
    );
    echo $plugin->loadView('shortcode/_editable_snippet', $args);
    echo $content;
    ?>

        <?php
        /* <ul class="salon-bar nav nav-pills nav-justified thumbnail">
          <?php $i = 0;
          foreach ($salon->getSteps() as $step) : $i++; ?>
          <li <?php echo $step == $salon->getCurrentStep() ? 'class="active"' : ''?>>
          <?php echo $i ?>. <?php echo $labels[$step] ?>
          </li>
          <?php endforeach ?>
          </ul>
         */

        ?>
<?php /*
<div class="sln-stepper">
<?php $i = 0;
          foreach ($salon->getSteps() as $step) : $i++; ?>
  <div class="step <?php echo $step == $salon->getCurrentStep() ? 'step--active' : ''?>">
    <span class="step-number"><?php echo $i ?></span> <span class="step-label"><?php echo $step; ?></span>
  </div>
<?php endforeach ?>
</div>
*/ ?>
<div id="sln-notifications"></div>
</div>
