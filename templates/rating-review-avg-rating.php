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
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<?php do_action( 'cbxmcratingreview_avg_rating_before', $avg_rating_info ); ?>
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
				<i itemprop="ratingValue"><?php echo number_format_i18n( $avg_rating_info['avg_rating'], 2 ) . '</i>/<i itemprop="bestRating">' . number_format_i18n( 5 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></i> (<i
                        itemprop="ratingCount"><?php echo intval( $avg_rating_info['total_count'] ); ?></i> <?php echo ( $avg_rating_info['total_count'] == 0 ) ? esc_html__( ' Review', 'cbxmcratingreview' ) : _n( 'Review', 'Reviews', $avg_rating_info['total_count'], 'cbxmcratingreview' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                )
	    	</span>
		<?php endif; ?>
    </div>
<?php if ( $show_chart ): ?>
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

<?php do_action( 'cbxmcratingreview_avg_rating_after', $avg_rating_info );