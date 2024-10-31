<?php
/**
 * Activation welcome screen.
 *
 * @package Next Page Caching
 */

if ( ! defined( 'ABSPATH' ) ) { exit; // Exit if accessed directly.
}

if ( ! class_exists( 'NPC_Admin_Welcome' ) ) {
	class NPC_Admin_Welcome {
		function __construct() {
			add_action( 'admin_menu', array( $this, 'create_admin_menu' ) );
			add_action( 'activated_plugin', array( $this, 'redirect_to_welcome_page' ) );
		}

		/**
		 * Creates the admin menu item.
		 */
		public function create_admin_menu() {
			add_submenu_page(
				'options-general.php', // Parent slug.
				esc_html__( 'Next Page Caching', 'next-page' ), // Page title.
				esc_html__( 'Next Page Caching', 'next-page' ), // Menu title.
				'manage_options', // Permissions.
				'next-page-caching', // Slug.
				array( $this, 'create_admin_page' ) // Page creation function.
			);
		}

		/**
		 * Redirect to our welcome page after activation.
		 *
		 * @param string $plugin The path to the plugin that was activated.
		 */
		public function redirect_to_welcome_page( $plugin ) {
			if ( plugin_basename( NPC_FILE ) === $plugin ) {
				wp_redirect( esc_url( admin_url( 'options-general.php?page=next-page-caching' ) ) );
				die();
			}
		}

		public function create_admin_page() {
			?>
			<div class="wrap about-wrap">
				<h1><?php esc_html_e( 'Hey there! Welcome to Next Page Caching.', 'next-page' ) ?></h1>
				<p style="font-size: 1.2em; opacity: .8; font-style: italic;"><?php esc_html_e( 'Thanks for installing my plugin, let me explain to you very quickly what this plugin does:', 'next-page' ) ?></p>
				<div class="welcome-panel">
					<h3 style="margin: 0;"><?php esc_html_e( "\"Too Long, Didn't Read\" (TLDR) Summary:", 'next-page' ) ?></h3>
					<h4 style="margin-bottom: 0;"><?php esc_html_e( 'What is this?', 'next-page' ) ?></h4>
					<p style="margin-top: 0;">
						<?php printf( esc_html__( 'Your website visitors usually jump from page to page, or read your blog posts one after the other. Current caching plugins perform caching on the %scurrent page%s you are on. This plugin caches the %snext page%s your visitor will go to. This means your visitors can navigate your website faster!', 'next-page' ), '<strong>', '</strong>', '<strong>', '</strong>' ) ?>
					</p>
					<h4 style="margin-bottom: 0;"><?php esc_html_e( 'How to use', 'next-page' ) ?></h4>
					<p style="margin-top: 0;">
						<?php esc_html_e( 'Edit each page or post, and modify the Next Page Caching Settings below the content editor. Don\'t worry, it\'s only 2 options! After filling those settings, Next Page caching should now work. Some caching methods are done automatically - like caching the first blog post while browsing your blog page.', 'next-page' ) ?>
						(<a href="#what-kinds-of-caching"><?php esc_html_e( 'learn more about what\'s being cached', 'next-page' ) ?></a>)
					</p>
					<h4 style="margin-bottom: 0;"><?php esc_html_e( 'Important Notice', 'next-page' ) ?></h4>
					<ul style="margin-top: 0;">
						<li style="margin-bottom: 0">&middot; <?php printf( esc_html__( '%sDo not replace%s your current caching plugin with this one, use this %stogether%s with your existing one.', 'next-page' ), '<strong>', '</strong>', '<strong>', '</strong>' ) ?></li>
						<li style="margin-bottom: 0">&middot; <?php printf( esc_html__( 'This plugin %sdoes not predict / analyze%s where your visitors will go next, it will need your help with this via the settings on each page/post', 'next-page' ), '<strong>', '</strong>' ) ?></li>
						<li style="margin-bottom: 0">&middot; <?php printf( esc_html__( 'This plugin %sonly has benefits in Modern Browsers%s', 'next-page' ), '<strong>', '</strong>' ) ?></li>
					</ul>
				</div>
				<p style="font-size: 1.2em; opacity: .8; font-style: italic; margin-top: 4em;"><?php esc_html_e( 'If you want to know more details, please read below:', 'next-page' ) ?></p>
				<div class="welcome-panel" id="how-it-works">
					<h3 style="margin: 0;"><?php esc_html_e( "How Does This Work?", 'next-page' ) ?></h3>
					<p><?php esc_html_e( 'Next Page Caching implements awesome "next page" caching to your site - this is different from your typical caching plugins like WP Rocket and W3 Total Cache.', 'next-page' ) ?></p>
					<p><?php esc_html_e( 'Instead of only trying to serve the current page faster to your visitors, this plugin lets your browser load parts of the next page while the visitor is busy reading the current page he/she is on.', 'next-page' ) ?></p>
					<p><?php esc_html_e( 'It does this with your help. We ask you two things in every post/page: First is what you think the next webpage your visitor will most likely go to afterwards, and second is a short list of critical files that you think should be loaded first in your page to make the top part of the page become visible quickly.', 'next-page' ) ?></p>
					<p><?php esc_html_e( 'With those two items, the plugin can now preload the important parts of the next page the visitor will go to. The plugin also prioritizes the loading of the chosen critical files to ensure that the next page is visible as soon as possible.', 'next-page' ) ?></p>
					<p><?php esc_html_e( 'This is not meant to replace your current caching plugin, but instead, this is meant to be used alongside it.', 'next-page' ) ?></p>
					<p><?php printf( esc_html__( 'If you want to know more about how the plugin works, here\'s a good primer about %sResource Hints%s.', 'next-page' ), '<a href="https://www.keycdn.com/blog/resource-hints/" target="_blank">', '</a>' ) ?></p>
				</div>
				<div class="welcome-panel">
					<h3 style="margin: 0;"><?php esc_html_e( "How Much Speed Benefits Will This Bring?", 'next-page' ) ?></h3>
					<p><?php esc_html_e( 'You can probably expect an loading time decrease to the next page anywhere from 100ms to 500ms depending on the situation.', 'next-page' ) ?></p>
					<p><?php esc_html_e( 'However, the benefits with this plugin is less on the page loading time, but it\'s more on the page interactivity time - the next page will feel faster since the critical parts prioritized during loading and the visitor can interact with the page sooner. This is opposed to the visitor waiting on a white screen while waiting for the page, featured image, or Google Fonts to finish loading.', 'next-page' ) ?></p>
					<p><?php esc_html_e( 'Since we\'re essentially loading parts of the next page within the current page, normal speed testing tools like YSlow, Pingdom Tools or GTMetrix will not show much difference - those only test the speed of the current page you\'re at. What you need is to test the speed from transitioning between one page to the next.', 'next-page' ) ?></p>
					<p><?php esc_html_e( 'You can open an incognito window, disable caching and check the network tab while navigating through your site.', 'next-page' ) ?></p>
				</div>
				<div class="welcome-panel" id="what-kinds-of-caching">
					<h3 style="margin: 0;"><?php esc_html_e( "What Caching Does This Do?", 'next-page' ) ?></h3>
					<ul>
						<li>&middot; <?php esc_html_e( 'The plugin prefetches the main HTML of the chosen next page, as well as the critical files specified.', 'next-page' ) ?></li>
						<li>&middot; <?php esc_html_e( 'Prefetches the first post when viewing a blog list or archive page.', 'next-page' ) ?></li>
						<li>&middot; <?php esc_html_e( 'Preloads the post\'s chosen critical files to prioritize them.', 'next-page' ) ?></li>
						<li>&middot; <?php esc_html_e( 'Preloads the theme stylesheet so that it gets loaded first.', 'next-page' ) ?></li>
						<li>&middot; <?php esc_html_e( 'Preloads the featured image of a blog post or page when needed (and if it\'s large) so that it shows up faster.', 'next-page' ) ?></li>
						<li>&middot; <?php esc_html_e( 'Preconnects to the Google Fonts domain for faster font downloading when needed.', 'next-page' ) ?></li>
					</ul>
				</div>
			</div>
			<?php
		}
	}

	new NPC_Admin_Welcome();
}
