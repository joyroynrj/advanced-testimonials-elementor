<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * GitHub Updater Class
 * Handles automatic updates from GitHub repository
 */
class ATE_GitHub_Updater {

    private $plugin_file;
    private $github_username;
    private $github_repo;
    private $plugin_slug;
    private $plugin_data;
    private $github_response;

    public function __construct($plugin_file, $github_username, $github_repo) {
        $this->plugin_file = $plugin_file;
        $this->github_username = $github_username;
        $this->github_repo = $github_repo;
        $this->plugin_slug = plugin_basename($plugin_file);

        add_filter('pre_set_site_transient_update_plugins', [$this, 'check_for_update']);
        add_filter('plugins_api', [$this, 'plugin_info'], 10, 3);
        add_filter('upgrader_post_install', [$this, 'after_install'], 10, 3);
    }

    /**
     * Get plugin data
     */
    private function get_plugin_data() {
        if (!function_exists('get_plugin_data')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        
        $this->plugin_data = get_plugin_data($this->plugin_file);
        return $this->plugin_data;
    }

    /**
     * Get GitHub data from API
     */
    private function get_github_data() {
        if (!empty($this->github_response)) {
            return $this->github_response;
        }

        $request_uri = sprintf(
            'https://api.github.com/repos/%s/%s/releases/latest',
            $this->github_username,
            $this->github_repo
        );

        $response = wp_remote_get($request_uri);

        if (is_wp_error($response)) {
            return false;
        }

        $response_body = wp_remote_retrieve_body($response);
        $this->github_response = json_decode($response_body);

        return $this->github_response;
    }

    /**
     * Check for plugin update
     */
    public function check_for_update($transient) {
        if (empty($transient->checked)) {
            return $transient;
        }

        $github_data = $this->get_github_data();
        $plugin_data = $this->get_plugin_data();

        if (!$github_data) {
            return $transient;
        }

        // Get version from tag name (remove 'v' prefix if exists)
        $github_version = ltrim($github_data->tag_name, 'v');
        $current_version = $plugin_data['Version'];

        // Compare versions
        if (version_compare($github_version, $current_version, '>')) {
            $plugin = [
                'slug' => current(explode('/', $this->plugin_slug)),
                'new_version' => $github_version,
                'url' => $plugin_data['PluginURI'],
                'package' => $github_data->zipball_url,
                'tested' => isset($plugin_data['Tested up to']) ? $plugin_data['Tested up to'] : '',
            ];

            $transient->response[$this->plugin_slug] = (object) $plugin;
        }

        return $transient;
    }

    /**
     * Get plugin information
     */
    public function plugin_info($false, $action, $args) {
        if ($action !== 'plugin_information') {
            return $false;
        }

        if ($args->slug !== current(explode('/', $this->plugin_slug))) {
            return $false;
        }

        $github_data = $this->get_github_data();
        $plugin_data = $this->get_plugin_data();

        if (!$github_data) {
            return $false;
        }

        $github_version = ltrim($github_data->tag_name, 'v');

        $plugin_info = [
            'name' => $plugin_data['Name'],
            'slug' => current(explode('/', $this->plugin_slug)),
            'version' => $github_version,
            'author' => $plugin_data['AuthorName'],
            'author_profile' => $plugin_data['AuthorURI'],
            'homepage' => $plugin_data['PluginURI'],
            'requires' => isset($plugin_data['RequiresWP']) ? $plugin_data['RequiresWP'] : '5.0',
            'tested' => isset($plugin_data['Tested up to']) ? $plugin_data['Tested up to'] : '',
            'downloaded' => 0,
            'last_updated' => $github_data->published_at,
            'sections' => [
                'description' => $plugin_data['Description'],
                'changelog' => $this->parse_changelog($github_data->body),
            ],
            'download_link' => $github_data->zipball_url,
        ];

        return (object) $plugin_info;
    }

    /**
     * Parse changelog from release notes
     */
    private function parse_changelog($body) {
        if (empty($body)) {
            return 'No changelog available.';
        }

        return '<h4>Release Notes</h4><pre>' . esc_html($body) . '</pre>';
    }

    /**
     * After installation/update
     */
    public function after_install($response, $hook_extra, $result) {
        global $wp_filesystem;

        $install_directory = plugin_dir_path($this->plugin_file);
        $wp_filesystem->move($result['destination'], $install_directory);
        $result['destination'] = $install_directory;

        if ($this->plugin_slug) {
            activate_plugin($this->plugin_slug);
        }

        return $result;
    }
}