<?php header("Content-type: text/css; charset=utf-8"); 


/*-----------------------------------------------------------------------------------*/
/*	General Functions
/*-----------------------------------------------------------------------------------*/

$absolute_path = __FILE__;
$path_to_file = explode( 'wp-content', $absolute_path );
$path_to_wp = $path_to_file[0];

require_once( $path_to_wp . '/wp-load.php' );

/*-----------------------------------------------------------------------------------*/

/*-----------------------------------------------------------------------------------*/
/*	Define Values
/*-----------------------------------------------------------------------------------*/

//Variables > Theme Options

$options = get_option('vslmd_options');
$account_brand = $options['account_brand'];
$account_bg_color = $options['account_background_color'];
$account_bg_image = $options['account_background_image'];
$account_clr_scheme = $options['account_color_scheme'];


if(!empty($account_brand)){ ?>

/*-----------------------------------------------------------------------------------*/
/*	01.	Custom Logo
/*-----------------------------------------------------------------------------------*/

.login h1 a {
  background-image: url('<?php echo $account_brand['url']; ?>');
  -webkit-background-size: contain;
  background-size: contain;
  height: <?php echo $account_brand['height']; ?>px;
  width: <?php echo $account_brand['width']; ?>px;
}

<?php } ?>

<?php if(!empty($account_clr_scheme)){ ?>

/*-----------------------------------------------------------------------------------*/
/*	02.	Text and Buttons Color Scheme
/*-----------------------------------------------------------------------------------*/

.login form .input, 
.login input[type=text] {
	opacity: 0.8;
    -webkit-transition: all 0.2s linear;
    transition: all 0.2s linear; 
}

.login form .input:hover, 
.login input[type=text]:hover,
.login form .input:active, 
.login input[type=text]:active,
.login form .input:focus, 
.login input[type=text]:focus {
	opacity: 1;
}

<?php if($account_clr_scheme == 'light') { ?>
	
	.login label,
    .login #backtoblog a, 
    .login #nav a {
    	color: rgba(255,255,255,.8);
    }
    
    .wp-core-ui .button-primary {
    	background-color: rgba(255,255,255,.8);
        color: #222328;
    }
	
<?php } else { ?>

	.login label,
    .login #backtoblog a, 
    .login #nav a {
    	color: #222328;
    }
	
<?php } ?>
<?php } ?>

<?php if(!empty($account_bg_image)) {

/*-----------------------------------------------------------------------------------*/
/*	03.	Background Color And Image
/*-----------------------------------------------------------------------------------*/


/* Background Image */

	$account_background_image = $account_bg_image['background-image'];
	$account_background_repeat = $account_bg_image['background-repeat'];
	$account_background_position = $account_bg_image['background-position'];
	$account_background_size = $account_bg_image['background-size'];
	$account_background_attachment = $account_bg_image['background-attachment'];

	if(!empty($account_background_image)){ ?>
		body.login {
			background: url(<?php echo $account_background_image; ?>);
			<?php if(!empty($account_background_repeat)){ ?> background-repeat: <?php echo $account_background_repeat; ?>; <?php } ?>
			<?php if(!empty($account_background_position)){ ?> background-position: <?php echo $account_background_position; ?>; <?php } ?>
			<?php if(!empty($account_background_size)){ ?> background-size: <?php echo $account_background_size; ?>; <?php } ?>
			<?php if(!empty($account_background_attachment)){ ?> background-attachment: <?php echo $account_background_attachment; ?>; <?php } ?>
		} <?php
	} // Background options end
} // Background image end


/* Background Color */

if(!empty($account_bg_color)) { ?>

body.login:after {
	background-color: <?php echo $account_bg_color['rgba']; ?>;
    content: '';
    width: 100%;
    height: 100%;
    position: absolute;
    z-index: 1;
    top: 0;
}

body #login {
    z-index: 2;
    position: relative;
}

<?php } ?> 
