<?php
$addAjax = apply_filters('sln.template.calendar.ajaxUrl','');
$ai = $plugin->getSettings()->getAvailabilityItems();
list($timestart, $timeend) = $ai->getTimeMinMax();
$timesplit = $plugin->getSettings()->getInterval();
$holidays_rules = apply_filters('sln.get-day-holidays-rules', $plugin->getSettings()->getDailyHolidayItems());
$day_calendar_holydays_ajax_data = apply_filters('sln.get-day-calendar-holidays-ajax-data', array());
?>
<script type="text/javascript">
    var salon;
    var calendar_translations = {
        'Go to daily view': '<?php _e('Go to daily view', 'salon-booking-system') ?>'
    };
    var salon_default_duration = <?php echo $timesplit; ?>;
    var daily_rules = JSON.parse('<?php echo json_encode($holidays_rules); ?>');
    var holidays_rules_locale = {
        'block':'<?php _e('Block','salon-booking-system'); ?>',
        'unblock':'<?php _e('Unlock','salon-booking-system'); ?>',
        'single':'<?php _e('this row','salon-booking-system'); ?>',
        'multiple':'<?php _e('these rows','salon-booking-system'); ?>'
    }
    var sln_search_translation = {
        'tot':'<?php _e('Tot.','salon-booking-system'); ?>',
        'edit':'<?php _e('Edit','salon-booking-system'); ?>',
        'cancel':'<?php _e('Cancel','salon-booking-system'); ?>',
        'no_results':'<?php _e('No results','salon-booking-system'); ?>'
    }

    var dayCalendarHolydaysAjaxData = JSON.parse('<?php echo json_encode($day_calendar_holydays_ajax_data); ?>');

<?php $today = new DateTime() ?>
jQuery(function($){
    initSalonCalendar(
        $,
        salon.ajax_url+"&action=salon&method=calendar&security="+salon.ajax_nonce+'<?php echo $addAjax ?>',
//        '<?php echo SLN_PLUGIN_URL ?>/js/events.json.php',
        '<?php echo $today->format('Y-m-d') ?>',
        '<?php echo SLN_PLUGIN_URL ?>/views/js/calendar/',
        '<?php echo $plugin->getSettings()->get('calendar_view') ?:'month' ?>',
        '<?php echo $plugin->getSettings()->get('week_start') ?: 0 ?>'
    );
});
</script>
<style>
.day-calbar,
.week-calbar{
    display: block;
    margin-bottom: 8px;
    height: 8px;
    width: 100%;
    background-color: #dfdfdf;
}
.week-calbar{
    margin-top: -8px;
}
.month-calbar{
    display: block;
    height: 8px;
    width: 100%;
    background-color: #dfdfdf;
}
.calbar .busy{
    display: block;
    background-color: red;
    height: 8px;
    float: left;
}
.calbar .free{
    display: block;
    height: 8px;
    float: left;
    background-color: green;
}
.calbar-tooltip{
    background-color: #c7dff3;
    display: inline-block;
    width: 340px;
    height: 50px;
    padding: 5px;
    margin: -20px 0 -10px -80px;
}
.calbar-tooltip span{
    float: left;
    display: block;
    width: 33%;
    color: #666;
}
.calbar-tooltip strong{
    font-size: 16px;
    color: #0C6EB6;
    display: block;
    clear: both;
}
#cal-day-box .day-event-panel-border{
    z-index: 610;
    position: absolute;
    height: inherit;
    width: 1px;
    background-color: #d4d4d4;
    top: -10px;
    left: 80px;
}
#cal-day-box #cal-day-panel-hour{
    z-index: 997 !important;
}
#cal-day-box .day-event{
    width: 7.4% !important;
    max-width: 7.4% !important;
    left: 82px;
}
#cal-day-box .cal-day-assistants{
    margin: 0 0 0 80px;
    width: 91.2%;
}
#cal-day-box .cal-day-assistant{
    display: inline-block;
    text-align: center;
    width: 8.4% !important;
    margin-right: -4px;
}
#cal-day-box .day-highlight{
    border-left: none !important;
    cursor: pointer;
}
#cal-day-box .day-highlight:hover{
    text-decoration: underline;
}

.cal-day-hour-part .block_date,.cal-day-hour-part [data-action=add-event-by-date] {
    width: 5%;
    min-width: 5% !important;
    padding: 0 0.3rem;
    height: 28px;
    display: none;
}
@media only screen and (min-width: 1200px) {
    .cal-day-hour-part [data-action=add-event-by-date] {
        width: 7%;
    }
}
.cal-day-hour-part{
    position: relative;
    z-index: 998;
}
.cal-day-hour-part.active .block_date,.cal-day-hour-part.active [data-action=add-event-by-date] {
    display: block;
    margin: 0 auto;
    position: absolute;
    top: 0;
    left: 50%;
    transform: translate(-105%);
    z-index: 999;
}
.cal-day-hour-part.selected [data-action=add-event-by-date]{
    display: none;
}
.cal-day-hour-part.active .block_date{
    transform: translate(5%);
}
.sln-calendar--wrapper .sln-calendar-view .cal-day-hour-part.active {
    background-color: rgba(122, 158, 208, 0.76);
}
</style>
<div class="wrap sln-bootstrap">
    <h1><?php _e('Calendar','salon-booking-system')?> - <span class="current-view--title"></span></h1>
