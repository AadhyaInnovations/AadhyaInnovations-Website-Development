<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this page directly.' );
}

class FrmProHtmlHelper {

	/**
	 * @since 5.0.17
	 *
	 * @param string $id
	 * @param string $name
	 * @param array  $args
	 * @return string|void
	 */
	public static function toggle( $id, $name, $args ) {
		if ( FrmAppHelper::is_admin_page() ) {
			// Load keyboard shortcuts. If this is a setting, use admin_toggle() instead.
			wp_enqueue_script( 'formidable_pro_settings', FrmProAppHelper::plugin_url() . '/js/admin/settings.js', array(), FrmProDb::$plug_version, true );
		}
		return self::clip(
			function() use ( $id, $name, $args ) {
				require FrmProAppHelper::plugin_path() . '/classes/views/shared/toggle.php';
			},
			isset( $args['echo'] ) ? $args['echo'] : false
		);
	}

	/**
	 * @since 6.0
	 *
	 * @param string $id
	 * @param string $name
	 * @param array  $args
	 * @return string|void
	 */
	public static function admin_toggle( $id, $name, $args ) {
		if ( is_callable( 'FrmHtmlHelper::toggle' ) ) {
			return FrmHtmlHelper::toggle( $id, $name, $args );
		}
		return self::toggle( $id, $name, $args );
	}

	/**
	 * Call an echo function and either echo it or return the result as a string.
	 *
	 * @since 5.0.17
	 *
	 * @param Closure $echo_function
	 * @param bool    $echo
	 * @return string|void
	 */
	private static function clip( $echo_function, $echo = false ) {
		if ( ! $echo ) {
			ob_start();
		}

		$echo_function();

		if ( ! $echo ) {
			$return = ob_get_contents();
			ob_end_clean();
			return $return;
		}
	}
}
