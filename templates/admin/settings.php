<?php

use CBX\MCRatingReview\Helpers\CBXMCRatingReviewAdminHelper;

/**
 * Provide a dashboard Setting
 *
 * This file is used to markup the admin setting page
 *
 * @link       https://codeboxr.com
 * @since      1.0.0
 *
 * @package    cbxmcratingreview
 * @subpackage cbxmcratingreview/templates/admin
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

$plugin_url = CBXMCRatingReviewAdminHelper::url_utmy( 'https://codeboxr.com/product/cbx-multi-criteria-rating-review-for-wordpress/' );
$doc_url    = CBXMCRatingReviewAdminHelper::url_utmy( 'https://codeboxr.com/product/cbx-multi-criteria-rating-review-for-wordpress/' );

$more_v_svg = cbxmcratingreview_esc_svg( cbxmcratingreview_load_svg( 'icon_more_v' ) );
$save_svg   = cbxmcratingreview_esc_svg( cbxmcratingreview_load_svg( 'icon_save' ) );
?>
<div class="wrap cbx-chota cbxchota-setting-common cbx-page-wrapper cbxmcratingreview-page-wrapper cbxmcratingreview-setting-wrapper"
     id="cbxmcratingreview-setting">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2></h2>
				<?php
				settings_errors();
				?>
				<?php do_action( 'cbxmcratingreview_wpheading_wrap_before', 'settings' ); ?>
                <div class="wp-heading-wrap">
                    <div class="wp-heading-wrap-left pull-left">
						<?php do_action( 'cbxmcratingreview_wpheading_wrap_left_before', 'settings' ); ?>
                        <h1 class="wp-heading-inline wp-heading-inline-cbxmcratingreview">
							<?php esc_html_e( 'CBX Multi Criteria Rating & Review: Setting', 'cbxmcratingreview' ); ?>
                        </h1>
						<?php do_action( 'cbxmcratingreview_wpheading_wrap_left_before', 'settings' ); ?>
                    </div>
                    <div class="wp-heading-wrap-right  pull-right">
						<?php do_action( 'cbxmcratingreview_wpheading_wrap_right_before', 'settings' ); ?>
                        <a href="#" id="save_settings"
                           class="button primary icon icon-inline icon-right mr-5">
                            <i class="cbx-icon">
								<?php echo $save_svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
                            </i>
                            <span class="button-label"><?php esc_html_e( 'Save Settings', 'cbxmcratingreview' ); ?></span>
                        </a>
                        <div class="button_actions button_actions-global-menu">
                            <details class="dropdown dropdown-menu ml-10">
                                <summary class="button icon icon-only outline primary icon-inline"><i
                                            class="cbx-icon"><?php echo $more_v_svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></i>
                                </summary>
                                <div class="card card-menu card-menu-right">
									<?php
									$menus = CBXMCRatingReviewAdminHelper::dashboard_menus();
									?>
                                    <ul>
										<?php
										foreach ( $menus as $menu ) { ?>
                                            <li>
                                                <a href="<?php echo esc_url( $menu['url'] ); ?>" class="button outline"
                                                   role="button"
                                                   title="<?php echo esc_attr( $menu['title-attr'] ); ?>"><?php echo esc_attr( $menu['title'] ); ?>
                                                </a>
                                            </li>
											<?php
										}
										?>
                                    </ul>
                                </div>
                            </details>
                        </div>
						<?php do_action( 'cbxmcratingreview_wpheading_wrap_right_after', 'settings' ); ?>
                    </div>
                </div>
				<?php do_action( 'cbxmcratingreview_wpheading_wrap_after', 'settings' ); ?>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-12">
				<?php do_action( 'cbxmcratingreview_settings_form_before', 'settings' ); ?>
                <div class="postbox">
                    <div class="clear clearfix"></div>
                    <div class="inside setting-form-wrap">
						<?php do_action( 'cbxmcratingreview_settings_form_start', 'settings' ); ?>
						<?php
						$settings->show_navigation();
						$settings->show_forms();
						?>
						<?php do_action( 'cbxmcratingreview_settings_form_end', 'settings' ); ?>
                    </div>
                    <div class="clear clearfix"></div>
                </div>
				<?php do_action( 'cbxmcratingreview_settings_form_after', 'settings' ); ?>
            </div>
        </div>
    </div>
</div>