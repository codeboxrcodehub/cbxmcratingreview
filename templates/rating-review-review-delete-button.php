<?php
/**
 * Provides review delete button
 *
 * This file is used to markup frontend review delete button
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
$delete_svg = cbxmcratingreview_esc_svg( cbxmcratingreview_load_svg( 'icon_delete_white' ) );
?>
<span class="cbxmcratingreview_review_list_item_toolbar_item cbxmcratingreview_review_list_item_toolbar_item_deletebutton">
	<a href="#" class="cbxmcratingreview-review-delete button error icon icon-only" data-busy="0"
       data-review_id="<?php echo intval( $post_review['id'] ) ?>"
       data-post_id="<?php echo intval( $post_review['post_id'] ) ?>"
       aria-label="<?php esc_attr_e( 'Click to delete this review', 'cbxmcratingreview' ); ?>"
       title="<?php esc_attr_e( 'Click to delete this review', 'cbxmcratingreview' ); ?>" data-balloon-pos="up">
        <i class="cbx-icon"><?php echo $delete_svg; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></i>
        <span class="button-label sr-only"><?php esc_html_e( 'Delete Review', 'cbxmcratingreview' ); ?></span></a>
    </a>
</span>