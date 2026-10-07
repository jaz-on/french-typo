<?php
/**
 * french_typo_hooks() really attaches the expected callbacks, at the expected priority and arity.
 * A renamed hook, a changed priority or a wrong argument count fails here instead of passing CI.
 *
 * @package French_Typo
 */

class HooksTest extends FrenchTypoTestCase {

	/**
	 * Run french_typo_hooks() and return the recorded registrations keyed by hook name.
	 *
	 * @return array<string, array<int, array>>
	 */
	private function registered(): array {
		$GLOBALS['french_typo_test_hooks'] = array();
		french_typo_hooks();
		$by_hook = array();
		foreach ( $GLOBALS['french_typo_test_hooks'] as $entry ) {
			$by_hook[ $entry['hook'] ][] = $entry;
		}
		return $by_hook;
	}

	private function assertHook( array $by_hook, string $hook, string $callback, int $priority = 10, int $args = 1 ): void {
		$this->assertArrayHasKey( $hook, $by_hook, "hook {$hook} is not registered" );
		$matches = array_filter(
			$by_hook[ $hook ],
			static function ( $entry ) use ( $callback, $priority, $args ) {
				return $entry['callback'] === $callback && $entry['priority'] === $priority && $entry['accepted_args'] === $args;
			}
		);
		$this->assertNotEmpty( $matches, "{$hook} should call {$callback} at priority {$priority} with {$args} argument(s)" );
		$this->assertTrue( function_exists( $callback ), "{$callback} must exist" );
	}

	public function test_plugin_file_registers_its_two_init_hooks(): void {
		$by_hook = array();
		foreach ( $GLOBALS['french_typo_test_load_hooks'] as $entry ) {
			$by_hook[ $entry['hook'] ][] = $entry;
		}
		$this->assertHook( $by_hook, 'init', 'french_typo_load_textdomain', 0 );
		$this->assertHook( $by_hook, 'init', 'french_typo_hooks' );
	}

	/**
	 * @dataProvider core_filter_provider
	 */
	public function test_core_filters_are_attached( string $hook, string $callback ): void {
		$this->assertHook( $this->registered(), $hook, $callback );
	}

	public function core_filter_provider(): array {
		$wrapper = 'french_typo_replace_wrapper';
		$cases   = array();
		foreach ( array(
			'the_title', 'the_content', 'the_excerpt',
			'widget_text', 'widget_text_content', 'widget_block_content', 'widget_title', 'widget_text_title', 'widget_block_title',
			'wp_nav_menu_items',
			'term_description', 'single_term_title', 'single_cat_title', 'single_tag_title', 'single_post_type_archive_title',
			'get_the_archive_title', 'get_the_archive_description',
			'comment_text', 'get_comment_author',
			'get_the_author_description',
		) as $hook ) {
			$cases[ $hook ] = array( $hook, $wrapper );
		}
		return $cases + array(
			'the_title_rss'            => array( 'the_title_rss', 'french_typo_replace_rss_title' ),
			'the_content_feed'         => array( 'the_content_feed', 'french_typo_replace_rss_content' ),
			'the_excerpt_rss'          => array( 'the_excerpt_rss', 'french_typo_replace_rss_excerpt' ),
			'comment_text_rss'         => array( 'comment_text_rss', 'french_typo_replace_rss_comment' ),
			'rest_prepare_post'        => array( 'rest_prepare_post', 'french_typo_rest_api_post' ),
			'rest_prepare_page'        => array( 'rest_prepare_page', 'french_typo_rest_api_post' ),
			'rest_prepare_attachment'  => array( 'rest_prepare_attachment', 'french_typo_rest_api_post' ),
			'french_typo_process_text' => array( 'french_typo_process_text', 'french_typo_replace' ),
		);
	}

	public function test_user_metadata_filter_takes_five_arguments(): void {
		$this->assertHook( $this->registered(), 'get_user_metadata', 'french_typo_user_meta', 10, 5 );
	}

