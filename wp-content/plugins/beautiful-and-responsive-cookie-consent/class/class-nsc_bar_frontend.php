<?php
if (!defined('ABSPATH')) {
    exit;
}

class nsc_bar_frontend
{

    private $json_config_string;
    private $plugin_url;
    private $plugin_configs;

    public function __construct()
    {
        $this->plugin_url = NSC_BAR_PLUGIN_URL;
        $this->active_tab = "";
        $this->plugin_configs = new nsc_bar_plugin_configs();
        $this->customized_font = false;
        $this->cookietypes = array();
        $this->cookie_name = "";
        $this->compliance_type = "";
        $this->custom_link = "";
        $this->custom_link_new_window = "";
        $this->improveBannerLoadingSpeed = false;
    }

    public function nsc_bar_set_json_configs($nsc_bar_banner_config)
    {
        $message = $nsc_bar_banner_config->nsc_bar_get_cookie_setting("content_message", false);
        $filteredMessage = apply_filters('nsc_bar_cookie_bar_message', $message);
        $nsc_bar_banner_config->nsc_bar_update_banner_setting("content_message", $filteredMessage, "string");

        $this->json_config_string = $nsc_bar_banner_config->nsc_bar_get_banner_config_string(true);
        $this->customized_font = $nsc_bar_banner_config->nsc_bar_get_cookie_setting("customizedFont", false);
        $this->improveBannerLoadingSpeed = $nsc_bar_banner_config->nsc_bar_get_cookie_setting("improveBannerLoadingSpeed", false);
        $this->cookietypes = $nsc_bar_banner_config->nsc_bar_get_cookie_setting("cookietypes", array());
        $this->cookie_name = $nsc_bar_banner_config->nsc_bar_get_cookie_setting("cookie_name", $this->plugin_configs->nsc_bar_return_settings_field_default_value("cookie_name"));
        $this->compliance_type = $nsc_bar_banner_config->nsc_bar_get_cookie_setting("type", $this->plugin_configs->nsc_bar_return_settings_field_default_value("type"));
        $this->dataLayerName = $nsc_bar_banner_config->nsc_bar_get_cookie_setting("dataLayerName", $this->plugin_configs->nsc_bar_return_settings_field_default_value("dataLayerName"));
        $this->container = $nsc_bar_banner_config->nsc_bar_get_cookie_setting("container", false);
        $this->custom_link = $this->get_create_custom_link($nsc_bar_banner_config, false);
        $this->custom_link_new_window = $this->get_create_custom_link($nsc_bar_banner_config, true);
    }

    public function nsc_bar_execute_frontend_wp_actions()
    {
        add_action('wp_enqueue_scripts', array($this, 'nsc_bar_enqueue_scripts'));
        add_shortcode('cc_revoke_settings_link_nsc_bar', array($this, 'nsc_bar_shortcode_revoke_settings_link'));
        add_shortcode('cc_show_cookie_banner_nsc_bar', array($this, 'nsc_bar_shortcode_show_cookie_banner'));
    }

    public function nsc_bar_enqueue_dataLayer_init_script()
    {
        $banner_active = $this->plugin_configs->nsc_bar_get_option('activate_banner');
        $banner_active = apply_filters('nsc_bar_filter_banner_is_active', $banner_active);
        if ($banner_active != true) {
            return;
        }
        // stick to wp_print_scripts -> might cause caching problems otherwise.
        add_action('wp_print_scripts', array($this, 'nsc_bar_get_dataLayer_banner_init_script'));
    }

    public function nsc_bar_get_dataLayer_banner_init_script($returnValue)
    {

        $nsc_bar_banner_config = new nsc_bar_banner_configs();
        $pushToDl = $nsc_bar_banner_config->nsc_bar_get_cookie_setting("onStatusChange", $this->plugin_configs->nsc_bar_return_settings_field_default_value("onStatusChange"));
        if ($pushToDl !== "1" && $returnValue !== true) {
            return;
        }

        $this->cookie_name = $nsc_bar_banner_config->nsc_bar_get_cookie_setting("cookie_name", $this->plugin_configs->nsc_bar_return_settings_field_default_value("cookie_name"));
        $this->cookietypes = $nsc_bar_banner_config->nsc_bar_get_cookie_setting("cookietypes", array());
        $this->compliance_type = $nsc_bar_banner_config->nsc_bar_get_cookie_setting("type", $this->plugin_configs->nsc_bar_return_settings_field_default_value("type"));

        $cookies = $this->get_consent_cookie_values();

        $dataLayerValues = array();
        foreach ($cookies as $cookie_name => $cookie_values) {
            $cookie_value = $cookie_values["value"];
            if (empty($cookie_value)) {
                $cookie_value = $cookie_values["defaultValue"];
            }

            // goal: remove dismiss completly from application. Problem: is saved in cookie for "just info". For backward compatibility hard to change.
            // first step: in datalayer dismiss will never appear.
            if ($cookie_value === "dismiss") {
                $cookie_value = "allow";
            }
            $key = esc_js($cookie_name);
            $dataLayerValues[$key] = esc_js($cookie_value);
        }

        $dataLayerValues = apply_filters('nsc_bar_filter_data_layer_values', $dataLayerValues);
        if ($returnValue === true) {
            return $dataLayerValues;
        }
        $dataLayerValues["event"] = 'beautiful_cookie_consent_initialized';
        $this->dataLayerName = $nsc_bar_banner_config->nsc_bar_get_cookie_setting("dataLayerName", $this->plugin_configs->nsc_bar_return_settings_field_default_value("dataLayerName"));
        echo "<script>window." . esc_js($this->dataLayerName) . " = window." . esc_js($this->dataLayerName) . " || []; window." . esc_js($this->dataLayerName) . ".push(" . json_encode($dataLayerValues) . ")</script>";

    }

