<?php
/**
 * Plugin Name: Advanced Testimonials for Elementor
 * Description: Professional testimonial carousel widget for Elementor with advanced customization options
 * Version: 1.0.0
 * Author: Joy Roy
 * Author URI: https://strativ.se
 * Text Domain: advanced-testimonials-elementor
 * Elementor tested up to: 3.24.0
 * Elementor Pro tested up to: 3.24.0
 */


if (!defined('ABSPATH')) {
    exit;
}

define('ATE_VERSION', '1.0.0');
define('ATE_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('ATE_PLUGIN_URL', plugin_dir_url(__FILE__));
define('ATE_PLUGIN_FILE', __FILE__);

/**
 * Main Plugin Class
 */
final class Advanced_Testimonials_Elementor {

    private static $_instance = null;

    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        add_action('plugins_loaded', [$this, 'init']);
    }

    public function init() {
        // Check if Elementor is installed and activated
        if (!did_action('elementor/loaded')) {
            add_action('admin_notices', [$this, 'admin_notice_missing_elementor']);
            return;
        }

        // Check for required Elementor version
        if (!version_compare(ELEMENTOR_VERSION, '3.0.0', '>=')) {
            add_action('admin_notices', [$this, 'admin_notice_minimum_elementor_version']);
            return;
        }

        // Register widget
        add_action('elementor/widgets/register', [$this, 'register_widgets']);

        // Register widget styles
        add_action('elementor/frontend/after_enqueue_styles', [$this, 'enqueue_styles']);

        // Register widget scripts
        add_action('elementor/frontend/after_register_scripts', [$this, 'enqueue_scripts']);

        // Include GitHub updater
        $this->include_github_updater();
    }

    public function admin_notice_missing_elementor() {
        $message = sprintf(
            esc_html__('"%1$s" requires "%2$s" to be installed and activated.', 'advanced-testimonials-elementor'),
            '<strong>' . esc_html__('Advanced Testimonials for Elementor', 'advanced-testimonials-elementor') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'advanced-testimonials-elementor') . '</strong>'
        );
        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    public function admin_notice_minimum_elementor_version() {
        $message = sprintf(
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'advanced-testimonials-elementor'),
            '<strong>' . esc_html__('Advanced Testimonials for Elementor', 'advanced-testimonials-elementor') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'advanced-testimonials-elementor') . '</strong>',
            '3.0.0'
        );
        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    public function register_widgets($widgets_manager) {
        require_once ATE_PLUGIN_DIR . 'widgets/testimonial-widget.php';
        $widgets_manager->register(new \ATE_Testimonial_Widget());
    }

    public function enqueue_styles() {
        wp_enqueue_style(
            'ate-testimonials',
            ATE_PLUGIN_URL . 'assets/css/testimonials.css',
            [],
            ATE_VERSION
        );

        // Swiper CSS
        wp_enqueue_style(
            'swiper',
            'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
            [],
            '11.0.0'
        );
        
        // Font Awesome for navigation icons
        wp_enqueue_style(
            'font-awesome-6',
            'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
            [],
            '6.4.0'
        );
    }

    public function enqueue_scripts() {
        // Error handler - Load FIRST with high priority
        wp_enqueue_script(
            'ate-error-handler',
            ATE_PLUGIN_URL . 'assets/js/error-handler.js',
            [],
            ATE_VERSION,
            false // Load in header
        );
        
        // Swiper JS
        wp_register_script(
            'swiper',
            'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
            ['jquery'],
            '11.0.0',
            true
        );

        wp_register_script(
            'ate-testimonials',
            ATE_PLUGIN_URL . 'assets/js/testimonials.js',
            ['jquery', 'swiper', 'ate-error-handler'],
            ATE_VERSION,
            true
        );

        // Always enqueue on frontend
        wp_enqueue_script('swiper');
        wp_enqueue_script('ate-testimonials');
        
        // Add inline script to help with initialization
        $inline_script = "
        var ateTestimonialsConfig = {
            swiperLoaded: typeof Swiper !== 'undefined',
            elementorLoaded: typeof elementorFrontend !== 'undefined',
            isLoggedIn: " . (is_user_logged_in() ? 'true' : 'false') . "
        };
        ";
        wp_add_inline_script('ate-testimonials', $inline_script, 'before');
    }

    public function include_github_updater() {
        require_once ATE_PLUGIN_DIR . 'includes/github-updater.php';
        
        if (is_admin()) {
            new ATE_GitHub_Updater(
                ATE_PLUGIN_FILE,
                'joyroynripen',  // Change this to your GitHub username
                'advanced-testimonials-elementor'       // Change this to your repository name
            );
        }
    }
}

Advanced_Testimonials_Elementor::instance();