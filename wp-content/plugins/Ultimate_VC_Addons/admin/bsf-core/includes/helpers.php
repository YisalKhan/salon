<?php
/**
 * Helper functions for BSF Core.
 * 
 * @author Brainstorm Force
 * @package bsf-core
 */

function bsf_get_api_site( $prefer_unsecure = false ) {

	if ( defined( 'BSF_API_URL' ) ) {
		$bsf_api_site = BSF_API_URL;
	} else {
		$bsf_api_site = 'http://support.brainstormforce.com/';

		if ( false == $prefer_unsecure && wp_http_supports( array( 'ssl' ) ) ) {
			$bsf_api_site = set_url_scheme( $bsf_api_site, 'https' );
		}
	}

	return $bsf_api_site;
}

function bsf_get_api_url( $prefer_unsecure = false ) {
	$url = bsf_get_api_site( $prefer_unsecure ) . 'wp-admin/admin-ajax.php';

	return $url;
}

if ( ! function_exists( 'bsf_convert_core_path_to_relative' ) ) {

	/**
	 * Depracate bsf_convert_core_path_to_relative() to in favour of bsf_core_url()
	 *
	 * @param  $path $path depracated
	 * @return String       URL of bsf-core directory.
	 */
	function bsf_convert_core_path_to_relative( $path ) {
		_deprecated_function( __FUNCTION__, '1.22.46', 'bsf_core_url' );

		return bsf_core_url( '' );
	}
}

if ( ! function_exists( 'bsf_core_url' ) ) {

	function bsf_core_url( $append = '' ) {
		$path       = wp_normalize_path( BSF_UPDATER_PATH );
		$theme_dir  = wp_normalize_path( get_template_directory() );
		$plugin_dir = wp_normalize_path( WP_PLUGIN_DIR );

		if ( strpos( $path, $theme_dir ) !== false ) {
			return rtrim( get_template_directory_uri() . '/admin/bsf-core/', '/' ) . $append;
		} elseif ( strpos( $path, $plugin_dir ) !== false ) {
			return rtrim( plugin_dir_url( BSF_UPDATER_FILE ), '/' ) . $append;
		} elseif ( strpos( $path, dirname( plugin_basename( BSF_UPDATER_FILE ) ) ) !== false ) {
			return rtrim( plugin_dir_url( BSF_UPDATER_FILE ), '/' ) . $append;
		}

		return false;
	}
}

if ( ! function_exists( 'get_brainstorm_product' ) ) {

	function get_brainstorm_product( $product_id = '' ) {

		$all_products = brainstorm_get_all_products();

		foreach ( $all_products as $key => $product ) {

			$product_id_bsf = isset( $product['id'] ) ? $product['id'] : '';

			if ( $product_id == $product_id_bsf ) {

				return $product;
			}
		}
	}
}

if ( ! function_exists( 'brainstorm_get_all_products' ) ) {

	function brainstorm_get_all_products( $skip_plugins = false, $skip_themes = false, $skip_bundled = false ) {

		$brainstrom_products         = get_option( 'brainstrom_products', array() );
		$brainstrom_bundled_products = get_option( 'brainstrom_bundled_products', array() );
		$brainstorm_plugins          = isset( $brainstrom_products['plugins'] ) ? $brainstrom_products['plugins'] : array();
		$brainstorm_themes           = isset( $brainstrom_products['themes'] ) ? $brainstrom_products['themes'] : array();

		if ( $skip_plugins == true ) {
			$all_products = $brainstorm_themes;
		} elseif ( $skip_themes == true ) {
			$all_products = $brainstorm_plugins;
		} else {
			$all_products = $brainstorm_plugins + $brainstorm_themes;
		}

		if ( $skip_bundled == false ) {

			foreach ( $brainstrom_bundled_products as $parent_id => $parent ) {

				foreach ( $parent as $key => $product ) {

					if ( isset( $all_products[ $product->id ] ) ) {
						$all_products[ $product->id ] = array_merge( $all_products[ $product->id ], (array) $product );
					} else {
						$all_products[ $product->id ] = (array) $product;
					}
				}
			}
		}

		return $all_products;
	}
}

/**
 * Generate's markup to generate notice to ask users to install required extensions.
 *
 * @since Graupi 1.9
 *
 * $product_id (string) Product ID of the brainstorm product
 * $mu_updater (bool) If True - give nag to separately install brainstorm updater multisite plugin
 */
