<?php
/**
 * Provides review read only rating
 *
 * This file is used to markup frontend review read only rating
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
do_action( 'cbxmcratingreview_user_rating_readonly_before', $post_review_by_user );
?>

    <div class="cbxmcratingreview_template_user_rating_readonly">
        <span data-processed="0" data-score="<?php echo floatval( $post_review_by_user['score'] ); ?>"
              class="cbxmcratingreview_readonlyrating cbxmcratingreview_readonlyrating_score  cbxmcratingreview_readonlyrating_score_js"></span>
        <span class="cbxmcratingreview_readonlyrating cbxmcratingreview_readonlyrating_info">
		<?php esc_html_e( 'You rated this ', 'cbxmcratingreview' );
		echo number_format_i18n( $post_review_by_user['score'], 1 ) . '/' . number_format_i18n( 5 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</span>
    </div>

<?php
do_action( 'cbxmcratingreview_user_rating_readonly_after', $post_review_by_user );