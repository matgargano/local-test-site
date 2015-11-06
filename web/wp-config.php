<?php

require __DIR__ . '/../vendor/autoload.php';
$dotenv = new Dotenv\Dotenv( dirname( __DIR__ ) );
$dotenv->load();
$dotenv->required( array( 'DB_NAME', 'DB_USER', 'DB_PASSWORD', 'WP_HOME', 'WP_SITEURL' ) );


// ** MySQL settings ** //
/** The name of the database for WordPress */
define( 'DB_NAME', $_ENV['DB_NAME'] );

/** MySQL database username */
define( 'DB_USER', $_ENV['DB_USER'] );

/** MySQL database password */
define( 'DB_PASSWORD', $_ENV['DB_PASSWORD'] );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );
define( 'CONCATENATE_SCRIPTS', false );

define( 'AUTH_KEY', 'qlZnGlT6hqGE`(]%:J>AK$dj-em7u3|qj+w]grF?j;Wi4p2NpuFy}+4T9*1KhZX=' );
define( 'SECURE_AUTH_KEY', 'U$PWy<r|BjbvL`|2P*<H,_trx y!-v*m=WQa/h[z&JG^swYq-]c9K}iq<[1d=L-S' );
define( 'LOGGED_IN_KEY', 'w~!:S#k=)%$+`[257  /s3wfLI7Kc@NV.]U]d{4CfS&#P}7Y_*E;XjZ[:jkH--W4' );
define( 'NONCE_KEY', 'RPtx+/R8<nwe%-(j|3UE*pihhYB=)H3+{Z_]_{+Z9s%^**jflY5rdU[)+xER2{W:' );
define( 'AUTH_SALT', 'wR^sq2-{-|9xL+;6u8cd?Ph/Q%>m:ue2yoJf}i7ADvU.WYqXr|)|NT6U9N0X-#DR' );
define( 'SECURE_AUTH_SALT', 'C.c;S%wb9F.$_HL7g21R.mimUw P.5tzSR0B[r$lkAJkSXNmey|~*Skjjc8 a^wq' );
define( 'LOGGED_IN_SALT', 'oo]#]{&,Obl4`d)sFtak+ SA=Hbx3(|.9)1@)TS#*5O#t(Pw[U)MHaF p*w6o$HE' );
define( 'NONCE_SALT', 'c$pAMeXi!=G5kk&RU+&x/*.!6/6C__<Mx?l*s^zbhWcm.5gqc+3M( P%R2z[a=^&' );


$table_prefix = 'wp_';

define( 'WP_CONTENT_DIR', dirname( __FILE__ ) . '/app' );
define( 'WP_CONTENT_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/app' );
define( 'WPMU_PLUGIN_DIR', dirname( __FILE__ ) . '/app/mu-plugins' );
define( 'WPMU_PLUGIN_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/app/mu-plugins' );

define( 'WP_HOME', $_ENV['WP_HOME'] );
define( 'WP_SITEURL', $_ENV['WP_SITEURL'] );

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