	public function test_optional_integrations_are_absent_by_default(): void {
		$by_hook = $this->registered();
		foreach ( array( 'acf/format_value/type=text', 'rwmb_the_value', 'wpseo_breadcrumb_links', 'wpseo_metadesc', 'rank_math/frontend/title', 'seopress_titles_title' ) as $hook ) {
			$this->assertArrayNotHasKey( $hook, $by_hook, "{$hook} must not be registered without its plugin" );
		}
	}

	public function test_admin_hooks_are_attached_only_in_admin(): void {
		$this->assertArrayNotHasKey( 'admin_menu', $this->registered() );

		$GLOBALS['french_typo_test_is_admin'] = true;
		$by_hook                              = $this->registered();
		$this->assertHook( $by_hook, 'admin_menu', 'french_typo_admin_menu' );
		$this->assertHook( $by_hook, 'admin_init', 'french_typo_admin_init' );
		$this->assertHook( $by_hook, 'admin_init', 'french_typo_handle_mlp_notice_dismiss' );
		$this->assertHook( $by_hook, 'admin_notices', 'french_typo_mlp_notice' );
		$this->assertHook( $by_hook, 'admin_enqueue_scripts', 'french_typo_admin_enqueue_scripts' );
		$this->assertHook( $by_hook, 'plugin_action_links_french-typo/french-typo.php', 'french_typo_action_links' );
		$this->assertHook( $by_hook, 'plugin_row_meta', 'french_typo_plugin_row_meta', 10, 2 );
	}

	/**
	 * Defining a function or a constant cannot be undone, so each integration gets its own process.
	 *
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function test_acf_filters_when_acf_is_active(): void {
		eval( 'function get_field() {}' ); // phpcs:ignore Squiz.PHP.Eval.Discouraged
		$by_hook = $this->registered();
		foreach ( array( 'text', 'textarea', 'wysiwyg' ) as $type ) {
			$this->assertHook( $by_hook, "acf/format_value/type={$type}", 'french_typo_replace_custom_field' );
		}
	}

	/**
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function test_meta_box_filter_when_meta_box_is_active(): void {
		eval( 'function rwmb_get_value() {}' ); // phpcs:ignore Squiz.PHP.Eval.Discouraged
		$this->assertHook( $this->registered(), 'rwmb_the_value', 'french_typo_replace_custom_field' );
	}

	/**
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function test_yoast_filters_when_yoast_is_active(): void {
		define( 'WPSEO_VERSION', '1' );
		$by_hook = $this->registered();
		$this->assertHook( $by_hook, 'wpseo_breadcrumb_links', 'french_typo_breadcrumbs' );
		foreach ( array( 'wpseo_metadesc', 'wpseo_title', 'wpseo_opengraph_title', 'wpseo_opengraph_desc', 'wpseo_twitter_title', 'wpseo_twitter_description' ) as $hook ) {
			$this->assertHook( $by_hook, $hook, 'french_typo_replace' );
		}
	}

	/**
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function test_rank_math_filters_when_rank_math_is_active(): void {
		define( 'RANK_MATH_VERSION', '1' );
		$by_hook = $this->registered();
		$this->assertHook( $by_hook, 'rank_math/frontend/breadcrumb/items', 'french_typo_breadcrumbs' );
		foreach ( array( 'frontend/title', 'frontend/description', 'opengraph/title', 'opengraph/description', 'twitter/title', 'twitter/description' ) as $suffix ) {
			$this->assertHook( $by_hook, "rank_math/{$suffix}", 'french_typo_replace' );
		}
	}

	/**
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function test_seopress_filters_when_seopress_is_active(): void {
		define( 'SEOPRESS_VERSION', '1' );
		$by_hook = $this->registered();
		$this->assertHook( $by_hook, 'seopress_breadcrumbs_items', 'french_typo_breadcrumbs' );
		foreach ( array( 'seopress_titles_title', 'seopress_titles_desc', 'seopress_social_og_title', 'seopress_social_og_desc', 'seopress_social_twitter_title', 'seopress_social_twitter_desc' ) as $hook ) {
			$this->assertHook( $by_hook, $hook, 'french_typo_replace' );
		}
	}
}
