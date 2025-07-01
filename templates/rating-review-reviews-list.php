<?php
/**
 * Provides review list
 *
 * This file is used to markup frontend review list and includes sub templates
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
do_action( 'cbxmcratingreview_review_list_before', $post_reviews );
?>
    <div class="cbx-chota">
        <div class="container">
            <ul class="<?php echo apply_filters( 'cbxmcratingreview_review_list_items_class', 'cbxmcratingreview_review_list_items' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"
                id="cbxmcratingreview_review_list_items_<?php echo intval( $post_id ); ?>">
		        <?php
		        if ( sizeof( $post_reviews ) > 0 ) {
			        foreach ( $post_reviews as $index => $post_review ) { ?>
                        <li id="cbxmcratingreview_review_list_item_<?php echo intval( $post_review['id'] ); ?>"
                            class="<?php echo apply_filters( 'cbxmcratingreview_review_list_item_class', 'cbxmcratingreview_review_list_item' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
					        <?php
					        //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					        echo cbxmcratingreview_get_template_html( 'rating-review-reviews-list-item.php', [
						        'post_review' => $post_review,
					        ] );
					        ?>
                        </li>
			        <?php }
		        } else {
			        ?>
                    <li class="<?php echo apply_filters( 'cbxmcratingreview_review_list_item_class_notfound_class', 'cbxmcratingreview_review_list_item cbxmcratingreview_review_list_item_notfound' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
                        <p class="no_reviews_found"><?php esc_html_e( 'No reviews yet!', 'cbxmcratingreview' ); ?></p>
                    </li>
			        <?php
		        }

		        ?>
            </ul>
        </div>
    </div>

<?php
do_action( 'cbxmcratingreview_review_list_after', $post_reviews );