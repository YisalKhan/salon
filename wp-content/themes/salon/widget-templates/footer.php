<!-- ******************* Footer Widget Area ******************* -->
<?php

$options = get_option('vslmd_options');
$footerColumns = $options['footer_widget_columns']; 

if($footerColumns == '1'){ ?>

<div class="col-md-12">
	<?php if ( is_active_sidebar( 'first-footer' ) ) { ?>
	<?php dynamic_sidebar('first-footer'); ?>
	<?php } ?>
</div>

<?php } else if($footerColumns == '2'){ ?>

<div class="col-md-6">
	<?php if ( is_active_sidebar( 'first-footer' ) ) { ?>
	<?php dynamic_sidebar('first-footer'); ?>
	<?php } ?>
</div>

<div class="col-md-6">
	<?php if ( is_active_sidebar( 'second-footer' ) ) { ?>
	<?php dynamic_sidebar('second-footer'); ?>
	<?php } ?>
</div>

<?php } else if($footerColumns == '3'){ ?>

<div class="col-md-4">
	<?php if ( is_active_sidebar( 'first-footer' ) ) { ?>
	<?php dynamic_sidebar('first-footer'); ?>
	<?php } ?>
</div>

<div class="col-md-4">
	<?php if ( is_active_sidebar( 'second-footer' ) ) { ?>
	<?php dynamic_sidebar('second-footer'); ?>
	<?php } ?>
</div>

<div class="col-md-4">
	<?php if ( is_active_sidebar( 'third-footer' ) ) { ?>
	<?php dynamic_sidebar('third-footer'); ?>
	<?php } ?>
</div>

<?php } else if($footerColumns == '4'){ ?>

<div class="col-md-3">
	<?php if ( is_active_sidebar( 'first-footer' ) ) { ?>
	<?php dynamic_sidebar('first-footer'); ?>
	<?php } ?>
</div>

<div class="col-md-3">
	<?php if ( is_active_sidebar( 'second-footer' ) ) { ?>
	<?php dynamic_sidebar('second-footer'); ?>
	<?php } ?>
</div>

<div class="col-md-3">
	<?php if ( is_active_sidebar( 'third-footer' ) ) { ?>
	<?php dynamic_sidebar('third-footer'); ?>
	<?php } ?>
</div>

<div class="col-md-3">
	<?php if ( is_active_sidebar( 'fourth-footer' ) ) { ?>
	<?php dynamic_sidebar('fourth-footer'); ?>
	<?php } ?>
</div>

<?php } else if($footerColumns == '5'){ ?>

<div class="col-md-8">
	<?php if ( is_active_sidebar( 'first-footer' ) ) { ?>
	<?php dynamic_sidebar('first-footer'); ?>
	<?php } ?>
</div>

<div class="col-md-4">
	<?php if ( is_active_sidebar( 'second-footer' ) ) { ?>
	<?php dynamic_sidebar('second-footer'); ?>
	<?php } ?>
</div>

<?php } else if($footerColumns == '6'){ ?>

<div class="col-md-4">
	<?php if ( is_active_sidebar( 'first-footer' ) ) { ?>
	<?php dynamic_sidebar('first-footer'); ?>
	<?php } ?>
</div>

<div class="col-md-8">
	<?php if ( is_active_sidebar( 'second-footer' ) ) { ?>
	<?php dynamic_sidebar('second-footer'); ?>
	<?php } ?>
</div>

<?php } else if($footerColumns == '7'){ ?>

<div class="col-md-3">
	<?php if ( is_active_sidebar( 'first-footer' ) ) { ?>
	<?php dynamic_sidebar('first-footer'); ?>
	<?php } ?>
</div>

<div class="col-md-6">
	<?php if ( is_active_sidebar( 'second-footer' ) ) { ?>
	<?php dynamic_sidebar('second-footer'); ?>
	<?php } ?>
</div>

<div class="col-md-3">
	<?php if ( is_active_sidebar( 'third-footer' ) ) { ?>
	<?php dynamic_sidebar('third-footer'); ?>
	<?php } ?>
</div>

<?php } ?>