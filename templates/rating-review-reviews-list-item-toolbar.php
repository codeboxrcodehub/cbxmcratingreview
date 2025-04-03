<?php
/**
 * Provides review list item toolbar
 *
 * This file is used to markup frontend review list item toolbar
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
$post_id    = intval( $post_review['post_id'] );
$post_title = get_the_title( $post_id );


?>
<div class="cbxmcratingreview_review_list_item_toolbar">
	<?php do_action( 'cbxmcratingreview_review_list_item_toolbar_start', $post_review ); ?>
    <div class="cbxmcratingreview_review_list_item_toolbar_l">
		<?php do_action( 'cbxmcratingreview_review_list_item_toolbar_left', $post_review ); ?>
    </div>
    <div class="cbxmcratingreview_review_list_item_toolbar_r">
		<?php do_action( 'cbxmcratingreview_review_list_item_toolbar_right', $post_review ); ?>
    </div>
	<?php do_action( 'cbxmcratingreview_review_list_item_toolbar_end', $post_review ); ?>
</div>
