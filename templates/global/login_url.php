<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

//echo '<div class="cbx-chota"><div class="container"><div class="row"><div class="col-12">';
/* translators: %1$s: Login Link */
echo '<div class="guest_login_url_wrap"><p class="mb-0">' . wp_kses( sprintf( __( 'Please <a href="%1$s">login</a> to access.', 'cbxmcratingreview' ), esc_url( cbxmcratingreview_login_url_with_redirect() ) ), [
		'a' => [
			'href' => [],
			'class' => []
		]
	] ) . '</p></div>';
//echo '</div></div></div></div>';