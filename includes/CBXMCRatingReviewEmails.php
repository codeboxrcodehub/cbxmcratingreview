<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
class CBXMCRatingReviewEmails {
	/**
	 * The single instance of the class
	 *
	 * @var CBXMCRatingReviewEmails
	 */
	private static $_instance = null;

	/**
	 * Array of email notification classes
	 *
	 * @var CBXMCRatingReviewEmails[]
	 */
	public $emails = [];

	//public $mail_format;

	public function __construct() {
		$this->init();

		// Email Header, Footer and content hooks.
		add_action( 'cbxmcratingreview_email_header', [ $this, 'email_header' ] );
		add_action( 'cbxmcratingreview_email_footer', [ $this, 'email_footer' ] );

		// Let 3rd parties unhook the above via this hook.
		do_action( 'cbxmcratingreview_email', $this );
	}//end method instance

	/**
	 * Init email classes.
	 */
	public function init() {
		// Include email classes.
		include_once __DIR__ . '/Emails/CBXMCRatingReviewEmail.php';

		$this->emails['new_review_admin_alert'] = include __DIR__ . '/Emails/CBXMCRatingReviewReviewAdminAlertEmail.php';
		$this->emails['new_review_user_alert']  = include __DIR__ . '/Emails/CBXMCRatingReviewReviewUserAlertEmail.php';
		$this->emails['review_status_change']   = include __DIR__ . '/Emails/CBXMCRatingReviewReviewStatusUpdateUserEmail.php';

		$this->emails = apply_filters( 'cbxmcratingreview_email_classes', $this->emails );
	}//end method clone

	/**
	 * Main CBXMCRatingReviewEmails Instance.
	 *
	 * Ensures only one instance of CBXMCRatingReviewEmails is loaded or can be loaded.
	 *
	 * @return CBXMCRatingReviewEmails Main instance
	 * @since 2.1
	 * @static
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}//end method wakeup

	/**
	 * Cloning is forbidden.
	 *
	 * @since 2.0.0
	 */
	public function __clone() {
		cbxmcratingreview_doing_it_wrong( __FUNCTION__, __( 'Cloning is forbidden.', 'cbxmcratingreview' ), '2.0.0' );
	}//end constructor

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 2.0.0
	 */
	public function __wakeup() {
		cbxmcratingreview_doing_it_wrong( __FUNCTION__, __( 'Unserializing instances of this class is forbidden.', 'cbxmcratingreview' ), '2.0.0' );
	}//end method init

	/**
	 * Get the email header.
	 *
	 * @param mixed $email_heading Heading for the email.
	 */
	public function email_header( $email_heading ) {
		$template_settings = get_option( 'cbxmcratingreview_email_tpl' );
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo cbxmcratingreview_get_template_html( 'emails/email-header.php', [
			'email_heading'     => $email_heading,
			'template_settings' => $template_settings
		] );
	}//end method email_header

	/**
	 * Get the email footer.
	 */
	public function email_footer() {
		$template_settings = get_option( 'cbxmcratingreview_email_tpl' );
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo cbxmcratingreview_get_template_html( 'emails/email-footer.php', [ 'template_settings' => $template_settings ] );
	}//end method email_footer

	/**
	 * Send the email.
	 *
	 * @param mixed $to Receiver.
	 * @param mixed $subject Email subject.
	 * @param mixed $message Message.
	 * @param string $headers Email headers (default: "Content-Type: text/html\r\n").
	 * @param string $attachments Attachments (default: "").
	 *
	 * @return bool
	 */
	public function send( $to, $subject, $message, $headers = "Content-Type: text/html\r\n", $attachments = '' ) {
		// Send.
		$email = new CBXMCRatingReviewEmail();

		return $email->send( $to, $subject, $message, $headers, $attachments );
	}//end method send

	/**
	 * Wraps a message in the cbxmcratingreview mail template.
	 *
	 * @param string $email_heading Heading text.
	 * @param string $message Email message.
	 * @param bool $plain_text Set true to send as plain text. Default to false.
	 *
	 * @return string
	 */
	public function wrap_message( $email_heading, $message, $plain_text = false ) {
		// Buffer.
		ob_start();

		do_action( 'cbxmcratingreview_email_header', $email_heading, null );
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo wpautop( wptexturize( $message ) ); // WPCS: XSS ok.

		do_action( 'cbxmcratingreview_email_footer', null );

		// Get contents.
		return ob_get_clean();
	}//end method wrap_message

public function is_user_email() {
		return $this->user_email;
	}//end method get_blogname

	/**
	 * Get blog name formatted for emails.
	 *
	 * @return string
	 */
	private function get_blogname() {
		return wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
	}//end method is_user_email
}//end class CBXMCRatingReviewEmails