</div>
<div class="clearfix"></div>
<div class="container-fluid sln-calendar--wrapper sln-calendar--wrapper--loading">
<div class="sln-calendar--wrapper--sub" style="opacity: 0;">
    <!--<div class="row">
        <div class="col-xs-11">
            <div class="page-header pull-left">
                <h3 class="current-view--title"></h3>
            </div>
        </div>
    </div>-->

<div class="row">
    <div class="col-xs-12 col-md-3 col-md-push-9 btn-group"> 
        <?php include 'help.php' ?>
    </div>
    
          <?php do_action('sln.template.calendar.navtabwrapper') ?>
</div>
<div class="row">
    <div class="col-xs-12 btn-group nav-tab-wrapper sln-nav-tab-wrapper">
        <div class="sln-btn sln-btn--borderonly sln-btn--large" data-calendar-view="day">
        <button class="" data-calendar-view="day"><?php _e('Day', 'salon-booking-system')?></button>
        </div>
        <div class="sln-btn sln-btn--borderonly sln-btn--large" data-calendar-view="week">
        <button class="" data-calendar-view="week"><?php _e('Week', 'salon-booking-system')?></button>
        </div>
        <div class="sln-btn sln-btn--borderonly sln-btn--large" data-calendar-view="month">
        <button class=" active" data-calendar-view="month"><?php _e('Month', 'salon-booking-system')?></button>
        </div>
        <div class="sln-btn sln-btn--borderonly sln-btn--large" data-calendar-view="year">
        <button class="" data-calendar-view="year"><?php _e('Year', 'salon-booking-system')?></button>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-md-6 sln-box-title current-view--title"></div>
    <div class="col-xs-12 form-group sln-switch cal-day-filter">
        <div class="pull-right">
            <span class="sln-fake-label"><?php _e('Assistants view', 'salon-booking-system') ?></span>
            <?php
            SLN_Form::fieldCheckbox(
                "sln-calendar-assistants-mode-switch",
                ($checked = get_user_meta( get_current_user_id(), '_assistants_mode', true ) ) !== ''  ? $checked && $checked != 'false' : false
            )
            ?>
            <label for="sln-calendar-assistants-mode-switch" class="sln-switch-btn" data-on="On" data-off="Off"></label>
        </div>
    </div>
</div>

<div class="row sln-calendar-view sln-box">
    <div class="col-xs-12 form-inline">
        <div class="row">            
            <div class="col-xs-12 col-sm-6 col-sm-push-6">
                <div class="sln-calendar-viewnav btn-group">
                    <div class="sln-btn sln-btn--light sln-btn--large  sln-btn--icon sln-btn--icon--left sln-icon--arrow--left" data-calendar-view="day">
                        <button class="f-row" data-calendar-nav="prev"><?php _e('Previous', 'salon-booking-system') ?></button>
                    </div>
                    <div class="sln-btn sln-btn--light sln-btn--large" data-calendar-view="day">
                        <button class="f-row" data-calendar-nav="today"><?php _e('Today', 'salon-booking-system')?></button>
                    </div>
                    <div class="sln-btn sln-btn--light sln-btn--large  sln-btn--icon sln-icon--arrow--right" data-calendar-view="day">
                        <button class="f-row f-row--end" data-calendar-nav="next"><?php _e('Next', 'salon-booking-system') ?></button>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4 col-sm-pull-6">
                <div class="cal-day-search cal-day-filter">
                    <div class="sln-calendar-booking-search-wrapper"><div class="sln-calendar-booking-search-input-wrapper"><?php
                                            SLN_Form::fieldText(
                                                "sln-calendar-booking-search",false,[ 'attrs' =>[
                                                        'size' => 32,
                                                        'placeholder'=>__("Search",'salon-booking-system')
                                                    ]
                                                ]
                                            )
                                            ?></div>
                    <div class="sln-calendar-booking-search-icon">
                        
                    </div>
                    </div>
                    <div id="search-results-list" class="sln-calendar-search-results-list"></div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-2 col-sm-pull-5 col-lg-pull-4">
                <div class="cal-day-filter cal-day-pagination"></div>
            </div>
        </div>
    </div>

        <div class="clearfix"></div>
        <div id="calendar" data-timestart="<?php echo $timestart ?>" data-timeend="<?php echo $timeend ?>" data-timesplit="<?php echo $timesplit ?>"></div>
    <div class="clearfix"></div>

