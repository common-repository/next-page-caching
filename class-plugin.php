<?php
/**
Plugin Name: Next Page Caching
Description: Speed up the loading of the NEXT page your visitors will go to.
Author: Gambit Technologies, Inc
Version: 0.1
Author URI: http://gambit.ph
Plugin URI: http://wordpress.org/plugins/next-page-caching
Text Domain: next-page
Domain Path: /languages
 *
 * The main plugin file.
 *
 * @package Next Page Caching.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; // Exit if accessed directly.
}

defined( 'VERSION_NPC' ) || define( 'VERSION_NPC', '0.1' );
defined( 'NPC_FILE' ) || define( 'NPC_FILE', __FILE__ );

require_once( 'class-admin-metabox.php' );
require_once( 'class-cacher.php' );
require_once( 'class-browser-hints.php' );
require_once( 'class-admin-welcome.php' );
require_once( 'funcs-helper-links.php' );
