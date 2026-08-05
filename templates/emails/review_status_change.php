<?php
/**
 * Review added by user email for admin
 *
 * This template can be overridden by copying it to yourtheme/cbxmcratingreview/emails/review_status_change.php
 *
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

do_action( 'cbxmcratingreview_email_header', $email_heading, $email ); ?>
    <!-- User Review Notification Section -->
    <div class="content-section">
        <h2 class="heading">{email_heading}</h2>
        <p class="message">
            <?php echo esc_html__( 'Dear {review_user_name}', 'cbxmcratingreview' ); ?>,<br><br>

            <?php echo wp_kses(__( 'Your review status has been updated to <strong>{review_status}</strong>. Please find the latest details below.', 'cbxmcratingreview' ), ['strong' => []]); ?>
        </p>
        <div class="form-summary-section">
            <h3 class="form-summary-heading"><?php echo esc_html__( 'Review Details:', 'cbxmcratingreview' ); ?></h3>
            <table role="presentation" class="form-summary-table" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="form-label" width="35%"><?php echo esc_html__( 'Rating:', 'cbxmcratingreview' ); ?></td>
                    <td class="form-value">{review_score} / 5 ⭐</td>
                </tr>

                <tr>
                    <td class="form-label"><?php echo esc_html__( 'Title:', 'cbxmcratingreview' ); ?>:</td>
                    <td class="form-value">{review_headline}</td>
                </tr>

                <tr>
                    <td class="form-label" style="vertical-align: top;"><?php echo esc_html__( 'Review:', 'cbxmcratingreview' ); ?></td>
                    <td class="form-value">
                        <p>{review_comment}</p>
                    </td>
                </tr>

                <tr>
                    <td class="form-label"><?php echo esc_html__( 'Status:', 'cbxmcratingreview' ); ?></td>
                    <td class="form-value">
                        {review_status}
                    </td>
                </tr>

                <tr>
                    <td class="form-label">Post:</td>
                    <td class="form-value">
                        {post_link}
                    </td>
                </tr>

            </table>

            <div class="form-summary-footer">
                {review_date_human}
            </div>
        </div>


        <p class="message">
            <strong><?php esc_html_e( 'Next Steps:', 'cbxmcratingreview' ); ?></strong><br>
            <?php esc_html_e('If you have any questions regarding this update or believe this change was made in error, please contact our support team for assistance.', 'cbxmcratingreview'); ?>
        </p>
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td class="button-center">
                    <a href="{post_url}" class="button"><?php esc_html_e('View Review', 'cbxmcratingreview'); ?></a>
                    <!--                    <a href="https://yoursite.com/admin/reply" class="button button-secondary">Respond</a>-->
                </td>
            </tr>
        </table>

        <p class="message" style="margin-top:25px;">
            <?php esc_html_e( 'Thank you', 'cbxmcratingreview' ); ?>,<br>
        </p>
    </div>
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