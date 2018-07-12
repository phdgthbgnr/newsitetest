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
define('DB_NAME', 'newsite');

/** MySQL database username */
define('DB_USER', 'newsite');

/** MySQL database password */
define('DB_PASSWORD', 'PXpPWrSZeVcrxTsw');

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
define('AUTH_KEY',         ':xBn&10(&zlfunlY+7thX2p_B/-JS4DF/@ sOr<z-+La!n4`9,&vi,Si4/+AHL[,');
define('SECURE_AUTH_KEY',  'A$:/6P)9zYtLQ@s|T@|sD~>^CQNbH3y9)Tq?T|[>Euy|xQvXCYv-gn=k~&;40-r~');
define('LOGGED_IN_KEY',    'qJ4cG!w<2T^<TzrjQp6[4VV:<?~ps0EK6vill!69LN;3K|<&R$$S|+(Szv(@7O.$');
define('NONCE_KEY',        '(G<qpU&wml;Z;+EAZaIU):V!jW7BswXm}=i0ETS?e-<|f4B3$/H310U)txIZ.5?[');
define('AUTH_SALT',        '|/c@63t.$:?NTs[p*~|IP|Gvg5Lt1cS+;vT.r@$d *G$-]a-ww|*rNA6?Rk]+dcs');
define('SECURE_AUTH_SALT', 'H^]dYBNg_7HE)B[[d!~GBxgt7--B)s(h(89nc#(<>wQE_Rn=B|k)V2CU4G4|$JFf');
define('LOGGED_IN_SALT',   'hJ<C7UxV6k+jf96/n!|fFM-68+RS^]Fu;//0]cW7^1fdsCB-.!W[X5ZMYKg~t?8+');
define('NONCE_SALT',       'G(ZNp!Amx$HX=3,ZQ9%uX[,bUM-25GV  &<LLcz!SdykH1jm&RWlN?Ge`zYgdWk`');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'ns_';

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
