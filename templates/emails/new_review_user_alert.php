<?php
/**
 * Review added by user email for admin
 *
 * This template can be overridden by copying it to yourtheme/cbxmcratingreview/emails/new_review_admin_alert.php
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'cbxmcratingreview_email_header', $email_heading, $email ); ?>
    <p><?php echo esc_html__( 'Dear {review_user_name},', 'cbxmcratingreview' ); ?></p>
    <p><?php echo esc_html__( 'We got a review for email address {review_user_email}.', 'cbxmcratingreview' ); ?></p>

    <h2><?php echo esc_html__( 'Review Details:', 'cbxmcratingreview' ); ?></h2>

    <p style='margin-bottom: 0;'><?php echo esc_html__( 'Rating: {review_score}', 'cbxmcratingreview' ); ?></p>
    <p style='margin-bottom: 0;'><?php echo esc_html__( 'Title: {review_headline}', 'cbxmcratingreview' ); ?></p>
    <p style='margin-bottom: 0;'><?php echo esc_html__( 'Review: {review_comment}', 'cbxmcratingreview' ); ?></p>
    <p style='margin-bottom: 0;'><?php echo esc_html__( 'Review status: {review_status}', 'cbxmcratingreview' ); ?></p>
    <p><?php echo esc_html__( 'Post: {post_link}', 'cbxmcratingreview' ); ?></p>
    <p><?php echo esc_html__( 'We will check and get back to you soon. Thank you.', 'cbxmcratingreview' ); ?></p>
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