<?php if($forAdmin): ?>

    <?php if(isset($updated) && $updated): ?>

	<?php echo $plugin->loadView('mail/_admin_update_message_text', compact('booking', 'plugin')) ?>

    <?php else: ?>

	<?php echo $plugin->loadView('mail/_admin_message_text', compact('booking')) ?>

    <?php endif; ?>

<?php else: ?>

    <?php if(isset($remind) && $remind): ?>

	<?php echo $plugin->loadView('mail/_customer_reminder_message_text', compact('booking', 'plugin')) ?>

    <?php else: ?>

	<?php if(isset($updated) && $updated): ?>

	    <?php echo $plugin->loadView('mail/_customer_update_message_text', compact('booking', 'plugin', 'updated_message')) ?>

	<?php else: ?>

	    <?php echo $plugin->loadView('mail/_customer_message_text', compact('booking', 'plugin')) ?>

	<?php endif; ?>

    <?php endif; ?>

<?php endif; ?>