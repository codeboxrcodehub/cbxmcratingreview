<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use CBX\MCRatingReview\Helpers\CBXMCRatingReviewHelper;

/**
 * Provides frontend user dashboard
 *
 * This file is used to markup the frontend user dashboard html
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

do_action( 'cbxmcratingreview_user_dashboard_before' );
?>
    <div class="cbx-chota cbxmcratingreview-frontend-manager-wrapper" id="cbxmcratingreview-review-public">
    </div>
<?php
do_action( 'cbxmcratingreview_user_dashboard_after' );
