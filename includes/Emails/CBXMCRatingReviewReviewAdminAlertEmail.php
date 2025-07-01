<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use CBX\MCRatingReview\Helpers\CBXMCRatingReviewHelper;

if ( ! class_exists( 'CBXMCRatingReviewReviewAdminAlertEmail', false ) ) :

	/**
	 * Class CBXMCRatingReviewEmailEmailReviewAdminAlert file
	 *
	 * Sending email alert to admin when user review an object from frontend
	 */
	class CBXMCRatingReviewReviewAdminAlertEmail extends CBXMCRatingReviewEmail {
		/**
		 * Constructor.
		 */
		public function __construct() {
			$this->id          = 'new_review_admin_alert';
			$this->user_email  = false; //alert for admin
			$this->title       = esc_html__( 'New review admin email alert', 'cbxmcratingreview' );
			$this->description = esc_html__( 'Sends notification to admin on review created by user/customer.',
				'cbxmcratingreview' );

			$this->template_html = 'emails/new_review_admin_alert.php';

			$this->placeholders = [
				'{review_score}'    => '',
				'{review_headline}' => '',
				'{review_comment}'  => '',
				'{review_status}'   => '',
				'{post_link}'       => '', // html
				'{review_edit_url}' => '' // html
			];

			// Triggers for this email.
			add_action( 'cbxmcratingreview_review_publish', [ $this, 'trigger' ], 10, 1 );

			// Call parent constructor.
			parent::__construct();

			// Other settings.
			$this->recipient = $this->get_option( 'recipient', get_option( 'admin_email' ) );
		}//end method constructor

		/**
		 * Initialise Settings Form Fields - these are generic email options most will use.
		 */
		public function init_form_fields() {
			/* translators: %s: list of placeholders */
			$placeholder_text  = sprintf( __( 'Available placeholders: %s', 'cbxmcratingreview' ),
				'<code>' . esc_html( implode( '</code>, <code>', array_keys( $this->placeholders ) ) ) . '</code>' );
			$this->form_fields = [
				'enabled'            => [
					'title'   => esc_html__( 'Enable/Disable', 'cbxmcratingreview' ),
					'type'    => 'checkbox',
					'label'   => esc_html__( 'Enable this email notification', 'cbxmcratingreview' ),
					'default' => 'yes'
				],
				'recipient'          => [
					'title'       => esc_html__( 'Recipient(s)', 'cbxmcratingreview' ),
					'type'        => 'text',
					'desc_tip'    => true,
					'description' => esc_html__( 'Email Recipient(s). Put multiple as comma.', 'cbxmcratingreview' ),
					'placeholder' => esc_html__( 'Email', 'cbxmcratingreview' ),
					'default'     => $this->get_default_recipient()
				],
				'subject'            => [
					'title'       => esc_html__( 'Subject', 'cbxmcratingreview' ),
					'type'        => 'text',
					'desc_tip'    => true,
					'description' => $placeholder_text,
					'placeholder' => esc_html__( 'Email subject here', 'cbxmcratingreview' ),
					'default'     => $this->get_default_subject()
				],
				'heading'            => [
					'title'       => esc_html__( 'Email heading', 'cbxmcratingreview' ),
					'type'        => 'text',
					'desc_tip'    => true,
					'description' => $placeholder_text,
					'placeholder' => esc_html__( 'Email heading here', 'cbxmcratingreview' ),
					'default'     => $this->get_default_heading()
				],
				'additional_content' => [
					'title'       => esc_html__( 'Additional content', 'cbxmcratingreview' ),
					'description' => esc_html__( 'Text to appear below the main email content.',
							'cbxmcratingreview' ) . ' ' . $placeholder_text,
					'css'         => 'width:400px; height: 75px;',
					'placeholder' => esc_html__( 'N/A', 'cbxmcratingreview' ),
					'type'        => 'textarea',
					'default'     => $this->get_default_additional_content(),
					'desc_tip'    => true
				],
				'email_type'         => [
					'title'       => esc_html__( 'Email type', 'cbxmcratingreview' ),
					'type'        => 'select',
					'description' => esc_html__( 'Choose which format of email to send.', 'cbxmcratingreview' ),
					'default'     => 'html',
					'class'       => 'email_type wc-enhanced-select',
					'options'     => $this->get_email_type_options(),
					'desc_tip'    => true
				],
				'from_name'          => [
					'title'       => esc_html__( 'From Name', 'cbxmcratingreview' ),
					'type'        => 'text',
					'desc_tip'    => true,
					'description' => esc_html__( 'Email sent from name. Put empty to set this from WordPress core or via any smtp plugin.',
						'cbxmcratingreview' ),
					'placeholder' => esc_html__( 'From name', 'cbxmcratingreview' ),
					'default'     => ''
				],
				'from_email'         => [
					'title'       => esc_html__( 'From Email', 'cbxmcratingreview' ),
					'type'        => 'text',
					'desc_tip'    => true,
					'description' => esc_html__( 'Email sent from name. Put empty to set this from WordPress core or via any smtp plugin.',
						'cbxmcratingreview' ),
					'placeholder' => esc_html__( 'From Email', 'cbxmcratingreview' ),
					'default'     => ''
				],
				'cc'                 => [
					'title'       => esc_html__( 'CC', 'cbxmcratingreview' ),
					'type'        => 'text',
					'desc_tip'    => true,
					'description' => esc_html__( 'Email Recipient(s) as CC. Put multiple as comma.',
						'cbxmcratingreview' ),
					'placeholder' => esc_html__( 'Email', 'cbxmcratingreview' ),
					'default'     => ''
				],
				'bcc'                => [
					'title'       => esc_html__( 'BCC', 'cbxmcratingreview' ),
					'type'        => 'text',
					'desc_tip'    => true,
					'description' => esc_html__( 'Email Recipient(s) as BCC. Put multiple as comma.',
						'cbxmcratingreview' ),
					'placeholder' => esc_html__( 'Email', 'cbxmcratingreview' ),
					'default'     => ''
				],
			];

		}//end method init_form_fields

		/**
		 * Trigger the sending of this email.
		 *
		 * @param object $new_review_info
		 */
		public function trigger( $new_review_info ) {

			if ( $this->is_enabled() && $this->get_recipient() ) {

				$exprev_status_arr   = CBXMCRatingReviewHelper::ReviewStatusOptions();
				$modification_status = ( isset( $exprev_status_arr[ $new_review_info['status'] ] ) ? $exprev_status_arr[ $new_review_info['status'] ] : '' );

				$post_url   = esc_url( get_permalink( $new_review_info['post_id'] ) );
				$post_title = esc_html( get_the_title( $new_review_info['post_id'] ) );
				/* translators: %1$s: Post Title , %2$s: Post Url  */
				$post_url_link = sprintf( wp_kses( __( '<a href="%1$s">%2$s</a>', 'cbxmcratingreview' ),
					[ 'a' => [ 'href' => [] ] ] ), $post_title, $post_url );

				$review_edit_url = esc_url( admin_url( 'admin.php?page=cbxmcratingreviewreview-list#/log/' . $new_review_info['id'] ) );
				/* translators: %s: Review edit Url   */
				$review_edit_url_link = sprintf( '<a href="%s">' . wp_kses( __( 'To edit click this url.',
						'cbxmcratingreview' ), [ 'a' => [ 'href' => [] ] ] ) . '</a>', esc_url( $review_edit_url ) );

				$this->placeholders['{review_score}']    = $new_review_info['score'];
				$this->placeholders['{review_headline}'] = $new_review_info['headline'];
				$this->placeholders['{review_comment}']  = $new_review_info['comment'];
				$this->placeholders['{review_status}']   = $modification_status;

				$this->placeholders['review_edit_url'] = $review_edit_url_link;
				$this->placeholders['{post_link}']     = $post_url_link;

				$this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(),
					$this->get_attachments() );
			}
		}//end method trigger

		/**
		 * Get email subject.
		 *
		 * @return string
		 * @since  3.1.0
		 */
		public function get_default_subject() {
			return esc_html__( 'New Review added', 'cbxmcratingreview' );
		}//end method get_default_subject

		/**
		 * Get email heading.
		 *
		 * @return string
		 * @since  3.1.0
		 */
		public function get_default_heading() {
			return esc_html__( 'New Review added', 'cbxmcratingreview' );
		}//end method get_default_heading

		/**
		 * Default content to show below main email content.
		 *
		 * @return string
		 * @since 3.7.0
		 */
		public function get_default_additional_content() {
			return '';
		}//end method get_default_additional_content

		/**
		 * Get email content.
		 *
		 * @return string
		 */
		public function get_content() {
			//$this->sending = true;

			if ( 'plain' === $this->get_email_type() ) {
				$email_content = wordwrap( preg_replace( $this->plain_search, $this->plain_replace,
					wp_strip_all_tags( $this->get_content_plain() ) ), 70 );
			} else {
				$email_content = $this->get_content_html();
			}

			return $email_content;
		}//end method get_content

		/**
		 * Get content html.
		 *
		 * @return string
		 */
		public function get_content_html() {
			return cbxmcratingreview_get_template_html( $this->template_html, [
				'email_heading'      => $this->get_heading(),
				'additional_content' => $this->get_additional_content(),
				'email'              => $this,
			] );
		}//end method get_content_html

		/**
		 * Get content plain.
		 *
		 * @return string
		 */
		public function get_content_plain() {
			$message = $this->get_content_html();

			return \Soundasleep\Html2Text::convert( $message );
		}//end method get_content_plain
	}//end class CBXMCRatingReviewReviewAdminAlertEmail
endif;

return new CBXMCRatingReviewReviewAdminAlertEmail();