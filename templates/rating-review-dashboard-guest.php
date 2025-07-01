<?php
/**
 * Provides cbxmcratingreview frontend dashboard login
 *
 * This file is used to markup public facing frontend dashboard
 *
 * @link       https://codeboxr.com
 * @since      1.0.0
 *
 * @package    cbxmcratingreview
 * @subpackage cbxmcratingreview/templates
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<?php
do_action( 'cbxmcratingreview_user_dashboard_before' );
?>
    <div class="cbx-chota cbxmcratingreview-frontend-manager-wrapper" id="cbxmcratingreview-review-public">
        <div class="inside cbxmcratingreview-frontend-manager-inside" id="cbxmcratingreview-frontend-dashboard">
            <div class="cbx-chota">
                <div class="container">
					<?php
					if ( ! is_user_logged_in() ):
						//$show_login = $settings->get_field( 'show_login_form', 'cbxmcratingreview_common_config', 'yes' );
						$guest_login_form = esc_attr( $settings->get_field( 'guest_login_form', 'cbxmcratingreview_common_config', 'wordpress' ) );

						$login_html = '';

						if ( $guest_login_form != 'none' ) {
							$login_html .= cbxmcratingreview_get_template_html( 'global/login_form.php', [
								'settings' => $settings,
							] );
						} else {
							$login_html .= cbxmcratingreview_get_template_html( 'global/login_url.php', [
								'settings' => $settings,
							] );
						}

						echo $login_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					endif;
					?>
                </div>
            </div>
        </div>
    </div>
<?php
do_action( 'cbxmcratingreview_user_dashboard_after' );