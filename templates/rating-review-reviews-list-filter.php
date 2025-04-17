<?php
/**
 * Provides review list filter box
 *
 * This file is used to markup frontend review list filter box
 *
 * @link       https://codeboxr.com
 * @since      1.0.0
 *
 * @package    cbxmcratingreview
 * @subpackage cbxmcratingreview/templates
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<?php

$enable_positive_critical = intval( $settings->get_field( 'enable_positive_critical', 'cbxmcratingreview_common_config', 1 ) );

$all_reviews_link = get_permalink();

$order_by = get_query_var( 'orderby', 'id' );
$order    = get_query_var( 'order', 'DESC' );
$score    = isset( $_GET['score'] ) ? intval( $_GET['score'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

$style = ( $total_reviews == 0 ) ? ' display: none;' : '';

?>

<div class="clearfix cbxmcratingreview_clearfix clear"></div>
<div class="cbx-chota">
    <div class="container">
        <div style="<?php echo esc_html( $style ); ?>" class="cbxmcratingreview_search_wrapper"
             id="cbxmcratingreview_search_wrapper_<?php echo intval( $post_id ); ?>" data-busy="0"
             data-postid="<?php echo intval( $post_id ); ?>" data-formid="<?php echo absint( $form_id ); ?>"
             data-perpage="<?php echo intval( $perpage ); ?>"
             data-orderby="<?php echo esc_attr( $order_by ); ?>" data-order="<?php echo esc_attr( $order ); ?>">

            <div class="cbxmcratingreview_search_wrapper_filters" action="">
                <label class="sr-only" for="score"><?php esc_html_e( 'Score', 'cbxmcratingreview' ); ?></label>
                <select class="custom-select form-control cbxmcratingreview_search_wrapper_filter cbxmcratingreview_search_filter_score cbxmcratingreview_search_filter_js"
                        name="score">
                    <option value=""><?php esc_html_e( 'All Stars', 'cbxmcratingreview' ); ?></option>
				    <?php
				    for ( $score_val = 1; $score_val <= 5; $score_val ++ ) {
					    /* translators: %s: star count  */
					    $score_text = sprintf( _n( '%s star', '%s stars', $score_val, 'cbxmcratingreview' ), $score_val );
					    echo '<option ' . selected( $score, $score_val, false ) . ' value="' . esc_attr( $score_val ) . '">' . esc_html( $score_text ) . '</option>';
				    }

				    //
				    if ( $enable_positive_critical ) {
					    echo '<option ' . selected( $score, - 1, false ) . ' value="-1">' . esc_html__( 'All Positive', 'cbxmcratingreview' ) . '</option>';
					    echo '<option ' . selected( $score, - 2, false ) . ' value="-2">' . esc_html__( 'All Critical', 'cbxmcratingreview' ) . '</option>';
				    }
				    ?>
                </select>
                <label class="sr-only"
                       for="search_reviews_orderby"><?php esc_html_e( 'Order By', 'cbxmcratingreview' ); ?></label>
                <select class="custom-select form-control cbxmcratingreview_search_wrapper_filter cbxmcratingreview_search_filter_orderby cbxmcratingreview_search_filter_js"
                        name="orderby">
                    <option <?php selected( $order_by, 'id' ); ?>
                            value="id"><?php esc_html_e( 'Most Recent', 'cbxmcratingreview' ); ?></option>
                    <option <?php selected( $order_by, 'score' ); ?>
                            value="score"><?php esc_html_e( 'Rating Score', 'cbxmcratingreview' ); ?></option>
                </select>
                <label class="sr-only"
                       for="search_archive_order"><?php esc_html_e( 'Order', 'cbxmcratingreview' ); ?></label>
                <select class="custom-select form-control cbxmcratingreview_search_wrapper_filter cbxmcratingreview_search_filter_order cbxmcratingreview_search_filter_js"
                        name="order">
                    <option <?php selected( $order, 'DESC' ); ?>
                            value="DESC"><?php esc_html_e( 'DESC', 'cbxmcratingreview' ); ?></option>
                    <option <?php selected( $order, 'ASC' ); ?>
                            value="ASC"><?php esc_html_e( 'ASC', 'cbxmcratingreview' ); ?></option>
                </select>
                <div class="clearfix cbxmcratingreview_clearfix clear"></div>
            </div>
        </div>
    </div>
</div>
<div class="clearfix cbxmcratingreview_clearfix clear"></div>

