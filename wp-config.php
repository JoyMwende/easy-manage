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
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'easy-manage' );

/** Database username */
define( 'DB_USER', 'admin' );

/** Database password */
define( 'DB_PASSWORD', 'admin' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         'r~&&_T-/s$eSMK-p}KmGhQk_ty*TJE_CxV={c }H?nX,59_V`CF-b~qzi8;1$B-{' );
define( 'SECURE_AUTH_KEY',  'fAesESR8i@Ox;WyneLQWh-U6wU|zSqn=fTUe)^+ROM) 7D-mP2~HhGIuB!`;a[pV' );
define( 'LOGGED_IN_KEY',    '#H~ZoDz@+0C[e3bx>5&R%yA`&RvNdYe[31O5%;BDE;8}r!W8&)dRKPDgq~Cy_u57' );
define( 'NONCE_KEY',        '`HALJ~RgYS<rixvw^%sE)~|`ZS3=!GGJh1EF5eY6%Bh](bz=[Ip,(HtKVsK1M=(t' );
define( 'AUTH_SALT',        's#Q>Y=g$k@/M_rIc_IAHEkK=&iF#yZ|gkbDBT!74#Z3Grc=7Ge;pHy~u1b*~/xN+' );
define( 'SECURE_AUTH_SALT', 'zol^+^b<A-(7a|u1<V6/CfH?3IV{B^}Zm(hI <[pSAw%Wqzz!ImJ~PLTl,Zmb3<|' );
define( 'LOGGED_IN_SALT',   '[@w=&X&${fvcm4il}*o81&[qm=iJ3FMP@yYHi]0+3F:7X3n*wsr|A3V}6IB:jrr6' );
define( 'NONCE_SALT',       ':e.{MYx]hHEj2-vuzPPKn+?pO}rG{7AeH:lEki-^-ED)x_Igem-EL68*y-+ P:Xg' );

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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

define('JWT_AUTH_SECRET_KEY', 'DFGHJKUYTRED');

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
