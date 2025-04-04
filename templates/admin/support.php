<?php

use CBX\MCRatingReview\Helpers\CBXMCRatingReviewHelper;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<?php
$plugin_url = CBXMCRatingReviewHelper::url_utmy( 'https://codeboxr.com/product/cbx-multi-criteria-rating-review-for-wordpress/' );
$doc_url    = CBXMCRatingReviewHelper::url_utmy( 'https://codeboxr.com/doc/cbxmcratingreview-doc/' );
$more_v_svg = cbxmcratingreview_esc_svg( cbxmcratingreview_load_svg( 'icon_more_v' ) );
?>
<div class="wrap cbx-chota cbx-page-wrapper cbxmcratingreview-page-wrapper cbxmcratingreview-support-wrapper"
     id="cbxmcratingreview-support">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2></h2>
				<?php do_action( 'cbxmcratingreview_wpheading_wrap_before', 'support' ); ?>
                <div class="wp-heading-wrap">
                    <div class="wp-heading-wrap-left pull-left">
						<?php do_action( 'cbxmcratingreview_wpheading_wrap_left_before', 'support' ); ?>
                        <h1 class="wp-heading-inline wp-heading-inline-cbxmcratingreview">
							<?php esc_html_e( 'CBX Multi Criteria Rating & Review: Support & Docs', 'cbxmcratingreview' ); ?>
                        </h1>
						<?php do_action( 'cbxmcratingreview_wpheading_wrap_left_before', 'support' ); ?>
                    </div>
                    <div class="wp-heading-wrap-right  pull-right">
						<?php do_action( 'cbxmcratingreview_wpheading_wrap_right_before', 'support' ); ?>
                        <!--<a href="<?php /*echo admin_url( 'admin.php?page=cbxmcratingreview_settings' ); */ ?>" class="button outline primary"><?php /*esc_html_e( 'Global Settings', 'cbxmcratingreview' ); */ ?></a>-->
                        <div class="button_actions button_actions-global-menu">
                            <details class="dropdown dropdown-menu ml-10">
                                <summary class="button icon icon-only outline primary icon-inline">
                                    <i class="cbx-icon">
										<?php echo $more_v_svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
                                    </i>
                                </summary>
                                <div class="card card-menu card-menu-right">
									<?php
									$menus = CBXMCRatingReviewHelper::dashboard_menus();
									?>
                                    <ul>
										<?php
										foreach ( $menus as $menu ) { ?>
                                            <li>
                                                <a href="<?php echo esc_url( $menu['url'] ); ?>" class="button outline"
                                                   role="button"
                                                   title="<?php echo esc_attr( $menu['title'] ); ?>"><?php echo esc_html( $menu['label'] ); ?>
                                                </a>
                                            </li>
											<?php
										}
										?>
                                    </ul>
                                </div>
                            </details>
                        </div>
						<?php do_action( 'cbxmcratingreview_wpheading_wrap_right_after', 'support' ); ?>
                    </div>
                </div>
				<?php do_action( 'cbxmcratingreview_wpheading_wrap_after', 'support' ); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="cbx-backend-card">
                    <div class="header">
                        <div class="text">
                            <h2><?php esc_html_e( 'Get Free & Pro Addons', 'cbxmcratingreview' ); ?></h2>
                        </div>
                    </div>
                    <div class="content">
                        <div class="cbx-backend-row">
                            <div class="cbx-backend-col col-xs-12 text-xs-left">
                                <div class="cbx-backend-feature-card">
                                    <div class="feature-card-body static">
                                        <div class="feature-card-header">
                                            <a href="<?php echo esc_url( $plugin_url ); ?>"
                                               target="_blank"> <img
                                                        src="https://codeboxr.com/wp-content/uploads/productshots/14861-banner.webp"
                                                        alt="CBX Multi Criteria Rating & Review for WordPress"/> </a>

                                        </div>
                                        <div class="feature-card-description">
                                            <h3>
                                                <a href="<?php echo esc_url( $plugin_url ); ?>"
                                                   target="_blank">CBX Multi Criteria Rating & Review Pro Addon</a></h3>
                                            <p>Pro features for CBX Multi Criteria Rating & Review.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="cbx-backend-card dashboard-changelog">
                    <div class="header">
                        <div class="text">
                            <h2><?php esc_html_e( 'Core Plugin Changelog', 'cbxmcratingreview' ); ?></h2>
                        </div>
                    </div>
                    <div class="content">
                        <div class="cbx-backend-settings-row">
                            <p>
                                Version - 2.0.1
                            </p>
                            <ul>
                                <li>[fixed] Two Classic widgets were commented in code in last release, now fixed</li>
                                <li>[updated] Dashboard support page updated</li>
                            </ul>
                        </div>
                        <div class="cbx-backend-settings-row">
                            <p>
                                Version - 2.0.0
                            </p>
                            <ul>
                                <li>[new] Totally refreshed dashboard</li>
                                <li>[new] WordPress core V6.7.2 compatible</li>
                                <li>[new] Plugin check plugin V1.4.0 compatible</li>
                                <li>[new] Pro Addon plugin V2.0.0 compatible</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="cbx-backend-card dashboard-changelog">
                    <div class="header">
                        <div class="text">
                            <h2><?php esc_html_e( 'Pro Addon Changelog', 'cbxmcratingreview' ); ?></h2>
                        </div>
                    </div>
                    <div class="content">
                        <div class="cbx-backend-settings-row">
                            <div class="cbx-backend-settings-row">
                                <p>
                                    Version - 2.0.1
                                </p>
                                <ul>
                                    <li>[updated] Style improvement for dashboard</li>
                                    <li>[updated] Core plugin V2.0.1 released</li>
                                </ul>
                            </div>
                            <div class="cbx-backend-settings-row">
                                <p>
                                    Version - 2.0.0
                                </p>
                                <ul>
                                    <li>[new] Totally refreshed dashboard</li>
                                    <li>[new] WordPress core V6.7.2 compatible</li>
                                    <li>[new] Plugin check plugin V1.4.0 compatible</li>
                                    <li>[new] Core plugin V2.0.0 compatible</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="cbx-backend-card dashboard-wp-plugin">
                    <div class="header">
                        <div class="text">
                            <h2><?php esc_html_e( 'Other WordPress Plugins', 'cbxmcratingreview' ); ?></h2>
                        </div>
                    </div>
                    <div class="content">
						<?php

						include_once( ABSPATH . WPINC . '/feed.php' );
						if ( function_exists( 'fetch_feed' ) ) {
							$feed = fetch_feed( 'https://codeboxr.com/product_cat/wordpress/feed/' );
							if ( ! is_wp_error( $feed ) ) : $feed->init();
								$feed->set_output_encoding( 'UTF-8' );   // this is the encoding parameter, and can be left unchanged in almost every case
								$feed->handle_content_type();            // this double-checks the encoding type
								$feed->set_cache_duration( 21600 );      // 21,600 seconds is six hours
								$limit = $feed->get_item_quantity( 20 ); // fetches the 18 most recent RSS feed stories
								$items = $feed->get_items( 0, $limit );  // this sets the limit and array for parsing the feed

								$blocks = array_slice( $items, 0, 20 );


								foreach ( $blocks as $block ) {
									$url = $block->get_permalink();
									$url = CBXMCRatingReviewHelper::url_utmy( $url ); ?>
                                    <div class="cbx-backend-settings-row">
                                        <a href="<?php echo esc_url( $url ) ?>" target="_blank">
                                            <svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                <defs/>
                                                <path d="M16.4 9.1L12.2 5c-.3-.3-.7-.3-1-.2s-.6.5-.6.9v1.7H4.2c-.5 0-.9.4-.9.9v3.4c0 .2.1.5.3.7.2.2.4.3.7.3h6.4v1.7c0 .4.2.7.6.9.4.1.8.1 1-.2l4.1-4.2c.4-.5.4-1.3 0-1.8z"
                                                      fill="currentColor"/>
                                            </svg>
											<?php echo esc_attr( $block->get_title() ); ?></a>
                                    </div>
									<?php
								}//end foreach
							endif;
						}
						?>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="cbx-backend-card dashboard-support">
                    <div class="header">
                        <div class="text">
                            <h2><?php esc_html_e( 'Help & Supports', 'cbxmcratingreview' ); ?></h2>
                        </div>
                    </div>
                    <div class="content">
                        <div class="cbx-backend-settings-row">
                            <a href="<?php echo esc_url( $plugin_url ); ?>" target="_blank">
                                <svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <defs/>
                                    <path d="M10 2.6c-4.4 0-7.9 3.6-7.9 7.9s3.6 7.9 7.9 7.9 7.9-3.6 7.9-7.9-3.5-7.9-7.9-7.9zm1.7 12.3c-.4.2-.7.3-1 .4-.2.1-.5.1-.8.1-.5 0-.9-.1-1.2-.4-.3-.2-.4-.5-.4-.9v-.4c0-.2.1-.3.1-.5l.5-1.8c0-.2.1-.4.1-.5v-.4c0-.2 0-.4-.1-.5-.1-.1-.3-.2-.5-.2-.1 0-.3 0-.4.1-.2 0-.3.1-.4.1l.1-.6c.3-.1.7-.3 1-.3.3-.1.6-.2.9-.2.5 0 .9.1 1.1.4.3.2.4.5.4.9v.4c0 .2-.1.4-.1.5l-.5 1.9c0 .1-.1.3-.1.5v.4c0 .2.1.4.2.5.1.1.3.1.6.1.1 0 .3 0 .4-.1.2 0 .3-.1.3-.1l-.2.6zm-.1-7.3c-.2.2-.5.3-.9.3-.3 0-.6-.1-.9-.3-.2-.2-.3-.5-.3-.8 0-.3.1-.6.4-.8.2-.2.5-.3.9-.3.3 0 .6.1.9.3.2.2.4.5.4.8-.2.3-.3.6-.5.8z"
                                          fill="currentColor"/>
                                </svg>
								<?php esc_html_e( 'CBX Multi Criteria Rating & Review for WordPress', 'cbxmcratingreview' ); ?>
                            </a>
                        </div>
                        <div class="cbx-backend-settings-row">
                            <a href="<?php echo esc_url( $doc_url ); ?>" target="_blank">
                                <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M14.5834 3.75C12.9584 3.75 11.2084 4.08333 10 5C8.79171 4.08333 7.04171 3.75 5.41671 3.75C4.20837 3.75 2.92504 3.93333 1.85004 4.40833C1.24171 4.68333 0.833374 5.275 0.833374 5.95V15.35C0.833374 16.4333 1.85004 17.2333 2.90004 16.9667C3.71671 16.7583 4.58337 16.6667 5.41671 16.6667C6.71671 16.6667 8.10004 16.8833 9.21671 17.4333C9.71671 17.6833 10.2834 17.6833 10.775 17.4333C11.8917 16.875 13.275 16.6667 14.575 16.6667C15.4084 16.6667 16.275 16.7583 17.0917 16.9667C18.1417 17.2417 19.1584 16.4417 19.1584 15.35V5.95C19.1584 5.275 18.75 4.68333 18.1417 4.40833C17.075 3.93333 15.7917 3.75 14.5834 3.75ZM17.5 14.3583C17.5 14.8833 17.0167 15.2667 16.5 15.175C15.875 15.0583 15.225 15.0083 14.5834 15.0083C13.1667 15.0083 11.125 15.55 10 16.2583V6.66667C11.125 5.95833 13.1667 5.41667 14.5834 5.41667C15.35 5.41667 16.1084 5.49167 16.8334 5.65C17.2167 5.73333 17.5 6.075 17.5 6.46667V14.3583Z"
                                          fill="currentColor"></path>
                                    <path d="M11.65 9.17504C11.3833 9.17504 11.1416 9.00837 11.0583 8.74171C10.95 8.41671 11.1333 8.05838 11.4583 7.95838C12.7416 7.54171 14.4 7.40838 15.925 7.58338C16.2666 7.62504 16.5166 7.93338 16.475 8.27504C16.4333 8.61671 16.125 8.86671 15.7833 8.82504C14.4333 8.66671 12.9583 8.79171 11.8416 9.15004C11.775 9.15837 11.7083 9.17504 11.65 9.17504ZM11.65 11.3917C11.3833 11.3917 11.1416 11.225 11.0583 10.9584C10.95 10.6334 11.1333 10.275 11.4583 10.175C12.7333 9.75837 14.4 9.62504 15.925 9.80004C16.2666 9.84171 16.5166 10.15 16.475 10.4917C16.4333 10.8334 16.125 11.0834 15.7833 11.0417C14.4333 10.8834 12.9583 11.0084 11.8416 11.3667C11.779 11.3827 11.7146 11.3911 11.65 11.3917ZM11.65 13.6084C11.3833 13.6084 11.1416 13.4417 11.0583 13.175C10.95 12.85 11.1333 12.4917 11.4583 12.3917C12.7333 11.975 14.4 11.8417 15.925 12.0167C16.2666 12.0584 16.5166 12.3667 16.475 12.7084C16.4333 13.05 16.125 13.2917 15.7833 13.2584C14.4333 13.1 12.9583 13.225 11.8416 13.5834C11.779 13.5993 11.7146 13.6077 11.65 13.6084Z"
                                          fill="currentColor"></path>
                                </svg>
								<?php esc_html_e( 'Documentation & User Guide', 'cbxmcratingreview' ); ?> </a>
                        </div>
                        <div class="cbx-backend-settings-row">
                            <a href="https://wordpress.org/support/plugin/cbxmcratingreview/reviews/#new-post"
                               target="_blank">
                                <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M18.3 8.1c-.1-.3-.3-.5-.6-.5l-4.8-.7-2.2-4.4c-.1-.3-.4-.4-.7-.4-.3 0-.5.2-.7.4L7.2 6.9l-4.9.7c-.3 0-.5.2-.6.5-.1.3 0 .6.2.8l3.5 3.4-.8 4.7c0 .3.1.6.3.7.1.1.3.1.4.1.1 0 .2 0 .4-.1l4.3-2.3 4.3 2.3c.1.1.2.1.4.1.4 0 .8-.3.8-.8v-.2l-.8-4.8 3.5-3.4c.1 0 .2-.3.1-.5z"
                                          fill="currentColor"/>
                                </svg>
								<?php esc_html_e( 'CBX Multi Criteria Rating & Review for WordPress', 'cbxmcratingreview' ); ?>
                            </a>
                        </div>
                        <div class="cbx-backend-settings-row">
                            <a href="https://wordpress.org/support/plugin/cbxmcratingreview/" target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 22 22">
                                    <defs/>
                                    <path fill="currentColor" fill-rule="evenodd"
                                          d="M16 2H3c-.55 0-1 .45-1 1v14l4-4h10c.55 0 1-.45 1-1V3c0-.55-.45-1-1-1zm-1 2v7H5.17L4 12.17V4h11zm4 2h2c.55 0 1 .45 1 1v15l-4-4H7c-.55 0-1-.45-1-1v-2h13V6z"
                                          clip-rule="evenodd"/>
                                </svg>
								<?php esc_html_e( 'Core Plugin Support', 'cbxmcratingreview' ); ?></a>
                        </div>
                        <div class="cbx-backend-settings-row">
                            <a href="https://codeboxr.com/contact-us" target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 22 22">
                                    <defs/>
                                    <path fill="currentColor" fill-rule="evenodd"
                                          d="M16 2H3c-.55 0-1 .45-1 1v14l4-4h10c.55 0 1-.45 1-1V3c0-.55-.45-1-1-1zm-1 2v7H5.17L4 12.17V4h11zm4 2h2c.55 0 1 .45 1 1v15l-4-4H7c-.55 0-1-.45-1-1v-2h13V6z"
                                          clip-rule="evenodd"/>
                                </svg>
								<?php esc_html_e( 'Pro Addon Support', 'cbxmcratingreview' ); ?></a>
                        </div>
                    </div>
                </div>

                <div class="cbx-backend-card dashboard-wp-plugin">
                    <div class="header">
                        <div class="text">
                            <h2><?php esc_html_e( 'Codeboxr News Updates', 'cbxmcratingreview' ); ?></h2>
                        </div>
                    </div>
                    <div class="content">
						<?php
						$items = CBXMCRatingReviewHelper::codeboxr_news_feed();
						if ( $items !== false && count( $items ) > 0 ) {
							foreach ( $items as $item ) {
								$url   = $item['url'];
								$title = $item['title'];

								echo '<div class="cbx-backend-settings-row">';
								echo '<a href="' . esc_url( $url ) . '" target="_blank">';
								echo '<svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                <defs/>
                                                <path d="M16.4 9.1L12.2 5c-.3-.3-.7-.3-1-.2s-.6.5-.6.9v1.7H4.2c-.5 0-.9.4-.9.9v3.4c0 .2.1.5.3.7.2.2.4.3.7.3h6.4v1.7c0 .4.2.7.6.9.4.1.8.1 1-.2l4.1-4.2c.4-.5.4-1.3 0-1.8z"
                                                      fill="currentColor"/>
                                            </svg>';

								//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								echo $title;
								echo '</a>';
								echo '</div>';
							}//end for loop
						}//if data found
						?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>