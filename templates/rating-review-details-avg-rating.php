<?php
/**
 * Provides avg rating
 *
 * This file is used to markup the avg rating html
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

$ratings_stars        = isset( $avg_rating_info['rating_stat'] ) ? $avg_rating_info['rating_stat'] : [];
$criteria_stat_scores = isset( $ratings_stars['criteria_stat_scores'] ) ? $ratings_stars['criteria_stat_scores'] : [];
$criteria_info        = isset( $ratings_stars['criteria_info'] ) ? $ratings_stars['criteria_info'] : [];


do_action( 'cbxmcratingreview_details_avg_rating_before', $avg_rating_info );
?>
<?php if ( $show_short ): ?>
    <div class="cbxmcratingreview_template_avg_rating_readonly">
		<?php if ( $show_star ): ?>
            <span data-processed="0" data-score="<?php echo floatval( $avg_rating_info['avg_rating'] ); ?>"
                  class="cbxmcratingreview_readonlyrating cbxmcratingreview_readonlyrating_score cbxmcratingreview_readonlyrating_score_js"></span>
		<?php endif; ?>

		<?php if ( $show_score ): ?>
            <span class="cbxmcratingreview_readonlyrating cbxmcratingreview_readonlyrating_info"
                  itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
				<span style="display: none;" itemprop="itemReviewed" itemscope itemtype="http://schema.org/Thing">
					<span itemprop="name"><?php echo esc_html( get_the_title( $avg_rating_info['post_id'] ) ); ?></span>
				</span>

				<meta itemprop="worstRating" content="1">
				<i itemprop="ratingValue"><?php echo number_format_i18n( $avg_rating_info['avg_rating'], 2 ) . '</i>/<i itemprop="bestRating">' . number_format_i18n( 5 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?></i> (<i
                        itemprop="ratingCount"><?php echo intval( $avg_rating_info['total_count'] ); ?></i> <?php echo ( $avg_rating_info['total_count'] == 0 ) ? esc_html__( ' Review', 'cbxmcratingreview' ) : _n( 'Review', 'Reviews', $avg_rating_info['total_count'], 'cbxmcratingreview' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
                )
			</span>
		<?php endif; ?>
    </div>
<?php endif; ?>
<?php

if ( $show_chart ) : ?>
    <div class="cbxmcratingreview_template_avg_rating_readonly_chart">
		<?php

		$chart_html         = '<div class="' . apply_filters( 'cbxmcratingreview_template_avg_rating_chart_class', 'cbxmcratingreview_template_avg_rating_chart', $avg_rating_info ) . '">';
		$rating_stat_scores = $avg_rating_info['rating_stat_scores'];
		for ( $score = 5; $score > 0; $score -- ) {
			$rating_stat_score = isset( $rating_stat_scores[ $score ] ) ? $rating_stat_scores[ $score ] : [
				'count'   => 0,
				'percent' => 0
			];
			/* translators: %1$s: Review text , %2$s: Review text  */
			$chart_html .= '<p><span title="' . sprintf( esc_html__( '%1$s %%, %2$s Reviews', 'cbxmcratingreview' ), number_format_i18n( $rating_stat_score['percent'], 2 ),
					number_format_i18n( intval( $rating_stat_score['count'] ), 0 ) ) . '" style="width: ' . $rating_stat_score['percent'] . '%;" class="cbxmcratingreview_template_avg_rating_chart_graph cbxmcratingreview_template_avg_rating_chart_graph_' . $score . '"></span><i class="cbxmcratingreview_template_avg_rating_chart_percentage cbxmcratingreview_template_avg_rating_chart_percentage_' . $score . '">' . number_format_i18n( $rating_stat_score['percent'],
					2 ) . '%</i><i class="cbxmcratingreview_template_avg_rating_chart_score cbxmcratingreview_template_avg_rating_chart_score_' . $score . '">' . intval( $score ) . ' ' . esc_html__( 'Stars', 'cbxmcratingreview' ) . '</i></p>';
		}

		$chart_html .= '</div>';

		echo apply_filters( 'cbxmcratingreview_template_avg_rating_chart', $chart_html, $avg_rating_info ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		?>
    </div>

<?php endif; ?>
<?php
if ( isset( $form['custom_criteria'] ) && is_array( $form['custom_criteria'] ) && sizeof( $form['custom_criteria'] ) > 0 ) {
	$custom_criterias = isset( $form['custom_criteria'] ) ? $form['custom_criteria'] : [];

	echo '<ul class="cbxmcratingreview_review_readonly_criterias">';
	foreach ( $custom_criterias as $custom_index => $custom_criteria ) {
		//$enabled = isset( $custom_criteria['enabled'] ) ? intval( $custom_criteria['enabled'] ) : 0;
		//if ( $enabled ) {


		$criteria_id = isset( $custom_criteria['criteria_id'] ) ? intval( $custom_criteria['criteria_id'] ) : intval( $custom_index );

		/* translators: %s: Criteria ID  */
		$label = isset( $custom_criteria['label'] ) ? esc_attr( $custom_criteria['label'] ) : sprintf( esc_html__( 'Criteria %d', 'cbxmcratingreview' ), $criteria_index );

		$stars_formatted = is_array( $custom_criteria['stars_formatted'] ) ? $custom_criteria['stars_formatted'] : [];

		$stars_length = isset( $stars_formatted['length'] ) ? intval( $stars_formatted['length'] ) : 0;
		$stars_hints  = isset( $stars_formatted['stars'] ) ? $stars_formatted['stars'] : [];


		$rating             = isset( $criteria_info[ $criteria_id ] ) ? $criteria_info[ $criteria_id ] : [];
		$rating_score       = isset( $rating['avg_rating'] ) ? $rating['avg_rating'] : 0; //score in 5 based but we need to convert to star length of current
		$rating_total_count = isset( $rating['total_count'] ) ? $rating['total_count'] : 0;

		if ( $stars_length != 5 ) {
			$rating_score = ( $stars_length != 0 ) ? ( $rating_score * $stars_length ) / 5 : 0;
		}

		echo '<li class="cbxmcratingreview_review_readonly_criteria cbxmcratingreview_review_details_criteria" data-criteria_id="' . esc_attr( $criteria_id ) . '">';
		if ( $show_star || $show_score ):
			echo '<p>' . esc_attr( $label ) . '</p>';
		endif;

		if ( $show_star ):
			echo '<span data-processed="0" class="cbxmcratingreview_detailsrating_score cbxmcratingreview_readonlyrating_score_js " data-score="' . esc_attr( $rating_score ) . '"  data-hints=\'' . wp_json_encode( array_values( $stars_hints ) ) . '\'></span>';
		endif;

		if ( $show_score ):
			echo '<span class="cbxmcratingreview_detailsrating_text">' . number_format_i18n( $rating_score, 2 ) . '' . number_format_i18n( $stars_length ) . ' (' . number_format_i18n( intval( $rating_total_count ) ) . ' ' . ( ( $rating_total_count == 0 ) ? esc_html__( ' Review', 'cbxmcratingreview' ) : _n( 'Review', 'Reviews', $rating_total_count, 'cbxmcratingreview' ) ) . ')</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		endif;
		//}
		echo '</li>';
	}
	echo '</ul>';
}
?>

<?php
do_action( 'cbxmcratingreview_details_avg_rating_after', $avg_rating_info );