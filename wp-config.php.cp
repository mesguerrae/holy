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
//  include "../inc/dbinfo.inc"; 

$db_name = DB_DATABASE;

$db_host = DB_SERVER;

$db_username = DB_USERNAME;

$db_password = DB_PASSWORD;

define( 'WP_HOME', 'https://www.holycosmetics.com.co' );
define( 'WP_SITEURL', 'https://www.holycosmetics.com.co' );
define('ENABLE_CACHE', true);
//define('WP_REDIS_HOST', '127.0.0.1');
define('WP_REDIS_HOST', 'holy-redis-cache.sygh2h.ng.0001.use2.cache.amazonaws.com');
//echo $db_name. " ".$db_host." ".$db_username." ".$db_password;exit;
define('DB_NAME','holycosm_HolyWP');

/** MySQL database username */
define('DB_USER', 'holy_master');

/** MySQL database password */
define('DB_PASSWORD', 'W3t25RTGmDgzXqyt');
    

/** MySQL hostname */
define('DB_HOST', 'holy-db-instance.cxaimoc4sbcf.us-east-2.rds.amazonaws.com');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

    
    define('WP_DEBUG', true);
/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'l?2M~z uFQc5jq^~EK4zYMv|iuuJFq`a,(wK_A)Ae^h#.VmU6>@<|]wL<. bI^w,');
define('SECURE_AUTH_KEY',  '|e8]O}Va5MKB<Z&6D//$ uA,T>?fDZ&hnAo):aB_(S|-#ME# >4b4$)y_t+IB;i}');
define('LOGGED_IN_KEY',    'fl3i16@|M)ho4a).VWqLY7 #^N3t:l,hl]?E.j%/WF{etr1i>X.jjPawR+5sBAk[');
define('NONCE_KEY',        'Su1W#?BWA}.A3Y_)Y5pK,Drs+c<qy7f^f^{w}AoC7`N=ow|l,l7p]$-U$Q)=vScd');
define('AUTH_SALT',        '-a)_#x3:%%ot6.m7Qy~G~[QxAwhk_=)UY+ymm(gf,aaSOhk^IH%Co4oW6j;=hrd{');
define('SECURE_AUTH_SALT', 'vMtRUFl; l}iBH0%ZksIvRD_]+(7V)lTfLXwP$[{?sC$w4b=y=#Kf2Xj,eErl8|p');
define('LOGGED_IN_SALT',   'u2<sKzeD;6@IxqC^9S,*%,?x_er8]$Z{~;;|6q+6)z|5*baDaXK&J{Z3Wm;B]:z9');
define('NONCE_SALT',       'rEt|,T:2s3iwjO?6`bu][rA<V_E FRVvO-}IZuICWd7+T??T&VD=oUIh3S;lRPq|');
define('FS_METHOD', 'direct');

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
//define('WP_DEBUG', true);
//define( 'WP_DEBUG_LOG', true );
/* That's all, stop editing! Happy blogging. */


/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');


/** Sets up WordPress vars and included files. */

if (strpos($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') !== false)
       $_SERVER['HTTPS']='on';

require_once(ABSPATH . 'wp-settings.php');

/*$isSecure = false;
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
    $isSecure = true;
}
elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
    $isSecure = true;
}*/
//$REQUEST_PROTOCOL = $isSecure ? 'https://' : 'http://';
//define( 'WP_CONTENT_URL', $REQUEST_PROTOCOL.$_SERVER['HTTP_HOST'] . '/wp-content');
//define( 'WP_HOME', $REQUEST_PROTOCOL.$_SERVER['HTTP_HOST'] );

define('FORCE_SSL_LOGIN', false);
define('FORCE_SSL_ADMIN', false);

