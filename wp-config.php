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
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */

/**
 * DATABASE HIDDEN FOR SECURITY REASONS - CONTACT HR@AADHYAINNOVATIONS.COM - HR / ADMIN TEAM TO GET DATABASE CREDENTIALS 
 */
define('DB_NAME', "");

/** Database username */
define('DB_USER', "");

/** Database password */
define('DB_PASSWORD', "");

/** Database hostname */
define('DB_HOST', "localhost");

/** Database charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

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
define('AUTH_KEY', 'RfDA2xz:&71F0kdBWA82rt@U8R&s(Oh8Xy!&WaE;~)JZxS0+&uvJo0HEGW5I;*x8');
define('SECURE_AUTH_KEY', 'w#yAMm]d_;OlGH9)84m&k8&|7I69704@yf|MpS;7iDn2-+gF:i@2K@G*eyi-HF1a');
define('LOGGED_IN_KEY', 'BSl9B/2t:qsJKY8)yA20Z(Z-w[3BC2N5wD:PMxIl6pBJXS6chozCpS4g!*MFo*a~');
define('NONCE_KEY', '&@B639Cjy%5R72~%+UA1TDQ*83~9*7tE/q%IdzTF@*Z8/|nJc8ojA*17K|3H_LYB');
define('AUTH_SALT', 'NXJ21-Zg4098%M9m*UQj5M4&4F#[rB_Ovk414UB1g2Rg*a|a!e6OvZETwv-3853)');
define('SECURE_AUTH_SALT', '_re(0#(q0P(-Ui8UMbF3up84fKWA@#lL-1145N064XEl-T#Wq|zD[7ZX)|0%JX9K');
define('LOGGED_IN_SALT', '7NR8]+hp719PH41iN5Rg95gF)4i3_Q2mu9z_tizqPXttFyV&vf+~rMN494~5B;Y/');
define('NONCE_SALT', 'zl0WC&0&]dk+n)52%Gga)00q#W2EEAF0a9:Dw2kbHs6[+[Ysa/~xad91s6Yp*GpA');

/**#@-*/

/**
 * WordPress database table prefix.
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

/* Add any custom values between this line and the "stop editing" line. */



define( 'WP_SITEURL', 'https://aadhyainnovations.com/' );
define( 'WP_CACHE_KEY_SALT', 'b5412784a5732b577a62471c2ee2e4e7' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname(__FILE__) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
