<?php
/**
 * Header Alert Message.
 *
 * @package cornerstone
 */

$options = get_option('vslmd_options');

$alert_message_text = $options['alert_message_text'];

?>

<!-- ******************* Structure ******************* -->

<div class="alert-message">
	<div class="alert alert-dismissible fade show" role="alert">
		<div class="alert-message-content">
			<div class="container">
				<?php if(!empty($alert_message_text)){ echo $alert_message_text; } ?>
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
		</div>
	</div>
</div>