if ( ! function_exists( 'bsf_extension_nag' ) ) {

	function bsf_extension_nag( $product_id = '', $mu_updater = false ) {

		$display_nag = get_user_meta( get_current_user_id(), $product_id . '-bsf_nag_dismiss', true );

		if ( $mu_updater == true ) {
			bsf_nag_brainstorm_updater_multisite();
		}

		if ( $display_nag === '1' ||
			! user_can( get_current_user_id(), 'activate_plugins' ) ||
			! user_can( get_current_user_id(), 'install_plugins' ) ) {
			return;
		}

		$bsf_installed_plugins     = '';
		$bsf_not_installed_plugins = '';
		$bsf_not_activated_plugins = '';
		$installer                 = '';
		$bsf_install               = false;
		$bsf_activate              = false;
		$bsf_bundled_products      = bsf_bundled_plugins( $product_id );
		$bsf_product_name          = brainstrom_product_name( $product_id );

		foreach ( $bsf_bundled_products as $key => $plugin ) {

			if ( ! isset( $plugin->id ) || $plugin->id == '' || ! isset( $plugin->must_have_extension ) || $plugin->must_have_extension == 'false' ) {
				continue;
			}

			$plugin_abs_path = WP_PLUGIN_DIR . '/' . $plugin->init;
			if ( is_file( $plugin_abs_path ) ) {

				if ( ! is_plugin_active( $plugin->init ) ) {
					$bsf_not_activated_plugins .= $bsf_bundled_products[ $key ]->name . ', ';
				}
			} else {
				$bsf_not_installed_plugins .= $bsf_bundled_products[ $key ]->name . ', ';
			}
		}

		$bsf_not_activated_plugins = rtrim( $bsf_not_activated_plugins, ', ' );
		$bsf_not_installed_plugins = rtrim( $bsf_not_installed_plugins, ', ' );

		if ( $bsf_not_activated_plugins !== '' || $bsf_not_installed_plugins !== '' ) {
			echo '<div class="updated notice is-dismissible"><p></p>';
			if ( $bsf_not_activated_plugins !== '' ) {
				echo '<p>';
				echo $bsf_product_name . __( ' requires following plugins to be active : ', 'bsf' );
				echo '<strong><em>';
				echo $bsf_not_activated_plugins;
				echo '</strong></em>';
				echo '</p>';
				$bsf_activate = true;
			}

			if ( $bsf_not_installed_plugins !== '' ) {
				echo '<p>';
				echo $bsf_product_name . __( ' requires following plugins to be installed and activated : ', 'bsf' );
				echo '<strong><em>';
				echo $bsf_not_installed_plugins;
				echo '</strong></em>';
				echo '</p>';
				$bsf_install = true;
			}

			if ( $bsf_activate == true ) {
				$installer .= '<a href="' . get_admin_url() . 'plugins.php?plugin_status=inactive">' . __( 'Begin activating plugins', 'bsf' ) . '</a> | ';
			}

			if ( $bsf_install == true ) {
				$installer .= '<a href="' . bsf_exension_installer_url( $product_id ) . '">' . __( 'Begin installing plugins', 'bsf' ) . '</a> | ';
			}

			$installer .= '<a href="' . esc_url( add_query_arg( 'bsf-dismiss-notice', $product_id ) ) . '">' . __( 'Dismiss This Notice', 'bsf' ) . '</a>';

			$installer = ltrim( $installer, '| ' );
			echo '<p><strong>';
			echo rtrim( $installer, ' |' );
			echo '</p></strong>';

			echo '<p></p></div>';
		}
	}
	
}

if ( ! function_exists( 'bsf_nag_brainstorm_updater_multisite' ) ) {

	function bsf_nag_brainstorm_updater_multisite() {

		if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
			require_once ABSPATH . '/wp-admin/includes/plugin.php';
		}

		if ( ! is_multisite() || is_plugin_active_for_network( 'brainstorm-updater/index.php' ) ) {
			return;
		}

		echo '<div class="notice notice-error uct-notice is-dismissible"><p>';
		printf(
			__( 'Looks like you are on a WordPress Multisite, you will need to install and network activate %1$s Brainstorm Updater for Multisite %2$s plugin. Download it from %3$s here %4$s', 'bsf' ),
			'<strong><em>',
			'</strong></em>',
			'<a href="http://bsf.io/bsf-updater-mu" target="_blank">',
			'</a>'
		);

		echo '</p>';
		echo '</div>';
	}

}