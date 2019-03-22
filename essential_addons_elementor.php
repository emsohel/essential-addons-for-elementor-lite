<?php
/**
 * Plugin Name: Essential Addons for Elementor
 * Description: The ultimate elements library for Elementor page builder plugin for WordPress.
 * Plugin URI: https://essential-addons.com/elementor/
 * Author: WPDeveloper
 * Version: 2.9.8
 * Author URI: https://wpdeveloper.net/
 *
 * Text Domain: essential-addons-elementor
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

/**
 * Including composer autoload.
 *
 * @since x.x.x
 */
require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';

class EssentialAddonsElementor
{
    use EssentialAddonsElementor\Traits\Core;
    use EssentialAddonsElementor\Traits\Ajax;
    use EssentialAddonsElementor\Traits\Generator;
    use EssentialAddonsElementor\Traits\Enqueue;
    use EssentialAddonsElementor\Traits\Admin;
    use EssentialAddonsElementor\Traits\ElementorHelper;
    use EssentialAddonsElementor\Traits\ElementsHelper;
    use EssentialAddonsElementor\Traits\Elements;

    public $plugin_basename;
    public $plugin_path;
    public $plugin_url;
    public $plugin_version;
    public $wp_upload;
    public $asset_path;
    public $asset_url;
    public $registered_elements;

    public function __construct()
    {
        define('ESSENTIAL_ADDONS_EL_URL', plugins_url('/', __FILE__));
        define('ESSENTIAL_ADDONS_EL_PATH', plugin_dir_path(__FILE__));
        define('ESSENTIAL_ADDONS_VERSION', '2.9.8');
        define('ESSENTIAL_ADDONS_BASENAME', plugin_basename(__FILE__));

        $this->plugin_basename = plugin_basename(__FILE__);

        $this->plugin_path = plugin_dir_path(__FILE__);

        $this->plugin_url = plugins_url('/', __FILE__);

        $this->plugin_version = '2.9.8';

        $this->wp_upload = wp_upload_dir();

        $this->asset_path = $this->wp_upload['basedir'] . DIRECTORY_SEPARATOR . 'essential-addons-elementor';

        $this->asset_url = $this->wp_upload['baseurl'] . DIRECTORY_SEPARATOR . 'essential-addons-elementor';

        $this->registered_elements = [
            'contact-form-7',
            'count-down',
            'creative-btn',
            'fancy-text',
            'post-grid',
            'post-timeline',
            'product-grid',
            'team-members',
            'testimonials',
            'weforms',
            'call-to-action',
            'flip-box',
            'info-box',
            'dual-header',
            'price-table',
            'ninja-form',
            'gravity-form',
            'caldera-form',
            'wpforms',
            'twitter-feed',
            'data-table',
            'filter-gallery',
            'image-accordion',
            'content-ticker',
            'tooltip',
            'adv-accordion',
            'adv-tabs',
            'progress-bar',
            'feature-list',
        ];

        // Start plugin tracking
        $this->start_plugin_tracking();

        // Query
        add_action('wp_ajax_nopriv_load_more', array($this, 'eael_load_more_ajax'));
        add_action('wp_ajax_load_more', array($this, 'eael_load_more_ajax'));

        // Generator
        add_action('eael_generate_editor_scripts', array($this, 'generate_editor_scripts'));
        add_action('elementor/editor/after_save', array($this, 'generate_post_scripts'));

        // Enqueue
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));

        // Elementor Helper
        add_action('elementor/controls/controls_registered', array($this, 'controls_registered'));
        add_action('elementor/editor/before_enqueue_scripts', array($this, 'before_enqueue_scripts')); // todo

        // Elements
        add_action('elementor/elements/categories_registered', array($this, 'add_elementor_widget_categories'));
        add_action('elementor/widgets/widgets_registered', array($this, 'add_eael_elements'));

        if (class_exists('Caldera_Forms')) {
            add_filter('caldera_forms_force_enqueue_styles_early', '__return_true');
        }

        // Admin
        if (is_admin()) {
            add_action('admin_init', array($this, 'admin_notice'));
            add_action('admin_menu', array($this, 'admin_menu'), 600);
            add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
            add_action('wp_ajax_save_settings_with_ajax', array($this, 'save_settings'));

            // Core Helper - Leave them for now
            add_filter("plugin_action_links_$this->plugin_basename", array($this, 'eael_add_settings_link'));
            add_action('admin_init', array($this, 'eael_redirect'));
            add_action('admin_footer-plugins.php', array($this, 'plugins_footer_for_pro'));
            add_filter('plugin_action_links_essential-addons-elementor/essential_adons_elementor.php', array($this, 'eae_pro_filter_action_links'));

            if (!did_action('elementor/loaded')) {
                add_action('admin_notices', array($this, 'eael_is_failed_to_load'));
            }
        }
    }

}
add_action('plugins_loaded', function () {
    new EssentialAddonsElementor;
});

/**
 * Activation redirects
 *
 * @since v1.0.0
 */
function eael_activate()
{
    update_option('eael_do_activation_redirect', true);
}
register_activation_hook(__FILE__, 'eael_activate');
