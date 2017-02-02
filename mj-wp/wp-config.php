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
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'mj-wp');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'XJ;9mF&ky^pRz+[s7xPH5LK;`PFtQG6R:LF7K$;{dk* ]fQlLDC5vMmr$G/RT# +');
define('SECURE_AUTH_KEY',  ';LG_@A%gKcRQ?|$S;QUN=OrTc7F~> 5$A9?afR0:W#~!3Vq$VaFluQhBdGpP%{6{');
define('LOGGED_IN_KEY',    '^QOtu#=3+64`mu9^ 4!w[ib2 1C7gR<:m#KGOcY+ b0|wNRa6`/V{9Wlk6;^Q)Rb');
define('NONCE_KEY',        '{6`Mog1.Vd=R8O0)tk_(X?&ZfiVlPtq}4f_]x/fAhNa0Q.hf(J:Cb3tQscshBodh');
define('AUTH_SALT',        '9j>H,6b>`Q7FA;TkyS1Yik.4s*Lw+`G+mjD:l@Eokhfz5t*1~2hy*7bh_2xTzHQ&');
define('SECURE_AUTH_SALT', 'a=+2=l)jDU[5uNcl.YljsEN,aGp==twINFgg&`n-::kUA[ j!n$`-O5N4hQ~]IBb');
define('LOGGED_IN_SALT',   'eXb)nFPw&&QeVxJJ[<k#vg]51?AM8B!5pe_W*}VKp ex$s0+R^-C9Pgf>@s_2BgS');
define('NONCE_SALT',       '36f/G^wLZAF|9s|Du|SpU|83`TdxfiSLb^c{`^Wmo@C#AqneF=)2EQHwMa3W;ce?');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
