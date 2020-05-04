<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'ayesha_theme_salon' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'xZ?eydk!X]LGh]eyGVuQ^qPKvo8a!Uf/L:FMQI!Q&~WE6;6*!j[I|b8R%uEr1xZb' );
define( 'SECURE_AUTH_KEY',  '2mWy/RWiUO/#5I#|Kl>)qlv`@q2O+Yd7Vjnc{&G-_e`90G&plsu}j[ojvrqMz5hS' );
define( 'LOGGED_IN_KEY',    'Sc(/TOak77+dx buK7lme24y{[Z8OKju#EgM*V#85m_9TVhF(N:~RLC87#pLrGh2' );
define( 'NONCE_KEY',        'jVUu:USUtKz70P~cfzailHrDbjvarOpnva9otD<zN|a`pP{aBJ]{BpoJ0,hPP^Re' );
define( 'AUTH_SALT',        '~6@Uh[:N(kX]xuyN34;Y[K^rsCgo$9@y8|E~oZYC487AI~=D=pS9wb;KeJ&H3YY%' );
define( 'SECURE_AUTH_SALT', 'e;<7ZS~c?>e^),p&ilE/%.G#>/HJF}f?7SU,|T0|[7FXV2] OLzQFJ DGp-q)/F9' );
define( 'LOGGED_IN_SALT',   'wqNt9={{`FE*n5;.`(do_^(12crx$>BT$kAsnMqOrpJ*-^FREuu ^f+A9CC=nY]A' );
define( 'NONCE_SALT',       'R0+bpEq]!%#F?v,hP}>LF=jg`m491]g<U]Hs= :j*jD%SYR[-w)(NSK?^a,%l4Oa' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );
define( 'WP_DEBUG_DISPLAY', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
