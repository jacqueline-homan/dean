<?php
function update_option('siteurl', 'http://localhost/in4h.org');
function update_option('home', 'http://localhost/in4h.org');
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

// This environment variable is selectively set in .htaccess

$env = ( isset($_SERVER['APPLICATION_ENV']) && $_SERVER['APPLICATION_ENV'] != null)
    ? strtoupper($_SERVER['APPLICATION_ENV'])
    : "PROD";

define('DB_NAME', $_SERVER[$env."_DB_NAME"]);

/** MySQL database username */
define('DB_USER', $_SERVER[ $env."_DB_USER" ]);

/** MySQL database password */
define('DB_PASSWORD', $_SERVER[$env."_DB_PASSWORD"]);

/** MySQL hostname */
define('DB_HOST', $_SERVER[$env."_DB_HOST"]);

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/** Points the url to the local dev copy not the live site */
define('WP_HOME', 'http://localhost/in4h.org');
define('WP_SITEURL', 'http://localhost/in4h.org');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '%Ouy;w1)_|=Wyl.1XGh0YE~4|,b/VAEbK`TvWAsaUSir6/E+F&f.|P||?92%n}h*');
define('SECURE_AUTH_KEY',  'PU=etS4$&iq4D)z&a#1GU@gi*1%bo<.F1uS(P9fsV1n.Q:8e@R[a2illVq$nCM$w');
define('LOGGED_IN_KEY',    'asoti #0?:Vk9b3ka!fhv=*R8_7bpNNS_}KPN~c#!0SHFXtQNDq+*~6MY.W<55gP');
define('NONCE_KEY',        '%@xe:3x{eD<c7.iK+Krf6M+^wY/RX?zR$BD:;;kiDyn{YOIuYJwa;+4[p~bA=Y~b');
define('AUTH_SALT',        '@4HHv`|v7gn@j;iIf]QkG_}n`]TEoLq-QhuXZ!ed3!l.Y+tF2n^eq%v`%0jG?vlH');
define('SECURE_AUTH_SALT', 'Xvi9!7HJ9SRrgh](dLPB3<8w:n097@<wtbW@h/@,mXJ+[.wBb&zb:tZtIC/T#G!~');
define('LOGGED_IN_SALT',   'ckSIL&Rd;njZ}iye:1|VtE=yIbwnu VbaUqOU~;&8PlPG@Q_`H[B1TcoVZ2TpKFs');
define('NONCE_SALT',       '.*BZ>/?|0<@c<`xn8m{^r|HU>Zc}.C$hkj95WS@}vH8bsGWty^>b<IRB4<vI!|Zt');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'in4h_';

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

define('DISALLOW_FILE_EDIT', true);

define('FORCE_SSL_ADMIN', true);