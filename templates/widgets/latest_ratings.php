<?php
/**
 * Provides Latest Rating templates
 *
 * This file is used to markup latest ratings widget/shortcode/display
 *
 * @link       https://codeboxr.com
 * @since      1.0.0
 *
 * @package    cbxmcratingreview
 * @subpackage cbxmcratingreview/templates/widgets
 */

use CBX\MCRatingReview\Helpers\CBXMCRatingReviewHelper;

if ( ! defined( 'WPINC' ) ) {
	die;
}

//post data needs to be in variable $data_posts
?>
<div class="cbxmcratingreviewlratings_list_wrapper">
	<?php
	do_action( 'cbxmcratingreviewlratings_list_before' );
	?>

	<?php
	if ( $title != '' && $scope != 'widget' ):
		echo '<h2 class="cbxmcratingreviewlratings_list_title">' . esc_html( $title ) . '</h2>';
	endif;
	?>
    <ul class="cbxmcratingreviewlratings_list">
		<?php
		if ( is_array( $data_posts ) && sizeof( $data_posts ) > 0 ) {
			foreach ( $data_posts as $index => $single_review ) {
				$post_id = absint( $single_review['post_id'] );
				$user_id = absint( $single_review['user_id'] );

				$post_title = get_the_title( $post_id );
				$post_link  = get_permalink( $post_id );

				$user_info         = get_userdata( $user_id );
				$user_display_name = $user_info->display_name;
				$user_display_name = CBXMCRatingReviewHelper::userDisplayNameAlt( $user_info, $user_display_name );
				?>
                <li>
					<?php
					do_action( 'cbxmcratingreviewlratings_list_item_before', $single_review );
					?>
					<?php
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo '<span><a href="' . apply_filters( 'cbxmcratingreview_reviewer_posts_url', esc_url( get_author_posts_url( $user_id ) ), $single_review ) . '">' . apply_filters( 'cbxmcratingreview_reviewer_name', esc_attr( $user_display_name ), $single_review ) . '</a>' . esc_html__( ' rated ', 'cbxmcratingreview' ) . '</span>';

					echo '<span data-processed="0" data-score="' . floatval( $single_review['score'] ) . '" class="cbxmcratingreview_readonlyrating cbxmcratingreview_readonlyrating_score cbxmcratingreview_readonlyrating_score_js"></span>';
					echo '<span class="cbxmcratingreview_readonlyrating cbxmcratingreview_readonlyrating_info">(' . number_format_i18n( $single_review['score'], 1 ) . '/' . number_format_i18n( 5 ) . ')</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					?>
                    <a href="<?php echo esc_url( $post_link ); ?>"><?php echo esc_html( $post_title ); ?></a>
					<?php
					do_action( 'cbxmcratingreviewlratings_list_item_after', $single_review );
					?>
                </li>
			<?php }
		} else {
			echo '<li>' . esc_html__( 'No reviews found', 'cbxmcratingreview' ) . '</li>';
		}
		?>
    </ul>
	<?php
	do_action( 'cbxmcratingreviewlratings_list_after' );
	?>
</div>