<!-- row sln-calendar-wrapper // END -->
</div>

<div id="sln-booking-editor-modal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="pull-right">
                    <button type="button" class="sln-btn sln-btn--ok sln-btn--large" aria-hidden="true" data-action="save-edited-booking"><?php _e('Save', 'salon-booking-system') ?></button>
                    <button type="button" class="sln-btn sln-btn--problem sln-btn--large" aria-hidden="true" data-action="delete-edited-booking"><?php _e('Delete', 'salon-booking-system') ?></button>
                    <button type="button" class="sln-btn sln-btn--light sln-btn--large" data-dismiss="modal" aria-hidden="true"><?php _e('Close', 'salon-booking-system') ?></button>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="modal-body">
                <div class="sln-booking-editor--wrapper">
                    <div class="sln-booking-editor--wrapper--sub" style="opacity: 0">
                        <iframe class="booking-editor" width="100%" height="600px" frameborder="0"
                                data-src-template-edit-booking="<?php echo admin_url('/post.php?post=%id&action=edit&mode=sln_editor') ?>"
                                data-src-template-new-booking="<?php echo admin_url('/post-new.php?post_type=sln_booking&date=%date&time=%time&mode=sln_editor') ?>"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if (current_user_can('export_reservations_csv_sln_calendar')): ?>
    <div class="row">
    <div class="col-xs-12 col-md-9">
        <form action="<?php echo admin_url('admin.php?page=' . SLN_Admin_Tools::PAGE)?>" method="post">
        <h2><?php _e('Export reservations into a CSV file', 'salon-booking-system') ?></h2>
        <div class="row">
            <?php
            $f         = $plugin->getSettings()->get('date_format');
            $weekStart = $plugin->getSettings()->get('week_start');
            $jsFormat  = SLN_Enum_DateFormat::getJsFormat($f);
            ?>
            <div class="form-group col-xs-12 col-md-4 sln_datepicker sln-input--simple">
            <label for="<?php echo SLN_Form::makeID("export[from]") ?>"><?php _e('from', 'salon-booking-system') ?></label>
            <input type="text" class="form-control sln-input" id="<?php echo SLN_Form::makeID("export[from]") ?>" name="export[from]"
                   required="required" data-format="<?php echo $jsFormat?>" data-weekstart="<?php echo $weekStart ?>"
                   data-locale="<?php echo SLN_Plugin::getInstance()->getSettings()->getDateLocale()?>"
            />
            </div>
            <div class="form-group col-xs-12 col-md-4 sln_datepicker sln-input--simple">
            <label for="<?php echo SLN_Form::makeID("export[to]") ?>"><?php _e('to', 'salon-booking-system') ?></label>
            <input type="text" class="form-control sln-input" id="<?php echo SLN_Form::makeID("export[to]") ?>" name="export[to]"
                   required="required" data-format="<?php echo $jsFormat?>" data-weekstart="<?php echo $weekStart ?>"
                   data-locale="<?php echo SLN_Plugin::getInstance()->getSettings()->getDateLocale()?>"
            />
            </div>
            <div class="form-group col-xs-12">
            <button type="submit" id="action" name="sln-tools-export" value="export"
                class="sln-btn sln-btn--main sln-btn--big sln-btn--icon sln-icon--file">
                <?php _e('Export', 'salon-booking-system') ?></button>
            </div>
        </div>
        </form>
    </div>
    <div class="col-xs-12 col-md-3 pull-right"></div>
    </div>
<?php endif; ?>
<div class="row">
<div class="col-xs-12 col-md-11">
    <h4><?php _e('Bookings status legend','salon-booking-system')?></h4> 
<ul>
<li><span class="pull-left event event-warning"></span><?php echo SLN_Enum_BookingStatus::getLabel(SLN_Enum_BookingStatus::PENDING) ?></li> 
<li><span class="pull-left event event-success"></span><?php echo SLN_Enum_BookingStatus::getLabel(SLN_Enum_BookingStatus::PAID) ?> <?php _e('or','salon-booking-system')?> <?php echo SLN_Enum_BookingStatus::getLabel(SLN_Enum_BookingStatus::CONFIRMED)?></li>
<li><span class="pull-left event event-info"></span><?php echo SLN_Enum_BookingStatus::getLabel(SLN_Enum_BookingStatus::PAY_LATER) ?></li>
<li><span class="pull-left event event-danger"></span><?php echo SLN_Enum_BookingStatus::getLabel(SLN_Enum_BookingStatus::CANCELED) ?></li>
</ul>
<div class="clearfix"></div>
        </div>
</div>
</div>
</div>
