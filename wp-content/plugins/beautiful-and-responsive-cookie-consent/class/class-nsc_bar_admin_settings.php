<?php
if (!defined('ABSPATH')) {
    exit;
}

class nsc_bar_admin_settings
{
    private $settings;
    private $default_banner_config_file;
    private $plugin_configs;

    public function __construct()
    {
        //retrieves only the hard coded settings.
        $this->plugin_configs = new nsc_bar_plugin_configs;
        $this->settings = $this->plugin_configs->nsc_bar_return_plugin_settings_without_db_settings();
    }

    public function nsc_bar_execute_backend_wp_actions()
    {
        add_action('admin_menu', array($this, 'nsc_bar_add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'nsc_bar_enqueue_script_on_admin_page'));
        add_action('admin_enqueue_scripts', array($this, 'nsc_bar_enqueue_styles_on_admin_page'));
        add_action('admin_enqueue_scripts', array($this, 'nsc_bar_enqueue_admin_preview_banner'), 90);
    }

    public function nsc_bar_add_admin_menu()
    {
        add_options_page($this->settings->settings_page_configs->page_title, $this->settings->settings_page_configs->menu_title, esc_attr($this->settings->settings_page_configs->capability), $this->settings->plugin_slug, array($this, "nsc_bar_createAdminPage"));
    }

    public function nsc_bar_enqueue_script_on_admin_page($hook)
    {
        if ($hook == 'settings_page_nsc_bar-cookie-consent') {
            wp_enqueue_script('nsc_bar_cookietypes_js', NSC_BAR_PLUGIN_URL . '/admin/js/cookietypes.v2.js', array(), NSC_BAR_VERSION);
        }
    }

    public function nsc_bar_enqueue_styles_on_admin_page($hook)
    {
        if ($hook == 'settings_page_nsc_bar-cookie-consent') {
            wp_enqueue_style('nsc_bar_admin_styles', NSC_BAR_PLUGIN_URL . '/admin/css/nsc_bar_admin.css', array(), NSC_BAR_VERSION);
        }
    }

    public function nsc_bar_enqueue_admin_preview_banner($hook)
    {
        if ($this->show_preview($hook)) {
            $nsc_bar_frontend_banner = new nsc_bar_frontend();
            $nsc_bar_banner_config = new nsc_bar_banner_configs();
            $nsc_bar_frontend_banner->nsc_bar_set_json_configs($nsc_bar_banner_config);
            $nsc_bar_frontend_banner->nsc_bar_enqueue_scripts();
        }
    }

    public function nsc_bar_createAdminPage()
    {
        $objSettings = $this->plugin_configs->nsc_bar_return_plugin_settings();
        $objSettings->current_language = "xx";
        $objSettings->addon_lang_description = "See <a href='https://beautiful-cookie-banner.com' target='_blank'>here</a> how to add multilanguage support to your banner.";
        $objSettings->additional_tab_link_parameter = "";
        if (class_exists("nsc_bara_languages") === true && class_exists("nsc_bara_admin_settings_addon") === true) {
            $language_settings = new nsc_bara_languages();
            $objSettings->current_language = $language_settings->nsc_bara_get_current_language();
            $bara_admin_settings = new nsc_bara_admin_settings_addon();
            $objSettings->additional_tab_link_parameter = $bara_admin_settings->nsc_bara_get_additonal_tab_link();
            $objSettings->addon_lang_description = $bara_admin_settings->nsc_bara_get_addon_lang_description();
        }
        $form_fields = new nsc_bar_html_formfields;
        require NSC_BAR_PLUGIN_DIR . "/admin/tpl/admin.php";
        // for testing
        return $objSettings;
    }

    public function nsc_bar_add_settings_link($links)
    {
        $settings_link = '<a href="options-general.php?page=nsc_bar-cookie-consent">' . __('Settings') . '</a>';
        array_push($links, $settings_link);
        return $links;
    }

    private function show_preview($hook)
    {
        if ($hook == 'settings_page_nsc_bar-cookie-consent' && $this->plugin_configs->nsc_bar_get_option('activate_test_banner') == true) {
            return true;
        }
        return false;
    }
}
