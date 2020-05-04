<?php
/**
 * The top header widgets.
 *
 * @package cornerstone
 */

$options = get_option('vslmd_options');

$top_header_columns = $options['top_header_columns'];

//Structure With 1 Column

if($top_header_columns == '1') { ?>

	<div class="col-md-12">
		<?php if ( is_active_sidebar( 'top-header-central' ) ) { ?>
            <?php dynamic_sidebar( 'top-header-central' ); ?>
        <?php } ?>
	</div>

<?php //Structure With 2 Columns

} elseif($top_header_columns == '2' || $top_header_columns == '5' || $top_header_columns == '6') { ?>
	
    <?php if($top_header_columns == '2') { ?>
    
        <div class="col-md-6">
            <?php if ( is_active_sidebar( 'top-header-left' ) ) { ?>
                <?php dynamic_sidebar( 'top-header-left' ); ?>
            <?php } ?>
        </div>
        
         <div class="col-md-6">
            <?php if ( is_active_sidebar( 'top-header-right' ) ) { ?>
                <?php dynamic_sidebar( 'top-header-right' ); ?>
            <?php } ?>
        </div>
    
    <?php } elseif($top_header_columns == '5') { ?>
    
        <div class="col-md-3">
            <?php if ( is_active_sidebar( 'top-header-left' ) ) { ?>
                <?php dynamic_sidebar( 'top-header-left' ); ?>
            <?php } ?>
        </div>
        
         <div class="col-md-9">
            <?php if ( is_active_sidebar( 'top-header-right' ) ) { ?>
                <?php dynamic_sidebar( 'top-header-right' ); ?>
            <?php } ?>
        </div>
    
    <?php } elseif($top_header_columns == '6') { ?>
    
    	<div class="col-md-9">
            <?php if ( is_active_sidebar( 'top-header-left' ) ) { ?>
                <?php dynamic_sidebar( 'top-header-left' ); ?>
            <?php } ?>
        </div>
        
         <div class="col-md-3">
            <?php if ( is_active_sidebar( 'top-header-right' ) ) { ?>
                <?php dynamic_sidebar( 'top-header-right' ); ?>
            <?php } ?>
        </div>
    
    <?php } ?>

<?php //Structure With 3 Columns

} elseif($top_header_columns == '3' || $top_header_columns == '4') { ?>

	<?php if($top_header_columns == '3') { ?>
	
        <div class="col-md-4">
            <?php if ( is_active_sidebar( 'top-header-left' ) ) { ?>
                <?php dynamic_sidebar( 'top-header-left' ); ?>
            <?php } ?>
        </div>
        
        <div class="col-md-4">
            <?php if ( is_active_sidebar( 'top-header-central' ) ) { ?>
                <?php dynamic_sidebar( 'top-header-central' ); ?>
            <?php } ?>
        </div>
        
         <div class="col-md-4">
            <?php if ( is_active_sidebar( 'top-header-right' ) ) { ?>
                <?php dynamic_sidebar( 'top-header-right' ); ?>
            <?php } ?>
        </div>
        
	<?php } elseif($top_header_columns == '4') { ?>
    
        <div class="col-md-3">
            <?php if ( is_active_sidebar( 'top-header-left' ) ) { ?>
                <?php dynamic_sidebar( 'top-header-left' ); ?>
            <?php } ?>
        </div>
        
        <div class="col-md-6">
            <?php if ( is_active_sidebar( 'top-header-central' ) ) { ?>
                <?php dynamic_sidebar( 'top-header-central' ); ?>
            <?php } ?>
        </div>
        
         <div class="col-md-3">
            <?php if ( is_active_sidebar( 'top-header-right' ) ) { ?>
                <?php dynamic_sidebar( 'top-header-right' ); ?>
            <?php } ?>
        </div>
    
    <?php } ?>
	
<?php } ?>