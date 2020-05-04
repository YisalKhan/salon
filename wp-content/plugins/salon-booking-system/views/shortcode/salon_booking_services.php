<?php
if(!$data['services']) return;
$plugin = SLN_Plugin::getInstance();
?>
<section class="sln-datashortcode sln-datashortcode--services">	
	<div class="sln-datalist <?php 
	if(isset($data['styled'])) echo 'sln-datalist--styled '; 
	if(isset($data['columns'])) echo 'sln-datalist--'.$data['columns'].'cols '; 	
	?>">
	<?php 
	$display = $data['display'];
	foreach ($data['services'] as $service) {
		$thumb     = has_post_thumbnail($service->getId()) ?get_the_post_thumbnail(
        	$service->getId(),
        	'thumbnail'
    	) : '';	
	?>
		<div class="sln-datalist__item">
			<?php if(!$display || in_array('name',$display)){ ?>
			<h3 class="sln-datalist__item__name"><?php echo $service->getName() ?></h3>
			<?php } ?>
			<?php if(!$display || in_array('image',$display)){ ?>
			<div class="sln-datalist__item__image">
				<?php echo $thumb ?>
			</div>
			<?php } ?>
			<?php if(!$display || in_array('description',$display)){ ?>
			<p class="sln-datalist__item__description">
				<?php echo $service->getContent() ?>
			</p>
			<?php } ?>
			<div class="sln-datalist__item__info">
				<?php if(!$display || in_array('duration',$display)){ ?>
				<p class="sln-datalist__item__duration">
					<span><?php echo __('Duration', 'salon-booking-system')?>: </span>
					<strong><?php echo $service->getDuration()->format('H:i') ?></strong>
				</p>
				<?php } ?>
				<?php if(!$display || in_array('price',$display)){ ?>
				<p class="sln-datalist__item__price">
					<span><?php _e('Price','salon-booking-system');?>: </span>
					<strong><?php echo $plugin->format()->money($service->getPrice(), true, false) ?></strong>
				</p>
				<?php } ?>
			</div>		
			<?php if(!$display || in_array('action',$display)){ ?>
			<div class="sln-datalist__item__actions">
				<a href="<?php 	echo add_query_arg(array('service' => $service->getId()), $data['booking_url']); ?>" class="sln-datalist__item__cta"><?php _e('Book now','salon-booking-system'); ?></a>
			</div>
			<?php } ?>
		</div>
	<?php } ?>			
		<div class="sln-datalist_clearfix"></div>
	</div>
</section>
