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
define( 'DB_NAME', 'wordp' );

/** MySQL database username */
define( 'DB_USER', 'wordp_user' );

/** MySQL database password */
define( 'DB_PASSWORD', 'test123' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost:3307' );

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
define( 'AUTH_KEY',         '1Wbc^$n~>#V2NOc8Y-C>@#@eu-`=JQ$[KfbK3|Tbj~4kC,zvzB@f|L*u 9l8~-on' );
define( 'SECURE_AUTH_KEY',  'gSZDP,g?eXPb`dUl$W#$&bHg;.Q=nNN[]7;5TR^7;Mp5:49f.+rvS?^Yuy>Kio`(' );
define( 'LOGGED_IN_KEY',    'o6idu&J9Y+o}BOSm{m.83}g,S&!0%l{+T]/uc2XKx6?$Ks6:B%S#.58SZ%g?1h@f' );
define( 'NONCE_KEY',        'bNp(|00ig;!SsA;z.e10NlS})ka?Cd!R>B gn(s^Z]KC#iEXks$XtN>^ q]Bz0yh' );
define( 'AUTH_SALT',        'w#=]2F}f`#|r0Yi[m7mCw/Wv.2CzEc=XUa,v&Cr|w~.`6v[KR.( qnf?g/:4v3qW' );
define( 'SECURE_AUTH_SALT', '|5L!aSWGUqm;LNbmx.NQFJnH]7*^>&NJ9v?n eI(=Y1 GvU!WP<TSa;Ei9X9hLT|' );
define( 'LOGGED_IN_SALT',   'z+=`LC,QMQ;O|lEo]?!Es+hhBIZRn^XF}a~.+/jsi-gZX{29^,?Ed,P+9^Ay$EV&' );
define( 'NONCE_SALT',       'BzD9K?>Vx%k@ o/&zmvlj.dynWJ[/AAGNIP. ft!0H|LL^~*lZ-k_ClNerMRTm0}' );

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
/*define('WP_ALLOW_MULTISITE',true);*/
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
