<?php
//namespace CBX\MCRatingReview\Widgets\Classic;

use CBX\MCRatingReview\CBXMCRatingReviewSettings;
use CBX\MCRatingReview\Helpers\CBXMCRatingReviewHelper;


// Prevent direct file access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Most rated posts widgets
 *
 * Class CBXMCRatingReviewMRPostsWidget
 */
class CBXMCRatingReviewMRPostsWidget extends WP_Widget {

	/**
	 *
	 * Unique identifier for your widget.
	 *
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * widget file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $widget_slug = 'cbxmcratingreviewmrposts-widget'; //main parent plugin's language file


	/**
	 * Specifies the classname and description, instantiates the widget,
	 * loads localization files, and includes necessary stylesheets and JavaScript.
	 */
	public function __construct() {

		parent::__construct(
			$this->get_widget_slug(),
			esc_html__( 'CBXMCRR: Most Rated Posts', 'cbxmcratingreview' ),
			[
				'classname'   => 'widget-cbxmcratingreviewmrposts',
				'description' => esc_html__( 'Most rated posts as per different filter widget', 'cbxmcratingreview' )
			]
		);

	} // end constructor

	/**
	 * Return the widget slug.
	 *
	 * @return    Plugin slug variable.
	 * @since    1.0.0
	 *
	 */
	public function get_widget_slug() {
		return $this->widget_slug;
	}//end method get_widget_slug

	/*--------------------------------------------------*/
	/* Widget API Functions
	/*--------------------------------------------------*/

