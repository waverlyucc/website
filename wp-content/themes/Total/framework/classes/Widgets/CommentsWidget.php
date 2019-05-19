<?php
/**
 * Comments widget
 *
 * @package Total WordPress Theme
 * @subpackage Widgets
 * @version 4.8
 */

namespace TotalTheme;

// Prevent direct file access
if ( ! defined ( 'ABSPATH' ) ) {
	exit;
}

// Start class
class CommentsWidget extends WidgetBuilder {
	private $args;

	/**
	 * Register widget with WordPress.
	 *
	 * @since 1.0
	 */
	public function __construct() {

		$this->args = array(
			'id_base' => 'wpex_recent_comments_avatars_widget',
			'name'    => $this->branding() . __( 'Comments With Avatars', 'total' ),
			'options' => array(
				'customize_selective_refresh' => true,
			),
			'fields'  => array(
				array(
					'id'    => 'title',
					'label' => __( 'Title', 'total' ),
					'type'  => 'text',
				),
				array(
					'id'      => 'number',
					'label'   => __( 'Number', 'total' ),
					'type'    => 'number',
					'default' => 3,
				),
			),
		);

		$this->create_widget( $this->args );

	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 * @since 1.0
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {

		// Parse and extract widget settings
		extract( $this->parse_instance( $instance ) );

		// Before widget hook
		echo wp_kses_post( $args['before_widget'] );

		// Display widget title
		$this->widget_title( $args, $instance );

		// Define widget output
		$output = '';

		$output .= '<ul class="wpex-recent-comments-widget clr">';

		// Query Comments
		$comments = get_comments( array (
			'number'      => $number,
			'status'      => 'approve',
			'post_status' => 'publish',
			'type'        => 'comment',
		) );

		if ( $comments ) {

			// Loop through comments
			foreach ( $comments as $comment ) {

				// Get comment ID
				$comment_id   = $comment->comment_ID;
				$comment_link = get_permalink( $comment->comment_post_ID ) . '#comment-' . $comment_id;

				$output .= '<li class="wpex-clr">';

					$output .= '<a href="' . esc_url( $comment_link ) .'" title="' . esc_html__( 'view comment', 'total' ) . '" class="avatar">';

						$output .= get_avatar( $comment->comment_author_email, 50 );

					$output .= '</a>';

					$output .= '<strong>' . get_comment_author( $comment_id ) . ':</strong>';

					$output .= wp_trim_words( $comment->comment_content, '10', '&hellip;' );

					$output .= '<br />';

					$output .= '<a href="' . esc_url( $comment_link ) . '" class="view-comment">' . esc_html__( 'view comment', 'total' ) . ' &rarr;</a>';

				$output .= '</li>';

			}

		// Display no comments notice
		} else {

			$output .= '<li>' . esc_html__( 'No comments yet.', 'total' ) . '</li>';

		}

		$output .= '</ul>';

		// Echo output
		echo $output;

		// After widget hook
		echo wp_kses_post( $args['after_widget'] );

	}

}
register_widget( 'TotalTheme\CommentsWidget' );