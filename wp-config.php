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
define( 'DB_NAME', 'u898053536_ibBEI' );

/** Database username */
define( 'DB_USER', 'u898053536_puS97' );

/** Database password */
define( 'DB_PASSWORD', 'E131UwHENs' );

/** Database hostname */
define( 'DB_HOST', '127.0.0.1' );

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
define( 'AUTH_KEY',          'S]9eb8{4eAaIV.>z&zl_9s=YlmaLwi`<DW]8mq.9x13;@.N1Aj#~:}HtKN94>7*g' );
define( 'SECURE_AUTH_KEY',   'wOwB6!#QSu[/sNw?Un3O=C@2Pz]ahN$Z]?jm[;#-s:Z8m@8.)L:h)s>Ag%tlL<=v' );
define( 'LOGGED_IN_KEY',     'l8c]7?v$v]Erk[B[aWW+</>,)q|O(PzC_fK{H} KdTv-{}+&~-be?_i23V}:a(p>' );
define( 'NONCE_KEY',         'sR<u.[D9r-p$gy{fJp3g$IYq|caLS=`=OrdG*.R9<9*6pW:b{2C<VJ$p<C(19Nc)' );
define( 'AUTH_SALT',         'L3B$S/=Xn y]#F8.V,sdW7d>|]lQo&:FVb+~X(9YnA|xJ-#jte!J^4?%y*kYT^ko' );
define( 'SECURE_AUTH_SALT',  'fbSd)ud_?|hI2k*[>fg R4t6momFsd7t_W1X-Jg~ o ?.GS*mBz8Rpgxh{Y-mr,d' );
define( 'LOGGED_IN_SALT',    '}Rgtns5b^qU,UaTRBUN;;}ce60*5I6IqeTg~ehD?rIVR[b1R#IvbO.sZ[xbM5m+b' );
define( 'NONCE_SALT',        'Xbu5=gSSQ!XK[FePJVO>R0z:=~`>G30>dI1eO*GJO[vzFJ|1IEqrK[<e/@xh^j(D' );
define( 'WP_CACHE_KEY_SALT', 'QssNLDdM0#y&:mDm(^dyh[M@{@n|Bl?1-OIt&7kW+ENmlNJan@(;cf?BH>,AcYvq' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


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
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'FS_METHOD', 'direct' );
define( 'COOKIEHASH', 'a0d1c57e0dd99227f49b66a53cc30e3c' );
define( 'WP_AUTO_UPDATE_CORE', 'minor' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