    public function nsc_bar_enqueue_scripts()
    {
        wp_register_style('nsc_bar_nice-cookie-consent', $this->plugin_url . 'public/cookieNSCconsent.min.css', array(), NSC_BAR_VERSION);
        if (!empty($this->customized_font)) {
            wp_add_inline_style('nsc_bar_nice-cookie-consent', '.cc-window { font-family: ' . str_replace("&#039;", "'", esc_html($this->customized_font)) . '}');
        }
        wp_enqueue_style('nsc_bar_nice-cookie-consent');

        wp_register_script('nsc_bar_nice-cookie-consent_js', $this->plugin_url . 'public/cookieNSCconsent.min.js', array(), NSC_BAR_VERSION, true);
        $eventListener = "load";
        if ($this->improveBannerLoadingSpeed === "1") {
            $eventListener = "DOMContentLoaded";
        }
        $bannerInitScript = 'function(){window.cookieconsent.initialise(' . $this->nsc_bar_json_with_js_function() . ')}';
        $bannerInitScript = apply_filters('nsc_bar_filter_inline_script_initialize', $bannerInitScript);

        wp_add_inline_script("nsc_bar_nice-cookie-consent_js", 'window.addEventListener("' . $eventListener . '",' . $bannerInitScript . ');');
        wp_enqueue_script('nsc_bar_nice-cookie-consent_js');

    }

    public function nsc_bar_json_with_js_function()
    {
        $validator = new nsc_bar_input_validation();
        $cleanedCookieTypes = $validator->esc_array_for_js($this->cookietypes);
        $popUpCloseJsFunction = '"onPopupClose": function(){location.reload();}';

        $json_config_string_with_js = $this->json_config_string;
        $json_config_string_with_js = apply_filters('nsc_bar_filter_json_config_string_before_js', $json_config_string_with_js);

        if (!empty($this->container)) {
            $setContainerPosition = '"container": document.querySelector("' . esc_js($this->container) . '")';
            $json_config_string_with_js = str_replace(array('"container": "' . $this->container . '"', '"container":"' . $this->container . '"'), $setContainerPosition, $json_config_string_with_js);
        }

        if (is_admin()) {
            $popUpCloseJsFunction = '"onPopupClose": function(){}';
        }

        $json_config_string_with_js = str_replace(array('"onPopupClose": "1"', '"onPopupClose":"1"'), $popUpCloseJsFunction, $json_config_string_with_js);
        $json_config_string_with_js = str_replace('{{customLink}}', $this->custom_link, $json_config_string_with_js);
        $json_config_string_with_js = str_replace('{{customLink_openNewWindow}}', $this->custom_link_new_window, $json_config_string_with_js);
        $json_config_string_with_js = apply_filters('nsc_bar_filter_json_config_string_with_js', $json_config_string_with_js);
        return $json_config_string_with_js;
    }

    public function nsc_bar_shortcode_show_cookie_banner()
    {
        $linktext = $this->plugin_configs->nsc_bar_get_option("shortcode_link_show_banner_text");
        return "<a id='nsc_bar_link_show_banner' style='cursor: pointer;'>" . esc_html($linktext) . "</a>";
    }

