<?php
/**
 * Plugin Name:       Job Listing Manager
 * Description:       Manage job postings with custom post types and blocks
 * Version:           0.3.2
 * Requires at least: 6.8
 * Requires PHP:      7.4
 * Author:            AJB
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       job-listing-manager
 *
 * @package JobListingManager
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/includes/post-types.php';
require_once __DIR__ . '/includes/taxonomies.php';
require_once __DIR__ . '/includes/field-groups.php';
require_once __DIR__ . '/includes/expiration.php';
require_once __DIR__ . '/includes/settings.php';
require_once __DIR__ . '/includes/forminator-template.php';
require_once __DIR__ . '/includes/forminator-customizations.php';

function jlm_check_dependencies() {
	add_action( 'admin_notices', 'jlm_dependency_notices' );
}
add_action( 'admin_init', 'jlm_check_dependencies' );

function jlm_dependency_notices() {
	$missing = array();
	
	if ( ! function_exists( 'get_field' ) ) {
		$missing[] = array(
			'name' => 'Secure Custom Fields (SCF)',
			'slug' => 'secure-custom-fields',
			'path' => 'secure-custom-fields/secure-custom-fields.php'
		);
	}
	
	$method = get_option( 'jlm_application_method', 'mailto' );
	if ( $method === 'form' && ! class_exists( 'Forminator' ) ) {
		$missing[] = array(
			'name' => 'Forminator',
			'slug' => 'forminator',
			'path' => 'forminator/forminator.php'
		);
	}
	
	if ( empty( $missing ) ) {
		return;
	}
	
	?>
	<div class="notice notice-warning">
		<p><strong><?php _e( 'Job Listing Manager', 'job-listing-manager' ); ?></strong></p>
		<p><?php _e( 'The following plugins are recommended:', 'job-listing-manager' ); ?></p>
		<ul>
			<?php foreach ( $missing as $plugin ) : 
				$is_installed = file_exists( WP_PLUGIN_DIR . '/' . $plugin['path'] );
				$install_url = wp_nonce_url(
					self_admin_url( 'update.php?action=install-plugin&plugin=' . $plugin['slug'] ),
					'install-plugin_' . $plugin['slug']
				);
				$activate_url = wp_nonce_url(
					self_admin_url( 'plugins.php?action=activate&plugin=' . $plugin['path'] ),
					'activate-plugin_' . $plugin['path']
				);
			?>
				<li>
					<?php echo esc_html( $plugin['name'] ); ?>
					<?php if ( $is_installed ) : ?>
						<a href="<?php echo esc_url( $activate_url ); ?>" class="button button-small"><?php _e( 'Activate', 'job-listing-manager' ); ?></a>
					<?php else : ?>
						<a href="<?php echo esc_url( $install_url ); ?>" class="button button-small"><?php _e( 'Install', 'job-listing-manager' ); ?></a>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
	<?php
}

function jlm_block_init() {
	if ( ! function_exists( 'get_field' ) ) {
		return;
	}
	wp_register_block_types_from_metadata_collection( __DIR__ . '/build', __DIR__ . '/build/blocks-manifest.php' );
	
	if ( file_exists( __DIR__ . '/build/assets/style.css' ) ) {
		wp_enqueue_style( 'jlm-global', plugin_dir_url( __FILE__ ) . 'build/assets/style.css' );
	}
}
add_action( 'init', 'jlm_block_init' );

function jlm_block_categories( $categories, $editor_context ) {
	$custom_category = array(
		'slug' => 'job-listing-blocks',
		'title' => 'Job Listings',
		'icon' => 'businessperson',
	);
	return array_merge( $categories, [ $custom_category ] );
}
add_filter( 'block_categories_all', 'jlm_block_categories', 10, 2 );
