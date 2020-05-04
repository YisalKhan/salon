<div class="sln-admin-sidebar mobile">
	<div class="sln-btn sln-btn--main sln-btn--big sln-btn--icon sln-icon--save sln-update-settings">
		<input type="submit" name="submit" id="submit" class="" value="Update Settings">
	</div>
	<div class="sln-toolbox">
		<button class="sln-btn sln-btn--main sln-btn--big sln-btn--icon sln-icon--tools sln-toolbox-trigger visible-md-inline-block visible-lg-inline-block">Tools </button>
		<a href="edit.php?post_type=sln_booking" class="sln-btn sln-btn--main sln-btn--big sln-btn--icon sln-icon--booking">Manage bookings </a>
		<a href="admin.php?page=salon" class="sln-btn sln-btn--main sln-btn--big sln-btn--icon sln-icon--calendar">Check calendar </a>
		<a href="edit.php?post_type=sln_attendant" class="sln-btn sln-btn--main sln-btn--big sln-btn--icon sln-icon--assistants">Active assistants </a>
	</div>
	<button class="sln-btn sln-btn--main sln-btn--small--round sln-btn--icon sln-icon--tools sln-toolbox-trigger-mob
	hidden-md hidden-lg">Tools </button>
	<?php if(!defined("SLN_VERSION_PAY") || !SLN_VERSION_PAY){ ?>
	<div class="clearfix visible-xs-block"></div>
	<button class="sln-btn hidden-md hidden-lg sln-admin-banner--trigger"><?php echo __('Get Premium', 'salon-booking-system') ?></button>
	<div class="clearfix"></div>
	<div class="sln-admin-banner">
		
			<a href="https://www.salonbookingsystem.com/plugin-pricing/" target="blank"><img src="<?php echo SLN_PLUGIN_URL . '/img/banner_business_plan.jpg'; ?>" alt="Logo"></a>
	</div>
	<?php } ?>
</div>
<div class="clearfix"></div>