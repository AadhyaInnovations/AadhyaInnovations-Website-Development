<?php


/**
 * Define the metabox and field configurations.
 */
function cmb2_ccpw_metaboxes()
{

// Start with an underscore to hide fields from custom fields list
    $prefix = 'ccpw_';
    $ccwp_logo_button_id="delete_cache";
    $ajax_url = admin_url( 'admin-ajax.php' );
    $currencies_arr = array(
        'USD' => 'USD',
        'GBP' => 'GBP',
        'EUR' => 'EUR',
        'INR' => 'INR',
        'JPY' => 'JPY',
        'CNY' => 'CNY',
        'ILS' => 'ILS',
        'KRW' => 'KRW',
        'RUB' => 'RUB',
        'DKK' => 'DKK',
        'PLN' => 'PLN',
        'AUD' => 'AUD',
        'BRL' => 'BRL',
        'MXN' => 'MXN',
        'SEK' => 'SEK',
        'CAD' => 'CAD',
        'HKD' => 'HKD',
        'MYR' => 'MYR',
        'SGD' => 'SGD',
        'CHF' => 'CHF',
        'HUF' => 'HUF',
        'NOK' => 'NOK',
        'THB' => 'THB',
        'CLP' => 'CLP',
        'IDR' => 'IDR',
        'NZD' => 'NZD',
        'TRY' => 'TRY',
        'PHP' => 'PHP',
        'TWD' => 'TWD',
        'CZK' => 'CZK',
        'PKR' => 'PKR',
        'ZAR' => 'ZAR',
    );
    /**
     * Initiate the metabox
     */
    
    $cmb2 = new_cmb2_box( array(
        'id'            => 'live_preview',
        'title'         => __( 'Crypto Widget Live Preview', 'cmb2' ),
        'object_types'  => array( 'ccpw'), // Post type
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true, // Show field names on the left
        // 'cmb_styles' => false, // false to disable the CMB stylesheet
        // 'closed'     => true, // Keep the metabox closed by default
    ) );
    $cmb = new_cmb2_box(array(
        'id' => 'generate_shortcode',
        'title' => __('Crypto Widget Settings', 'cmb2'),
        'object_types' => array('ccpw'), // Post type
        'context' => 'normal',
        'priority' => 'high',
        'show_names' => true, // Show field names on the left
        // 'cmb_styles' => false, // false to disable the CMB stylesheet
        // 'closed'     => true, // Keep the metabox closed by default
    ));

  
    


    $cmb->add_field(array(
        'name' => 'Widget Type<span style="color:red;">*</span>',
        'id' => 'type',
        'type' => 'select',
        'default' => 'table-widget',
        'options' => array(
            'table-widget' => __('Advanced Table', 'cmb2'),
            'list-widget' => __('Simple List', 'cmb2'),
            'ticker' => __('Ticker / Marquee', 'cmb2'),
            'multi-currency-tab' => __('Multi Currency Tabs', 'cmb2'),
            'price-label' => __('Price Label', 'cmb2'),

        ),
    ));    

$cmb->add_field(
    array(
        'name' => 'Show Coins <span style="color:red;">*</span>',
        'id' => 'show-coins',
        'type' => 'select',
        'options' => array(
            'custom' => 'Custom List',
                10 => 'Top 10',
                50 => 'Top 50',
                100 => 'Top 100',
                200 =>'Top 200',         
                250 => 'All (250)',
        ),
    ));
    $cmb->add_field(array(
        'name' => 'Select Coins<span style="color:red;">*</span>',
        'id' => 'display_currencies',
        'desc' => 'Select CryptoCurrencies (Press CTRL key to select multiple)',
        'type' => 'pw_multiselect',
        'options' => ccpw_get_all_coin_ids(),
        'attributes' => array(
            'required' => true,
            'data-conditional-id' => 'show-coins',
            'data-conditional-value' => json_encode(array('custom')),
        ),
    ));

    //select currency
    $cmb->add_field(array(
        'name' => 'Select Fiat Currency',
        'desc' => '',
        'id' => 'currency',
        'type' => 'select',
        'show_option_none' => false,
        'default' => 'custom',
        'options' => $currencies_arr,
        'desc' => '<span style="color:red">Remember to add <a href="https://openexchangerates.org/signup/free" target="blank">Openexchangerates.org free API</a> key for crypto to fiat price conversions</span>',
        'default' => 'USD',
        'attributes' => array(
            'data-conditional-id' => 'type',
            'data-conditional-value' => json_encode(array('price-label', 'list-widget', 'ticker', 'table-widget')),
        ),
    ));

    

    $cmb->add_field(
        array(
            'name' => 'Records Per Page',
            'id' => 'pagination_for_table',
            'type' => 'select',
            'options' => array(
                '10' => '10',
                '25' => '25',
                '50' => '50',
                '100' => '100',
            )
            ,
            'attributes' => array(
                'data-conditional-id' => 'type',
                'data-conditional-value' => json_encode(array('table-widget')),
            ),
        ));

    $cmb->add_field(array(
        'name' => 'Enable Formatting',
        'desc' => 'Select if you want to display marketcap, volume and supply in <strong>(Million/Billion)</strong>',
        'id' => 'enable_formatting',
        'type' => 'checkbox',
        'default' => ccpw_set_checkbox_default_for_new_post(true),
        'attributes' => array(
            'data-conditional-id' => 'type',
            'data-conditional-value' => json_encode(array('table-widget')),
        ),
    ));

    $cmb->add_field(array(
        'name' => 'Display 24 Hours changes? (Optional)',
        'desc' => 'Select if you want to display Currency changes in price',
        'default' => ccpw_set_checkbox_default_for_new_post(true),
        'id' => 'display_changes',
        'type' => 'checkbox',
        'attributes' => array(
            // 'required' => true,
            'data-conditional-id' => 'type',
            'data-conditional-value' => json_encode(array('price-label', 'list-widget', 'multi-currency-tab', 'ticker')),
        ),
    ));

    $cmb->add_field(array(
        'name' => 'Where Do You Want to Display Ticker? (Optional)',
        'desc' => '<br>Select the option where you want to display ticker.<span class="warning">Important: Do not add shortcode in a page if Header/Footer position is selected.</span>',
        'id' => 'ticker_position',
        'type' => 'radio_inline',
        'options' => array(
            'header' => __('Header', 'cmb2'),
            'footer' => __('Footer', 'cmb2'),
            'shortcode' => __('Anywhere', 'cmb2'),
        ),
        'default' => 'shortcode',

        'attributes' => array(
            // 'required' => true,
            'data-conditional-id' => 'type',
            'data-conditional-value' => 'ticker',
        ),

    ));

    $cmb->add_field(array(
        'name' => 'Ticker Position(Top)',
        'desc' => 'Specify Top Margin (in px) - Only For Header Ticker',
        'id' => 'header_ticker_position',
        'type' => 'text',
        'default' => '33',
        'attributes' => array(
            // 'required' => true,
            'data-conditional-id' => 'type',
            'data-conditional-value' => 'ticker',
        ),
    ));

    $cmb->add_field(array(
        'name' => 'Speed of Ticker',
        'desc' => 'Low value = high speed. (Best between 10 - 60) e.g 10*1000 = 10000 miliseconds',
        'id' => 'ticker_speed',
        'type' => 'text',
        'default' => '35',
        'attributes' => array(
            // 'required' => true,
            'data-conditional-id' => 'type',
            'data-conditional-value' => 'ticker',
        ),
    ));

    $cmb->add_field(array(
        'name' => 'Background Color',
        'desc' => 'Select background color',
        'id' => 'back_color',
        'type' => 'colorpicker',
        'default' => '#eee',
        'attributes' => array(
            'data-conditional-id' => 'type',
            'data-conditional-value' => json_encode(array('multi-currency-tab', 'list-widget', 'ticker')),
        ),
    ));

    $cmb->add_field(array(
        'name' => 'Font Color',
        'desc' => 'Select font color',
        'id' => 'font_color',
        'type' => 'colorpicker',
        'default' => '#000',
        'attributes' => array(
            'data-conditional-id' => 'type',
            'data-conditional-value' => json_encode(array('multi-currency-tab', 'list-widget', 'ticker')),
        ),
    ));

    $cmb->add_field(array(
        'name' => 'Custom CSS',
        'desc' => 'Enter custom CSS',
        'id' => 'custom_css',
        'type' => 'textarea',

    ));

    $cmb->add_field(array(
        'name' => 'Show API Credits',
        'desc' => 'Link back or a mention of ‘<strong>Powered by CoinGecko API</strong>’ would be appreciated!',
        'id' => 'ccpw_coinexchangeprice_credits',
        'default' => ccpw_set_checkbox_default_for_new_post(false),
        'type' => 'checkbox',
        'attributes' => array(
            // 'required' => true,
            'data-conditional-id' => 'type',
            'data-conditional-value' => json_encode(array('ticker', 'price-label', 'list-widget', 'multi-currency-tab', 'table-widget')),
        ),

    ));
    
    $cmb2->add_field( array(
        'name' => '',
        'desc' =>display_live_preview(),
        'type' => 'title',
        'id'   => 'live_preview'
    ) );







//Option page settings


    $cmb3 = new_cmb2_box(array(
    'id' => 'api_settings_page',
    'title' => esc_html__('Select API To Show Your Crypto Data In Widget', 'ccpw'),
    'object_types' => array('options-page'),
    /*
     * The following parameters are specific to the options-page box
     * Several of these parameters are passed along to add_menu_page()/add_submenu_page().
     */

    'option_key' => 'ccpw_options', // The option key and admin menu page slug.
    'icon_url' => 'false', // Menu icon. Only applicable if 'parent_slug' is left empty.
    'menu_title' => false, // Falls back to 'title' (above).
    'parent_slug' => 'cool-crypto-plugins', // Make options page a submenu item of the themes menu.
    //'capability'      => 'manage_options', // Cap required to view options-page.
    //'position'        => 2, // Menu position. Only applicable if 'parent_slug' is left empty.
    // 'admin_menu_hook' => 'network_admin_menu', // 'network_admin_menu' to add network-level options page.
    // 'display_cb'      => false, // Override the options-page form output (CMB2_Hookup::options_page_output()).
    // 'save_button'     => esc_html__( 'Save Settings', 'cilwp' ), // The text for the options-page save button. Defaults to 'Save'.
));

$cmb3->add_field(array(
    'name' => 'Select Api',
    'id' => 'select_api',
    'type' => 'select',
    'default' => 'coin_gecko',
    'options' => array(
        'coin_gecko' => __('Coin Gecko', 'cmb2'),
        'coin_paprika' => __('Coin Paprika', 'cmb2'),
    ),
));
$cmb3->add_field(array(
    'name' => 'Select Cache Time',
    'id' => 'select_cache_time',
    'type' => 'select',
    'default' => '5 Minute',
    'options' => array(
        '5 Minute' => __('5 Minute', 'cmb2'),
        '10 Minute' => __('10 Minute', 'cmb2'),
        '15 Minute' => __('15 Minute', 'cmb2'),
    ),
));

$cmb3->add_field(array(
    'id' => "Delete Cache",
    'type' => 'wysiwyg',
    'name' => "Delete Cache",
    'desc' => '
        <a class="button ccpw-delete-transient" data-ajax-url="' . $ajax_url . '"id="' . $ccwp_logo_button_id . '">Delete Cache</a>',
));

}


