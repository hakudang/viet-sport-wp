<?php
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
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',          'q{cW0VpYxtLb~~7BRtUG|e!Fl =_>puf=]6:SiSvxn<R+= TC9f@>VW`rI 6^cp7' );
define( 'SECURE_AUTH_KEY',   'JQ s2C+ H)GN&f/<7IfP2?F(Fe4XQGLz2t4Tmlxs+J|wJDO@WPAPe&z;Zut{PIRT' );
define( 'LOGGED_IN_KEY',     '$qRe6I*?P>=1kyr,s719{L@1JO+vJ8m*=8/X)[68-0luW+Au^Qr|=YN%F`wxBBq{' );
define( 'NONCE_KEY',         '5/B =iIjehM.ek$|L}MRK`~=wyxup~T4a7r<BkMY*4?(a>SSkO*NVMqTCx!k` !f' );
define( 'AUTH_SALT',         '!@6`O+dW?J#8Wv.WbzT>Q.dFBUd]x[9cMC]Mh{tsZ,*cl?KSwzTZuz$e(<SDici?' );
define( 'SECURE_AUTH_SALT',  'L^|u`f)<c%%p TvWy#Z1. tCn~R^y,!tN]0&mf5Hk hukp@QAe_Y`&Wpl]2i$d<8' );
define( 'LOGGED_IN_SALT',    'BUR]%WD:zwa:.(5Z]kYsyy$X&&W>l2x8R]^e2:<Kery:U`B5wbl-77ce(98X8vT9' );
define( 'NONCE_SALT',        'PH=o$?UKeV/6/d5@^X:=<I{YO$ {ybZF2A{dyVC_ZJ/=.D6[CuYS8`MX=1tP[zx@' );
define( 'WP_CACHE_KEY_SALT', 'QsCaq`wWu}$,Nwaf2:.vGNy)X87zCD>pWj|8r5nUE1Ri+mpLl{E>.>P<?KOij$*}' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';
// $table_prefix = 'wpstg0_';


/* Add any custom values between this line and the "stop editing" line. */



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
// if ( ! defined( 'WP_DEBUG' ) ) {
// 	define( 'WP_DEBUG', false );
// }

define( 'WP_HOME', 'http://viet-sport.local' );
define( 'WP_SITEURL', 'http://viet-sport.local' );


define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false ); // để log không in ra frontend

define( 'WP_ENVIRONMENT_TYPE', 'local' );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';


// define('FORCE_SSL_ADMIN', false);

