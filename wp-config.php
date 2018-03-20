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
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'Prysmian2026');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

define('FS_METHOD', 'direct');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
/**
define('AUTH_KEY',         'put your unique phrase here');
define('SECURE_AUTH_KEY',  'put your unique phrase here');
define('LOGGED_IN_KEY',    'put your unique phrase here');
define('NONCE_KEY',        'put your unique phrase here');
define('AUTH_SALT',        'put your unique phrase here');
define('SECURE_AUTH_SALT', 'put your unique phrase here');
define('LOGGED_IN_SALT',   'put your unique phrase here');
define('NONCE_SALT',       'put your unique phrase here');
*/

define('AUTH_KEY',         'VbXDe-~z-aT8s`p<r?2+,LNo?rPIL3s8OsE:H&+a_7RaHA;)@%Si_$rML*/ }|P-');
define('SECURE_AUTH_KEY',  'g>8^X/Z7:;dUdm<w-]`;q;-6o1D w*/x-5:1gJ4z#Us<Eyqr:[2:uMk,{ICHx$Gy');
define('LOGGED_IN_KEY',    'n2WiGvYOd6F(fr1>e>ViQ$*8@rHC+lP<]O3/TH?p#d4D]8Va+X=,Sih`?Hg T2m ');
define('NONCE_KEY',        '5Ct+U _Q4 K+[xu=p<Z-k|JNe|@KBTCWwo(HO.1!Mij`M|ncLlbx%?S#pZS+gns!');
define('AUTH_SALT',        'thiQk:> C#4Z<q[uF+hiC#7Yc889+oP)#+]DUCwZbjx+G+&.KXlMfrM:r/|IANpf');
define('SECURE_AUTH_SALT', 'zeqdVQUu9;dC>#WI6O>t^5n9--lOOW|L6(q-l$ScN?FSxMaUpJ=:`L4i[] T*Md]');
define('LOGGED_IN_SALT',   'NZ*@D2P4S!rku}wgh5;ZfFMnHJ40 P`[ayC|coQXnDdWq:vCW8lkp[9AIZ`5x+Py');
define('NONCE_SALT',       'k-lDgR$O!!j7Z-+Q+CS#0&KjV.LA+1uR-m1PUyJj^6qUfN!Yx<y9xHoAGe9dl;Mo');

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
