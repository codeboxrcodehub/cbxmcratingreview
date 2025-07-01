<?php
/**
 * Review added by user email for admin
 *
 * This template can be overridden by copying it to yourtheme/cbxmcratingreview/emails/new_review_admin_alert.php
 *
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

do_action( 'cbxmcratingreview_email_header', $email_heading, $email ); ?>
    <p><?php echo esc_html__( 'Dear Admin,', 'cbxmcratingreview' ); ?></p>
    <p><?php echo esc_html__( 'A new review is made.', 'cbxmcratingreview' ); ?></p>
    <h2><?php echo esc_html__( 'Review Details:', 'cbxmcratingreview' ); ?></h2>
    <p style='margin-bottom: 0;'><?php echo esc_html__( 'Rating: {review_score}', 'cbxmcratingreview' ); ?></p>
    <p style='margin-bottom: 0;'><?php echo esc_html__( 'Title: {review_headline}', 'cbxmcratingreview' ); ?></p>
    <p style='margin-bottom: 0;'><?php echo esc_html__( 'Review: {review_comment}', 'cbxmcratingreview' ); ?></p>
    <p style='margin-bottom: 0;'><?php echo esc_html__( 'Review status: {review_status}', 'cbxmcratingreview' ); ?></p>
    <p><?php echo esc_html__( 'Post: {post_link}', 'cbxmcratingreview' ); ?></p>
    <h2><?php echo esc_html__( 'Further action', 'cbxmcratingreview' ); ?></h2>
    <p><?php echo '{review_edit_url}'; ?></p>
    <p><?php echo esc_html__( 'Please check &amp; do necessary steps and give feedback to client. Thank you.', 'cbxmcratingreview' ); ?></p>
<?php
/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( $additional_content ) {
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
}
?>

<?php
do_action( 'cbxmcratingreview_email_footer', $email );