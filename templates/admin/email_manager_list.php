<?php
/**
 * Provide a dashboard view for the plugin
 * This file is used to markup the public-facing aspects of the plugin.
 * @link       https://codeboxr.com
 * @since      2.0.0
 * @package    cbxmcratingreview
 * @subpackage cbxmcratingreview/templates/admin
 */
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<div class="section_header row">
    <div class="col-12 section_header_l">
        <h2><?php esc_html_e( 'Email notifications', 'cbxmcratingreview' ); ?></h2>
        <p><?php esc_html_e( 'Here are the list of all the email notification send from this accounting system. Please note that, few notification may sent from background without any setting based on the type of not.', 'cbxmcratingreview' ); ?></p>
    </div>
    <!--                        <div class="col-6 section_header_r"></div>-->
</div>
<div id="email_manager_listing_wrapper">
    <h3><?php esc_html_e( 'Notification list', 'cbxmcratingreview' ); ?></h3>
    <table class="table table-bordered table-striped table-hover" id="cbxmcratingreview_email_items">
        <thead>
        <tr>
            <th><?php esc_html_e( 'Title', 'cbxmcratingreview' ); ?></th>
            <th><?php esc_html_e( 'Type', 'cbxmcratingreview' ); ?></th>
            <th><?php esc_html_e( 'Recipient(s)', 'cbxmcratingreview' ); ?></th>
            <th><?php esc_html_e( 'Actions', 'cbxmcratingreview' ); ?></th>
        </tr>
        </thead>
        <tbody>
		<?php
		$admin_url = admin_url( 'admin.php?page=cbxmcratingreview-emails' );

		$enabled_svg    = cbxmcratingreview_esc_svg( cbxmcratingreview_load_svg( 'icon_enabled', 'app' ) );
		$disabled_svg   = cbxmcratingreview_esc_svg( cbxmcratingreview_load_svg( 'icon_disabled', 'app' ) );

		foreach ( $emails as $email ):
			$id = $email->id;
			$title      = $email->title;
			$settings   = $email->settings;
			$user_email = $email->is_user_email();

			if ( ! is_array( $settings ) ) {
				$settings = [];
			}

			$enabled    = isset( $settings['enabled'] ) ? $settings['enabled'] : '';
			$email_type = isset( $settings['email_type'] ) ? $settings['email_type'] : 'html';

			$button_status_class = ( $enabled == 'yes' ) ? 'cbxmcratingreview_email_status_enabled' : 'cbxmcratingreview_email_status_disabled';

			// $enabled_icon_class = ( $enabled == 'yes' ) ? 'cbx-icon-enabled' : 'cbx-icon-disabled';
			$status_svg = ( $enabled == 'yes' ) ? $enabled_svg : $disabled_svg;

			$recipient = $email->get_recipient();

			$action_url = add_query_arg( [ 'edit' => $id ], $admin_url );
			?>
            <tr>
                <td>
                    <span class="button cbxmcratingreview_email_status <?php echo esc_attr( $button_status_class ); ?> outline secondary icon icon-only">
                        <i class="cbx-icon">
                            <?php echo $status_svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            ?>
                        </i>
                    </span>
					<?php echo esc_html( $title ); ?>
                </td>
                <td><?php echo esc_html( $email->get_content_type() ); ?></td>
                <td><?php echo ( $user_email ) ? esc_html__( 'System User/Guest', 'cbxmcratingreview' ) : esc_html( $recipient ); ?></td>
                <td><a class="button primary icon icon-inline small" href="<?php echo esc_url( $action_url ); ?>">
                        <i class="cbx-icon cbx-icon-edit-white"></i>
                        <span class="button-label"><?php esc_html_e( 'Edit', 'cbxmcratingreview' ); ?></span>
                    </a>
                </td>
            </tr>
		<?php endforeach; ?>
        </tbody>
    </table>
</div>