    public function nsc_bar_shortcode_revoke_settings_link()
    {
        $banner_configs_obj = new nsc_bar_banner_configs();

        $cookie_name = $banner_configs_obj->nsc_bar_get_cookie_setting("cookie_name", $this->plugin_configs->nsc_bar_return_settings_field_default_value("cookie_name"));
        $cookie_domain = $banner_configs_obj->nsc_bar_get_cookie_setting("cookie_domain", $this->plugin_configs->nsc_bar_return_settings_field_default_value("cookie_domain"));
        $cookie_expiry_days = $banner_configs_obj->nsc_bar_get_cookie_setting("cookie_expiryDays", $this->plugin_configs->nsc_bar_return_settings_field_default_value("cookie_expiryDays"));
        $compliance_type = $banner_configs_obj->nsc_bar_get_cookie_setting("type", $this->plugin_configs->nsc_bar_return_settings_field_default_value("type"));

        $link_text_opted_in = $this->plugin_configs->nsc_bar_get_option("shortcode_link_text_opted_in");
        $link_text_opted_out = $this->plugin_configs->nsc_bar_get_option("shortcode_link_text_opted_out");

        $wordpressUrl = get_bloginfo('url');
        $hostname = parse_url($wordpressUrl, PHP_URL_HOST);
        if ($hostname == "localhost") {
            $domain[0] = "localhost";
        } else {
            $hostname = $cookie_domain;
        }

        switch ($compliance_type) {
            case "opt-out":
                $current_cookie_value = "allow";
                break;
            case "opt-in":
                $current_cookie_value = "deny";
                break;
            default:
                return null;
        }

        if (isset($_COOKIE[$cookie_name]) && $current_cookie_value != "dismiss") {
            $current_cookie_value = $_COOKIE[$cookie_name];
        }

        switch ($current_cookie_value) {
            case "allow":
                $linktext = $link_text_opted_in;
                $linktext_after_click = $link_text_opted_out;
                $cookie_value_after_click = "deny";
                break;
            case "deny":
                $linktext = $link_text_opted_out;
                $linktext_after_click = $link_text_opted_in;
                $cookie_value_after_click = "allow";
                break;
            default:
                $linktext = "";
                $linktext_after_click = "";
                $cookie_value_after_click = "";
        }

        if (isset($_COOKIE[$cookie_name]) && $current_cookie_value != $_COOKIE[$cookie_name]) {
            $linktext = $atts['link_text_opted_out'];
        }

        $expire = time() + 60 * 60 * 24 * $cookie_expiry_days;
        $js_code = preg_replace("/\r|\n/", "", file_get_contents(NSC_BAR_PLUGIN_DIR . "/public/revoke_shortcode.js"));
        return "<a id='nsc_bar_optout_link' data-link_text_after_click='" . esc_attr($linktext_after_click) . "' data-link_text_before_click='" . esc_attr($linktext) . "' data-cookiename='" . esc_attr($cookie_name) . "' data-current_cookie_value='" . esc_attr($current_cookie_value) . "'data-cookie_value_after_click='" . esc_attr($cookie_value_after_click) . "' data-expires='" . esc_attr($expire) . "' data-domain='" . esc_attr($hostname) . "' style='cursor: pointer;' onclick='" . $js_code . "'>" . esc_html($linktext) . "</a>";
    }

    private function get_consent_cookie_values()
    {
        if (empty($this->cookietypes)) {
            return false;
        }
        $numberOfCookies = count($this->cookietypes);

        $cookieHandler = new nsc_bar_cookie_handler();
        $dataLayerEntries = array();

        $dataLayerEntries["cookieconsent_status"] = array("value" => $cookieHandler->nsc_bar_get_cookies_by_name($this->cookie_name), "defaultValue" => $this->calculate_default_consent_setting());
        if ($this->compliance_type !== "detailed" && $this->compliance_type !== "detailedRev") {
            return $dataLayerEntries;
        }

        for ($i = 0; $i < $numberOfCookies; $i++) {
            $cookie_name = $this->cookie_name . "_" . $this->cookietypes[$i]["cookie_suffix"];
            $dataLayerEntries["cookieconsent_status_" . $this->cookietypes[$i]["cookie_suffix"]] = array("value" => $cookieHandler->nsc_bar_get_cookies_by_name($cookie_name), "defaultValue" => $this->calculate_default_consent_setting($this->cookietypes[$i]));
        }

        return $dataLayerEntries;
    }

    private function calculate_default_consent_setting($cookietype = array())
    {
        if ($this->compliance_type === "opt-in") {
            return "deny";
        }

        if ($this->compliance_type === "opt-out" || $this->compliance_type === "info") {
            return "allow";
        }

        if (empty($cookietype)) {
            return "nochoice";
        }

        if ($cookietype["checked"] === "checked") {
            return "allow";
        }

        return "deny";

    }

    private function get_create_custom_link($nsc_bar_banner_config, $targetBlank)
    {
        $link = $nsc_bar_banner_config->nsc_bar_get_cookie_setting("content_hrefsecond", false);
        $link_text = $nsc_bar_banner_config->nsc_bar_get_cookie_setting("content_linksecond", false);
        $link_html = "";
        $target = "";
        if ($targetBlank === true) {
            $target = " target='_blank'";
        }

        if (!empty($link) && !empty($link_text)) {
            $link_html = "<a class='cc-link' id='nsc-bar-customLink'" . $target . " href='" . esc_url_raw($link) . "'>" . esc_html($link_text) . "</a>";
        }
        return $link_html;
    }

}
