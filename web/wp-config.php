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
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');


define('ADMIN_COOKIE_PATH', '/');
define('COOKIE_DOMAIN', '');
define('COOKIEPATH', '');
define('SITECOOKIEPATH', '');


/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'VhZW.T;JHucPZm-r`?c~V6LCq_IFn6VKFgs&-./[Us7~Dt):*3xy%Q#kI:R+AV!E');
define('SECURE_AUTH_KEY',  '+ULQ&_Cj3Lmn,r1OJXDM w}p[^m_/X+mABxeumx-ftISW{iXP~+fksWz;:t)V[p[');
define('LOGGED_IN_KEY',    'i#u^M}qk3hfw**%NN&3}J7_&0H8^7dv!H5Y<SH9?KOKUT)<2cFlDC&rC2)kDuNrf');
define('NONCE_KEY',        'cfqw1-cru{7,8%>x<V+l0jtW<21rC.KxtX]&-E+XS4|doIoJMW^|(vV_>LcJ^x ,');
define('AUTH_SALT',        '.,gRx6|~`BdSBE5vN97gp^$SL$VV9(Y2:H.!>}aViP/P|BH1OV^5)b6&(s_+tDg}');
define('SECURE_AUTH_SALT', 'vE=2b5]5(mLJc|[dV+no(:P8f!6]+;}{|^?f5&v5Ubt_x-}j@id6+x|l{6t~Q13p');
define('LOGGED_IN_SALT',   'a[1|A6?nej8k<Zg&#d5[|U.;O_q-`%eNN^7?BDbqyPDxk1|]Y$7B,.hea|WVzS/[');
define('NONCE_SALT',       '?HDP.,2<Bu+]g$@l=T`up+JB@v v/RJ?=F#,|$9VMTHtCo/#S-)ye@h$yp.,I[|W');



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
