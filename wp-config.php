<?php
define( 'WP_CACHE', true );
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'Sql1778066_1');

/** Database username */
define('DB_USER', 'Sql1778066');

/** Database password */
define('DB_PASSWORD', '4Ytr+d%D9-wAKBV');

/** Database hostname */
define('DB_HOST', '89.46.111.190');

/** Database charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/** Limit revision Post */
if (!defined('WP_POST_REVISIONS')) define('WP_POST_REVISIONS', 10);

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', '(45jU3N|2w9hR6oNN5LYL+]msvoODQL/]LgqY5_i88J:37w1wcr#6I9IV8QWkKQ2');
define('SECURE_AUTH_KEY', '42bG&;QxV[d!8#096qNL6q7PSFA9#78#qxX1![5U2YlJ|sxet40;7Jgv3jU3z85a');
define('LOGGED_IN_KEY', '8*g78V(ELA((|8SN7p~6N_6D!-Yk*6[neMabSkC7:3!9428eAkv@2:K-|7J9%#Zp');
define('NONCE_KEY', '1+E9803+7-t@Wnx&sAzfLy+k*1AVgRzF*#F9&Xd+2z7/+0/2Q/F!kPCE40fZC7S)');
define('AUTH_SALT', '1sJVmbn6725;74Lbcchvs]B46;Wtf|#Ah2Vgc29wK#P0@X8390H3%:+Z0vu7M6lS');
define('SECURE_AUTH_SALT', 'ykW_#cV(GA8ctuUGfGVn!tFrc&M6xg9r3~7hlec0Sug~c3964;&4rCnL7Sk20M*|');
define('LOGGED_IN_SALT', '8R5ukF;@N7|fsa;1l%)%54i*Zz9URP:F1T+j3KS2XwPZ~8VX1:#;g1x0!4:4Q8S+');
define('NONCE_SALT', 'X(yJI0sNCxR[acf)S9Hfhp;aD_~|#I9:4+XC1r5pilUW02fe*AC1m2L7(gP)4We!');


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'B7aMf_';


/* Add any custom values between this line and the "stop editing" line. */

define('WP_ALLOW_MULTISITE', true);
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
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}
define( 'WP_DEBUG_DISPLAY', false );

define( 'DISALLOW_FILE_EDIT', false );
define( 'CONCATENATE_SCRIPTS', false );
define( 'WP_CACHE_KEY_SALT', 'd8f785b0b5a7d1a4aa46c2adb4b387d2' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
