<?php
/**
 * Class that adds prefetch/preconnect/preload link tags.
 * Use `add_link` to add a URL and the class should handle the echoing
 * in the <head> or <body> tag on its own.
 *
 * Do not directly use this to add link tags. Use the function helpers like
 * `npc_add_prefetch()`
 *
 * - Multiple URLs will only echo once.
 * - If prefetch and preload is used, preload is prioritized.
 *
 * @package Next Page Caching
 */

if ( ! defined( 'ABSPATH' ) ) { exit; // Exit if accessed directly.
}

if ( ! class_exists( 'NPC_Browser_Hints' ) ) {
	class NPC_Browser_Hints {

		public static $head_is_done = false;

		/**
		 * Checker whether we have already added the url or not.
		 * @var array
		 */
		public static $all_urls_loaded = array();

		public static $head_link_attrs = array();
		public static $footer_link_attrs = array();

		function __construct() {
			require_once( 'funcs-helper-links.php' );
			add_action( 'wp_head', array( $this, 'add_head_links' ), 99999 );
			add_action( 'wp_footer', array( $this, 'add_footer_links' ), 99999 );
		}

		public function add_head_links() {
			self::$head_is_done = true;

			foreach ( self::$head_link_attrs as $attrs ) {
				echo npc_form_link_tag( $attrs );
			}
		}

		public function add_footer_links() {
			foreach ( self::$footer_link_attrs as $attrs ) {
				echo npc_form_link_tag( $attrs );
			}
		}

		public static function add_link( $npc_url ) {
			if ( in_array( $npc_url['href'], self::$all_urls_loaded ) ) {
				if ( $npc_url['rel'] === 'preload' ) {
					self::replace_link( $npc_url );
				}
				return;
			}
			self::$all_urls_loaded[] = $npc_url['href'];

			if ( ! self::$head_is_done ) {
				self::$head_link_attrs[] = $npc_url;
			} else {
				self::$footer_link_attrs[] = $npc_url;
			}
		}

		public static function replace_link( $npc_url ) {
			foreach ( self::$head_link_attrs as $i => $attrs ) {
				if ( $attrs['href'] === $npc_url['href'] ) {
					self::$head_link_attrs[ $i ] = $npc_url;
					return;
				}
			}
			foreach ( self::$footer_link_attrs as $i => $attrs ) {
				if ( $attrs['href'] === $npc_url['href'] ) {
					self::$footer_link_attrs[ $i ] = $npc_url;
					return;
				}
			}
		}
	}

	new NPC_Browser_Hints();
}
