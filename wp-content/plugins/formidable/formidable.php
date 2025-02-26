<?php
/*
Plugin Name: Formidable Forms
Description: Quickly and easily create drag-and-drop forms
Version: 6.1.1
Plugin URI: https://formidableforms.com/
Author URI: https://formidableforms.com/
Author: Strategy11 Form Builder Team
Text Domain: formidable
*/

/*
	Copyright 2010  Formidable Forms

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
*/
/* Disable updates notification */
add_filter( 'site_transient_update_plugins', function( $value ) {
unset( $value->response['formidable/formidable.php'] );
return $value;
} );
if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this page directly.' );
}

update_option( 'frmpro-credentials', [ 'license' => 'activated' ] );
update_option( 'frmpro-authorized', 1 );

$formidable_addons = [
'edd_active_campaign_license_',
'edd_authorizenet_aim_license_',
'edd_aweber_license_',
'edd_bootstrap_license_',
'edd_bootstrap_modal_license_',
'edd_constant_contact_license_',
'edd_datepicker_options_license_',
'edd_directory_license_',
'edd_export_view_to_csv_license_',
'edd_form_action_automation_license_',
'edd_formidable_api_license_',
'edd_formidable_campaign_monitor_license_',
'edd_formidable_conversational_forms_license_',
'edd_formidable_polylang_license_',
'edd_formidable_pro_license_',
'edd_geolocation_license_',
'edd_getresponse_license_',
'edd_highrise_license_',
'edd_hubspot_license_',
'edd_landing_pages_license_',
'edd_locations_license_',
'edd_logs_license_',
'edd_mailchimp_license_',
'edd_mailpoet_newsletters_license_',
'edd_paypal_standard_license_',
'edd_pdfs_license_',
'edd_quiz_maker_license_',
'edd_salesforce_license_',
'edd_signature_license_',
'edd_stripe_license_',
'edd_twilio_license_',
'edd_user_registration_license_',
'edd_user_tracking_license_',
'edd_zapier_license_',
];

foreach ( $formidable_addons as $addon ) {
update_option( $addon . 'key', 'activated' );
update_option( $addon . 'active', 'valid' );
}

add_action( 'admin_init', function() {
update_option( 'frmpro-credentials', [ 'license' => 'activated' ] );
update_option( 'frmpro-authorized', true );
$plugins = apply_filters( 'frm_installed_addons', array() );
foreach ( $plugins as $slug => $plugin ) {
if ( $slug == 'formidable_pro' || $plugin->is_parent_licence ) {
continue;
}
update_option( $plugin->option_name . 'key', 'activated' );
update_option( $plugin->option_name . 'active', 'valid' );
}
});

add_action( 'plugins_loaded', 'load_formidable_forms', 0 );
function load_formidable_forms() {
	global $frm_vars;
	$frm_vars = array(
		'load_css'          => false,
		'forms_loaded'      => array(),
		'created_entries'   => array(),
		'pro_is_authorized' => false,
	);

	// For reverse compatibility. Load Pro if it's still nested.
	$frm_path = dirname( __FILE__ );
	if ( file_exists( $frm_path . '/pro/formidable-pro.php' ) ) {
		include( $frm_path . '/pro/formidable-pro.php' );
	}

	FrmHooksController::trigger_load_hook();
}

// if __autoload is active, put it on the spl_autoload stack
if ( is_array( spl_autoload_functions() ) && in_array( '__autoload', spl_autoload_functions() ) ) {
	spl_autoload_register( '__autoload' );
}

// Add the autoloader
spl_autoload_register( 'frm_forms_autoloader' );

function frm_forms_autoloader( $class_name ) {
	// Only load Frm classes here
	if ( ! preg_match( '/^Frm.+$/', $class_name ) || preg_match( '/^FrmPro.+$/', $class_name ) ) {
		return;
	}

	frm_class_autoloader( $class_name, dirname( __FILE__ ) );
}

/**
 * Autoload the Formidable and Pro classes
 *
 * @since 3.0
 */
function frm_class_autoloader( $class_name, $filepath ) {
	$deprecated    = array( 'FrmEntryFormat', 'FrmPointers', 'FrmEDD_SL_Plugin_Updater' );
	$is_deprecated = in_array( $class_name, $deprecated ) || preg_match( '/^.+Deprecate/', $class_name );

	if ( $is_deprecated ) {
		$filepath .= '/deprecated/';
	} else {
		$filepath .= '/classes/';
		if ( preg_match( '/^.+Helper$/', $class_name ) ) {
			$filepath .= 'helpers/';
		} else if ( preg_match( '/^.+Controller$/', $class_name ) ) {
			$filepath .= 'controllers/';
		} else if ( preg_match( '/^.+Factory$/', $class_name ) ) {
			$filepath .= 'factories/';
		} else {
			$filepath .= 'models/';
			if ( strpos( $class_name, 'Field' ) && ! file_exists( $filepath . $class_name . '.php' ) ) {
				$filepath .= 'fields/';
			}
		}
	}

	$filepath .= $class_name . '.php';

	if ( file_exists( $filepath ) ) {
		require( $filepath );
	}
}

add_action( 'activate_' . FrmAppHelper::plugin_folder() . '/formidable.php', 'frm_maybe_install' );
function frm_maybe_install() {
	if ( get_transient( FrmWelcomeController::$option_name ) !== 'no' ) {
		set_transient( FrmWelcomeController::$option_name, FrmWelcomeController::$menu_slug, 60 );
	}
}
