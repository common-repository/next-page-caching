<?php
/**
 * Handles the addition of prefetch/preconnect/preload link tags depending
 * on the current page.
 *
 * @package Next Page Caching
 */

if ( ! defined( 'ABSPATH' ) ) { exit; // Exit if accessed directly.
}

if ( ! class_exists( 'NPC_Cacher' ) ) {
	class NPC_Cacher {
		function __construct() {
			require_once( 'funcs-helper-links.php' );
			add_action( 'wp_head', array( $this, 'add_theme_preload' ) );
			add_filter( 'wp_get_attachment_image_src', array( $this, 'add_featured_image_preload' ), 10, 4 );
			add_action( 'wp_head', array( $this, 'add_google_fonts_preconnect' ) );
			add_action( 'wp_head', array( $this, 'add_next_page_prefetch' ) );
			add_action( 'wp_head', array( $this, 'add_critical_preloaders' ) );
			add_filter( 'the_posts', array( $this, 'add_first_archive_entry_prefetch' ), 10, 2 );
		}

		/**
		 * Always preload the theme's stylesheet.
		 */
		public function add_theme_preload() {
			npc_add_preload( get_stylesheet_uri() );
		}

		/**
		 * When in a post/page and when a featured image is loaded, preload it.
		 */
		public function add_featured_image_preload( $image, $attachment_id, $size, $icon ) {
			if ( ! is_page() && ! is_single() ) {
				return $image;
			}
			if ( ! has_post_thumbnail() ) {
				return $image;
			}
			if ( get_post_thumbnail_id() !== $attachment_id ) {
				return $image;
			}
			if ( preg_match( '/thumbnail/', $size ) ) {
				return $image;
			}

			// Check if the image is big enough. I'm guessing more than 700x700
			if ( isset( $image['0'] ) && isset( $image['1'] ) && isset( $image['2'] ) )  {
				if ( $image['1'] >= 700 && $image['2'] >= 700 ) {
					npc_add_preload( $image['0'] );
				}
			}

			return $image;
		}

		/**
		 * Preconnect to Google Fonts to make them faster.
		 */
		public function add_google_fonts_preconnect() {
			npc_add_preconnect( 'https://fonts.gstatic.com' );
		}

		/**
		 * Prefetch the next page (meta setting),
		 * if the next page is a post/page, prefetch its critical files too.
		 */
		public function add_next_page_prefetch() {
			if ( ! is_page() && ! is_single() ) {
				return;
			}

			$next_page_url = get_post_meta( get_the_ID(), 'npc_next_url', true );
			if ( empty( $next_page_url ) ) {
				return;
			}
			npc_add_prefetch( $next_page_url );

			// Also prefetch the next page.
			$post_id = url_to_postid( $next_page_url );
			if ( ! empty( $post_id ) ) {
				$this->prefetch_post( $post_id );
			}
		}

		/**
		 * Prefetches the critical files of a post.
		 */
		public function prefetch_post( $post_id ) {
			$critical_urls = get_post_meta( $post_id, 'npc_critical_urls', true );
			if ( is_array( $critical_urls ) ) {
				foreach ( $critical_urls as $url ) {
					npc_add_prefetch( $url );
				}
			}
		}

		/**
		 * Preload the critical files of the current post.
		 */
		public function add_critical_preloaders() {
			if ( ! is_page() && ! is_single() ) {
				return;
			}

			$critical_urls = get_post_meta( get_the_ID(), 'npc_critical_urls', true );
			if ( is_array( $critical_urls ) ) {
				foreach ( $critical_urls as $url ) {
					npc_add_preload( $url );
				}
			}
		}

		/**
		 * Prefetch the first archive post entry when viewing archive pages.
		 */
		public function add_first_archive_entry_prefetch( $posts, $query ) {
			if ( is_archive() || is_category() || is_home() ) {
				if ( $query->is_archive || $query->is_category || $query->is_home ) {
					if ( count( $posts ) ) {
						$post_id = $posts[0]->ID;
						npc_add_prefetch( get_permalink( $post_id ) );
						$this->prefetch_post( $post_id );
					}
				}
			}

			return $posts;
		}
	}

	new NPC_Cacher();
}
