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
define('DB_NAME', 'waverly');

/** MySQL database username */
define('DB_USER', 'waverly');

/** MySQL database password */
define('DB_PASSWORD', 'waverly!');

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
define('AUTH_KEY',         '7F!~ J1_iFpwuN9e8lRZccN=+RKrY:*:qD-~y=kL3$[|x&yb:E&#Gi :&. :,H5.');
define('SECURE_AUTH_KEY',  'GYGM(^]y@#VU0$xBGzX4+j?md^W`::(7h//+V.Ti6b@RYmc}}{3|g<>`B`x)miQv');
define('LOGGED_IN_KEY',    '(p#1zK{Z(hdtW@scExrJ2(eF4:_{Mh>^@M4A`1]C;nR9$*$HxU|vGZGn15$9MyY3');
define('NONCE_KEY',        '2C99lN8>eLoPcVw`31=MSV9]W-q<f4(lU&+.)#d0VatI I?m,*[!/@J:#76]e !j');
define('AUTH_SALT',        'B{A<1$2A8o]VH{LlP{]-E8.Ct2(b*P@ u&9$iBr0lc7crX,zpL_,pxn!3>Dx:_bB');
define('SECURE_AUTH_SALT', 'h>I3Rx`#?gTzU7e]_z^aRW|D$ 55&^pGO+`fNI(xGg[:0IoF~5:5>Ep^h}/^Y;6>');
define('LOGGED_IN_SALT',   '-QL)&mJ$.02dN[2 =i>^0CI9LDO}f3gZ#/?9@Q:{@Q=7x1v]_rBa|<yw!2?xI{h2');
define('NONCE_SALT',       'h/)A_n8!>].kA1Iqp|vs6$aJjiOEO3#|P=Y,4#|.lfea[e^:-ReDmU1#A{0)/m{#');

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
