<?php
/**
 * Provides most rated post templates
 *
 * This file is used to markup most rated posts widget/shortcode/display
 *
 * @link       https://codeboxr.com
 * @since      1.0.0
 *
 * @package    cbxmcratingreview
 * @subpackage cbxmcratingreview/templates/widgets
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

//post data needs to be in variable $data_posts
?>
<div class="cbxmcratingreviewmrposts_wrapper">
	<?php
	do_action( 'cbxmcratingreviewmrposts_list_before' );
	?>
	<?php
	if ( $title != '' && $scope != 'widget' ):
		echo '<h2 class="cbxmcratingreviewmrposts_list_title">' . esc_html( $title ) . '</h2>';
	endif;
	?>
    <ul class="cbxmcratingreviewmrposts_list cbxmcratingreviewmrposts_<?php echo esc_attr( $scope ); ?>">
		<?php
		if ( is_array( $data_posts ) && sizeof( $data_posts ) > 0 ) {
			foreach ( $data_posts as $index => $single_post ) { ?>
                <li>
					<?php
					do_action( 'cbxmcratingreviewmrposts_list_item_before', $single_post );
					?>
					<?php
					echo '<span data-processed="0" data-score="' . floatval( $single_post['avg_rating'] ?? 0 ) . '" class="cbxmcratingreview_readonlyrating cbxmcratingreview_readonlyrating_score cbxmcratingreview_readonlyrating_score_js"></span>';
					?>
                    <span class="cbxmcratingreview_readonlyrating cbxmcratingreview_readonlyrating_info">
							<?php echo number_format_i18n( $single_post['avg_rating'] ?? 0, 2 ) . '/' . number_format_i18n( 5 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        (<?php echo intval( $single_post['total_count'] ?? 0 ); ?> <?php echo ( $single_post['total_count'] ?? 0 == 0 ) ? esc_html__( 'Review', 'cbxmcratingreview' ) : _n( 'Review', 'Reviews', $single_post['total_count'] ?? 0, 'cbxmcratingreview' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
                        )
					</span>
                    <a href="<?php echo esc_url( get_permalink( intval( $single_post['post_id'] ) ) ); ?>"><?php echo esc_html( get_the_title( intval( $single_post['post_id'] ) ) ); ?></a>
					<?php
					do_action( 'cbxmcratingreviewmrposts_list_item_after', $single_post );
					?>
                </li>
			<?php }
		} else {
			echo '<li>' . esc_html__( 'No items found', 'cbxmcratingreview' ) . '</li>';
		}
		?>
    </ul>
	<?php
	do_action( 'cbxmcratingreviewmrposts_list_after' );
	?>
</div>