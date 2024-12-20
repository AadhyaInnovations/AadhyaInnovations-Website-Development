<?php
if (!defined('ABSPATH')) {
    exit;
}

$allowed_html = array(
    "strong" => array(),
    "i" => array(),
    "a" => array(
        "href" => array(),
        "id" => array(),
        "title" => array(),
        "target" => array(),
    ),
    "div" => array(
        "class" => array(),
    ),
    "p" => array(),
    "br" => array(),
    "ul" => array(),
    "ol" => array(),
    "li" => array(),
    "h1" => array(),
    "h2" => array(),
    "h3" => array(),
    "h4" => array(),
    "h5" => array(),
    "h6" => array());
?>
<div class="wrap">
<div id="nsc_bar_upper_area">
<h1 id="nsc_bar_admin_title"><?php echo esc_html($objSettings->settings_page_configs->page_title) ?></h1>
<p><?php echo wp_kses($objSettings->settings_page_configs->description, $allowed_html) ?></p>
</div>
<h2 class="nav-tab-wrapper">
<?php
//tabs are created
foreach ($objSettings->setting_page_fields->tabs as $tab) {
    $activeTab = "";
    if ($tab->active === true) {
        $activeTab = 'nav-tab-active';
    }
    echo '<a href="?page=' . esc_attr($objSettings->plugin_slug) . '&tab=' . esc_attr($tab->tab_slug) . '&' . esc_attr($objSettings->additional_tab_link_parameter) . '" class="nav-tab ' . esc_attr($activeTab) . '" >' . esc_html($tab->tabname) . '</a>';
}
$active_tab_index = $objSettings->setting_page_fields->active_tab_index;
?>
</h2>
<span id="nsc_settings_content">
<table class="form-table nsc_bar_language">
  <tbody>
    <tr id="tr_content_language_setter">
      <th scope="row">Language</th>
      <td>
        <fieldset>
          <label><?php echo $form_fields->nsc_bar_get_language_dropdown() ?></label>
          <p class="description"><?php echo wp_kses($objSettings->addon_lang_description, $allowed_html) ?></p>
        </fieldset>
      </td>
    </tr>
  </tbody>
</table>

<?php echo wp_kses($objSettings->setting_page_fields->tabs[$active_tab_index]->tab_description, $allowed_html) ?>
<form action="" method="post">

<?php wp_nonce_field("save_cookie_settings_" . $objSettings->plugin_slug . "--" . $objSettings->setting_page_fields->tabs[$active_tab_index]->tab_slug, 'nsc_bar_nonce');?>
<input type="hidden" name="action" value="nsc_bar_cookie_settings_save" />
<input type="hidden" name="option_page" value="<?php echo $objSettings->plugin_slug . $objSettings->setting_page_fields->tabs[$active_tab_index]->tab_slug ?>" />
<?php submit_button();?>

<table class="form-table">
<?php foreach ($objSettings->setting_page_fields->tabs[$active_tab_index]->tabfields as $field_configs) {?>
 <tr id="tr_<?php echo esc_attr($field_configs->field_slug) ?>">
  <th scope="row">
    <?php echo esc_html($field_configs->name) ?>
  </th>
  <td>
    <fieldset>
      <?php echo $form_fields->nsc_bar_return_form_field($field_configs, $objSettings->plugin_prefix); ?>
    <?php
if (!empty($field_configs->custom_component)) {
    $rest_url = get_rest_url();
    $nonce = wp_create_nonce('wp_rest');
    $string = str_replace("{{REST_URL_ENCODED}}", urlencode($rest_url), $field_configs->custom_component);
    echo str_replace("{{WP_NONCE}}", $nonce, $string);
}
    ?>
     <p class="description"><?php echo wp_kses($field_configs->helpertext, $allowed_html) ?></p>
    </fieldset>
  </td>
 </tr>
<?php }?>
</table>
</form>
</span>
<?php require 'sidebar.php';?>
