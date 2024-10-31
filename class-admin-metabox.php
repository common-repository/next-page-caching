<?php
/**
 * Admin Metabox settings.
 *
 * @package Next Page Caching
 */

if ( ! defined( 'ABSPATH' ) ) { exit; // Exit if accessed directly.
}

if ( ! class_exists( 'NPC_Admin_Metabox' ) ) {
	class NPC_Admin_Metabox {
		function __construct() {
			add_action( 'add_meta_boxes', array( $this, 'add_metabox' ) );
			add_action( 'save_post', array( $this, 'save_metabox' ), 10, 2 );
		}

		/**
		 * Adds the meta box.
		 */
		public function add_metabox() {
			add_meta_box(
				'next-page-caching',
				__( 'Next Page Caching', 'next-page' ),
				array( $this, 'render_metabox' ),
				'page',
				'advanced',
				'default'
			);
			add_meta_box(
				'next-page-caching',
				__( 'Next Page Caching', 'next-page' ),
				array( $this, 'render_metabox' ),
				'post',
				'advanced',
				'default'
			);
		}


		/**
		 * Renders the meta box.
		 */
		public function render_metabox( $post ) {
			wp_enqueue_script( 'wplink' );
			wp_enqueue_script( __CLASS__, plugins_url( 'dist/npc.min.js', __FILE__ ), array(), VERSION_NPC );
			wp_enqueue_style( __CLASS__, plugins_url( 'src/npc.css', __FILE__ ), array(), VERSION_NPC );

			// Add nonce for security and authentication.
			wp_nonce_field( 'npc_save_action', 'npc_save_action' );

			$permalink = get_permalink();
			if ( get_post_status() === 'auto-draft' ) {
				$permalink = '';
			}

			$next_url = get_post_meta( $post->ID, 'npc_next_url', true );
			$critical_urls = get_post_meta( $post->ID, 'npc_critical_urls', true );
			if ( ! is_array( $critical_urls ) ) {
				$critical_urls = array();
			}

			?>
			<div class="npc-meta">
				<p class="description"><?php esc_html_e( 'Prioritize loading of some files for this page, and pre-fetch the files of the next page visitors might visit.', 'next-page' ) ?></p>
				<p class="description"><?php esc_html_e( 'Preloading and pre-fetching files will allow your visitors to browse through your site faster and encounter less loading times.', 'next-page' ) ?></p>

				<p class="npc_title">
					<label class="post-attributes-label" for="npc_next_url"><?php esc_html_e( 'Prefetch Next Page URL', 'next-page' ) ?></label>
				</p>
				<div id="npc_next_url_area">
					<input name="npc_next_url" type="url" id="npc_next_url" placeholder="http://" value="<?php echo esc_url( $next_url ) ?>" />
					<a class="button" id="npc_next_url_button"><?php esc_html_e( 'Pick URL', 'next-page' ) ?></a>
				</div>
				<p class="description"><?php esc_html_e( 'Enter the URL you think your visitors would most likely go to next while viewing this webpage. You can use Google Analytics or other visitor tracking methods to check where your visitors navigate to from here.', 'next-page' ) ?></p>

				<p class="npc_title">
					<label class="post-attributes-label" for="npc_critical_urls"><?php esc_html_e( 'Preload These Files For This Page', 'next-page' ) ?></label>
				</p>
				<?php

				if ( empty( $permalink ) ) {
					?>
					<p class="description"><?php esc_html_e( 'Please save first, refresh the page and try again.', 'next-page' ) ?></p>
					<?php
				} else {
					?>
					<p class="description"><?php esc_html_e( 'This is the list of URLs loaded in the frontend. The ones on the top are estimated to have either the slowest loading time or the largest file size.', 'next-page' ) ?></p>
					<p class="description"><?php esc_html_e( 'Highlight 3-5 URLs below that you think are the critical files that would need to be loaded as soon as possible for this webpage to become visible to your visitors.', 'next-page' ) ?></p>
					<a class="button" id="npc_critical_urls_button"><?php esc_html_e( 'Refresh URL List', 'next-page' ) ?></a><br/>
					<select name="npc_critical_urls[]" id="npc_critical_urls" multiple data-permalink="<?php echo esc_url( get_permalink() ) ?>">
						<?php
						foreach ( $critical_urls as $url ) {
							?>
							<option value="<?php echo esc_url( $url ) ?>" selected><?php echo esc_url( $url ) ?></option>
							<?php
						}
						?>
					</select>
					<?php
				}

				?>
			</div>
			<?php
		}

		/**
		 * Handles saving the meta box.
		 *
		 * @param int     $post_id Post ID.
		 * @param WP_Post $post    Post object.
		 * @return null
		 */
		public function save_metabox( $post_id, $post ) {
			// Add nonce for security and authentication.
			$nonce = isset( $_POST['npc_save_action'] ) ? $_POST['npc_save_action'] : '';

			// Check if nonce is valid.
			if ( ! wp_verify_nonce( $nonce, 'npc_save_action' ) ) {
				return;
			}

			// Check if user has permissions to save data.
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}

			// Check if not an autosave.
			if ( wp_is_post_autosave( $post_id ) ) {
				return;
			}

			// Check if not a revision.
			if ( wp_is_post_revision( $post_id ) ) {
				return;
			}

			$npc_next_url = isset( $_POST['npc_next_url'] ) ? $_POST['npc_next_url'] : '';
			update_post_meta( $post_id, 'npc_next_url', $npc_next_url );

			$npc_critical_urls = isset( $_POST['npc_critical_urls'] ) ? $_POST['npc_critical_urls'] : array();
			update_post_meta( $post_id, 'npc_critical_urls', $npc_critical_urls );
		}

	}

	new NPC_Admin_Metabox();
}
