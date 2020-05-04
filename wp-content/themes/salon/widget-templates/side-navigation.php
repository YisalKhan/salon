<?php if ( is_active_sidebar( 'side-navigation' ) ): ?>

<!-- ******************* Side Navigation Widget Area ******************* -->

<?php $options = get_option('vslmd_options'); ?>
<?php $side_navigation = (empty($options['side_navigation'])) ? '' : $options['side_navigation']; ?>
<?php $side_navigation_color_scheme = (empty($options['side_navigation_color_scheme'])) ? 'light' : $options['side_navigation_color_scheme']; ?>

<div id="<?php echo $side_navigation; ?>" class="side-navigation<?php echo ' '.$side_navigation_color_scheme; ?>">
    <a href="javascript:void(0)" id="close-side-navigation" class="closebtn">&times;</a>
    <div class="side-navigation-inner">
      <?php dynamic_sidebar( 'side-navigation' ); ?>
    </div>
</div>

<?php endif; ?>