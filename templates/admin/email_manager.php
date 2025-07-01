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

use CBX\MCRatingReview\Helpers\CBXMCRatingReviewAdminHelper;

defined( 'ABSPATH' ) || exit;

$more_v_svg = cbxmcratingreview_esc_svg( cbxmcratingreview_load_svg( 'icon_more_v' ) );
?>
<div class="wrap cbx-chota cbxmcratingreview-page-wrapper cbxmcratingreview-email-manager-wrapper"
     id="cbxmcratingreview-email-manager">
    <div class="container">
        <div class="row">
            <div class="col-12 mb-20">
                <h2></h2>
				<?php settings_errors(); ?>
				<?php do_action( 'cbxmcratingreview_wpheading_wrap_before', 'email_manager' ); ?>
                <div class="wp-heading-wrap">
                    <div class="wp-heading-wrap-left pull-left">
						<?php do_action( 'cbxmcratingreview_wpheading_wrap_left_before', 'email_manager' ); ?>
                        <h1 class="wp-heading-inline wp-heading-inline-cbxmcratingreview">
							<?php esc_html_e( 'CBX Multi Criteria Rating & Review: Email Manager', 'cbxmcratingreview' ); ?>
                        </h1>
						<?php do_action( 'cbxmcratingreview_wpheading_wrap_left_before', 'email_manager' ); ?>
                    </div>
                    <div class="wp-heading-wrap-right pull-right">
						<?php do_action( 'cbxmcratingreview_wpheading_wrap_right_before', 'email_manager' ); ?>
						<?php
						$menus = CBXMCRatingReviewAdminHelper::dashboard_menus();
						if ( sizeof( $menus ) ):
							?>
                            <div class="button_actions button_actions-global-menu">
                                <details class="dropdown dropdown-menu ml-10">
                                    <summary class="button outline primary icon icon-only">
                                        <i class="cbx-icon">
											<?php echo $more_v_svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
											?>
                                        </i>
                                        <span class="sr-only"><?php esc_html_e( 'Dashboard Menu', 'cbxmcratingreview' ); ?></span>
                                    </summary>
                                    <div class="card card-menu card-menu-right">
                                        <ul id="dashboard_menus">
											<?php foreach ( $menus as $slug => $menu ): ?>
												<?php
												$title = $menu['title-attr'];
												$label = $menu['title'];
												$url   = $menu['url'];

												echo '<li><a class="button outline dashboard_menu dashboard_menu_' . esc_attr( $slug ) . '" role="button" title="' . esc_attr( $title ) . '" href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a></li>';

												?>
											<?php endforeach; ?>
                                        </ul>
                                    </div>
                                </details>
                            </div>
						<?php endif; ?>
						<?php do_action( 'cbxmcratingreview_wpheading_wrap_right_after', 'email_manager' ); ?>
                    </div>
                </div>
				<?php do_action( 'cbxmcratingreview_wpheading_wrap_after', 'email_manager' ); ?>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-12">
				<?php do_action( 'cbxmcratingreview_email_manager_before' ); ?>
                <div id="email_manager_wrapper">
					<?php do_action( 'cbxmcratingreview_email_manager_start', 'email_manager' ); ?>
					<?php
					$template_data = [ 'settings' => $settings ];
					if ( $edit ):
						$template_data['email'] = $emails[ $id ];
						$template_data['id']    = $id;

						echo cbxmcratingreview_get_template_html( 'admin/email_manager_edit.php', $template_data );//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					else:
						$template_data = [ 'emails' => $emails ];

						echo cbxmcratingreview_get_template_html( 'admin/email_manager_list.php', $template_data );//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					endif;
					?>
					<?php do_action( 'cbxmcratingreview_email_manager_end', 'email_manager' ); ?>
                </div>
				<?php do_action( 'cbxmcratingreview_email_manager_after', 'email_manager' ); ?>
            </div>
        </div>
    </div>
</div>