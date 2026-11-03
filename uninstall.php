<?php
/**
 * Remove French Typo data when the plugin is deleted from the Plugins screen.
 *
 * @package French_Typo
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

/**
 * Delete the options of the current site.
 *
 * @since 1.2.5
 */
function french_typo_uninstall_site() {
	delete_option( 'french_typo_options' );
	delete_option( 'french_typo_mlp_notice_dismissed' );
}

if ( is_multisite() ) {
	foreach ( get_sites( array( 'fields' => 'ids' ) ) as $french_typo_site_id ) {
		switch_to_blog( $french_typo_site_id );
		french_typo_uninstall_site();
		restore_current_blog();
	}
} else {
	french_typo_uninstall_site();
}
