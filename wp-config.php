<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         '~!L )[zG8=EnX+v9-&SE 99;qi}{?Vkh|/s)kt7}nDW<N_^NPQj#+CL/Yx`G7Mpb');
define('SECURE_AUTH_KEY',  'wEZ{06t6:%N*_fcT%c+*[`]^asoR`R77c|^0cpPyO^g>,GuOs++A=$W$^a]jOE4B');
define('LOGGED_IN_KEY',    '($QoFgwzKb=}x:ifhYF3NB)jF/y1vr:Bfq[eJE+-`|OS-O}EkDCjrT4x9Z-(2-&R');
define('NONCE_KEY',        'F+Q)Y;^0G:bL;-FbiHgf+`(uOK(TvH=a9sib>iHG^HkFm,gy]8aL;9N+|X4Y+c6|');
define('AUTH_SALT',        '1Pw_]ERZkL^Ns]29rg?=|B.^I{Xpj3}pO +Nv,RQBViJhhD]-axisH%$!bOM7;Qf');
define('SECURE_AUTH_SALT', '|Xu//)!we0An.7@iX9jVwA B/kE<feD%_#i=Lw`8|`gvfw3JS?+PVt6CfPm!gC!-');
define('LOGGED_IN_SALT',   '0IG6een 8NiAg#Y>v>Kfnko32r^p$k>4ZFH,zX1|M_=JGc$@;<p+~U]Js-7WAN|&');
define('NONCE_SALT',       '?&/9iU9Aze;?YAk4<sQI#jLl(&S6@xB,_6> DrjEN,F;Hwk!FB!}lj>yQq:BCwiI');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