	/**
	 * Outputs the content of the widget.
	 *
	 * @param array args  The array of form elements
	 * @param array instance The current instance of the widget
	 */
	public function widget( $args, $instance ) {
		if ( ! isset ( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		extract( $args, EXTR_SKIP );

		$widget_string = $before_widget;

		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? esc_html__( 'Most Rated Posts', 'cbxmcratingreview' ) : $instance['title'], $instance, $this->id_base );
		// Defining the Widget Title
		if ( $title ) {
			$widget_string .= $args['before_title'] . $title . $args['after_title'];
		} else {
			$widget_string .= $args['before_title'] . $args['after_title'];
		}


		$instance = apply_filters( 'cbxmcratingreviewmrposts_widget_instance_display', $instance );

		$arr            = [];
		$arr['scope']   = 'widget';
		$arr['form_id'] = isset( $instance['form_id'] ) ? absint( $instance['form_id'] ) : 0;
		$arr['limit']   = isset( $instance['limit'] ) ? absint( $instance['limit'] ) : 10;
		$arr['order']   = isset( $instance['order'] ) ? sanitize_text_field( wp_unslash( $instance['order'] ) ) : 'DESC';
		$arr['orderby'] = isset( $instance['orderby'] ) ? sanitize_text_field( wp_unslash( $instance['orderby'] ) ) : 'avg_rating'; //avg_rating, total_count, post_id
		$arr['type']    = isset( $instance['type'] ) ? sanitize_text_field( wp_unslash( $instance['type'] ) ) : 'post';
		$arr['title']   = isset( $instance['title'] ) ? sanitize_text_field( wp_unslash( $instance['title'] ) ) : '';


		$attr_html = '';
		foreach ( $arr as $key => $value ) {
			$attr_html .= ' ' . $key . '="' . esc_attr( $value ) . '" ';
		}

		$content_html = do_shortcode( '[cbxmcratingreviewmrposts ' . $attr_html . ']' );

		$widget_string .= $content_html;
		$widget_string .= $after_widget;

		echo $widget_string; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}//end widget


	/**
	 * Processes the widget's options to be saved.
	 *
	 * @param array new_instance The new instance of values to be generated via the update.
	 * @param array old_instance The previous instance of values before the update.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title']   = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( wp_unslash( $new_instance['title'] ) ) : '';
		$instance['form_id'] = absint( $new_instance['form_id'] );
		$instance['limit']   = absint( $new_instance['limit'] );
		$instance['order']   = sanitize_text_field( wp_unslash( $new_instance['order'] ) );
		$instance['orderby'] = sanitize_text_field( wp_unslash( $new_instance['orderby'] ) );
		$instance['type']    = sanitize_text_field( wp_unslash( $new_instance['type'] ) );

		return apply_filters( 'cbxmcratingreviewmrposts_widget_instance_update', $instance, $new_instance );
	}//end method update

	/**
	 * Generates the administration form for the widget.
	 *
	 * @param array instance The array of keys and values for the widget.
	 */
	public function form( $instance ) {
		$fields = [
			'title'   => esc_html__( 'Most Rated Posts', 'cbxmcratingreview' ),
			'form_id' => 0,
			'limit'   => 10,
			'order'   => 'DESC',       //DESC, ASC
			'orderby' => 'avg_rating', //avg_rating, total_count, post_id
			'type'    => 'post'
		];

		$instance = wp_parse_args(
			(array) $instance,
			$fields
		);


		$instance = apply_filters( 'cbxmcratingreviewmrposts_widget_instance_form', $instance );

		extract( $instance, EXTR_SKIP );

		$forms = CBXMCRatingReviewHelper::getRatingForms();
		?>
        <!-- Custom  Title Field -->
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'cbxmcratingreview' ); ?></label>

            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text"
                   value="<?php echo esc_attr( $title ); ?>"/>
        </p>

        <!-- form listing -->
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'form_id' ) ); ?>"><?php esc_html_e( "Select Form", "cbxmcratingreview" ) ?>

                <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'form_id' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'form_id' ) ); ?>">
					<?php
					foreach ( $forms as $form ) {
						$form_id_  = intval( $form['id'] ) ? intval( $form['id'] ) : 0;
						$form_name = isset( $form['name'] ) ? esc_attr( $form['name'] ) : esc_html__( 'Untitled Form', 'cbxmcratingreview' );
						// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						echo '<option value="' . $form_id_ . '" ' . ( ( $form_id_ == $form_id ) ? ' selected="selected" ' : '' ) . '>' . $form_name . '</option>';
					}
					?>
                </select>
            </label>
        </p>

        <!-- Display Limit -->
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>">
				<?php esc_html_e( 'Display Limit:', "cbxmcratingreview" ); ?>
            </label>

            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'limit' ) ); ?>" type="text"
                   value="<?php echo esc_attr( $limit ); ?>"/>
        </p>

        <!-- Order by Selection -->
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>"><?php esc_html_e( "Order By", "cbxmcratingreview" ) ?>
                <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'orderby' ) ); ?>">
                    <option value="post_id" <?php echo ( $orderby == "post_id" ) ? 'selected="selected"' : ''; ?>> <?php esc_html_e( "Post ID", "cbxmcratingreview" ) ?>     </option>
                    <option value="avg_rating" <?php echo ( $orderby == "avg_rating" ) ? 'selected="selected"' : ''; ?>> <?php esc_html_e( "Avg Rating", "cbxmcratingreview" ) ?> </option>
                    <option value="total_count" <?php echo ( $orderby == "total_count" ) ? 'selected="selected"' : ''; ?>> <?php esc_html_e( "Rating Count", "cbxmcratingreview" ) ?> </option>

                </select>
            </label>
        </p>

        <!-- Selection of Ascending or Descending -->
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>"><?php esc_html_e( "Order", "cbxmcratingreview" ) ?>

                <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'order' ) ); ?>">
                    <option
                            value="asc" <?php echo ( $order == "asc" ) ? 'selected="selected"' : ''; ?>> <?php esc_html_e( "Ascending", "cbxmcratingreview" ) ?> </option>
                    <option
                            value="desc" <?php echo ( $order == "desc" ) ? 'selected="selected"' : ''; ?>> <?php esc_html_e( "Descending", "cbxmcratingreview" ) ?> </option>
                </select>
            </label>
        </p>
		<?php
		$all_post_types = CBXMCRatingReviewHelper::post_types( true );
		?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>"><?php esc_html_e( "Post Type", "cbxmcratingreview" ) ?>

                <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'type' ) ); ?>">
					<?php
					foreach ( $all_post_types as $post_key => $post_name ) {
						echo '<option value="' . esc_attr( $post_key ) . '" ' . ( ( $post_key == $type ) ? ' selected="selected" ' : '' ) . '>' . esc_attr( $post_name ) . '</option>';
					}
					?>
                </select>
            </label>
        </p>
		<?php
		do_action( 'cbxmcratingreviewmrposts_widget_form_admin', $instance, $this );
		?>
        <input type="hidden" id="<?php echo esc_attr( $this->get_field_id( 'submit' ) ); ?>"
               name="<?php echo esc_attr( $this->get_field_name( 'submit' ) ); ?>" value="1"/>
		<?php
	}//end method form
}//end class CBXMCRatingReviewMRPostsWidget