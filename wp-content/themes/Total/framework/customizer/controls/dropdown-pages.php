<?php
/**
 * Customizer Pages Select Control
 *
 * @package Total WordPress Theme
 * @subpackage Customizer
 * @version 4.5.1
 */

if ( ! class_exists( 'WPEX_Customizer_Dropdown_Pages' ) ) {

	class WPEX_Customizer_Dropdown_Pages extends WP_Customize_Control {

		public $type = 'wpex-dropdown-pages';
		public $include_templates = false;

		/**
		 * Render the content
		 *
		 * @access public
		 */
		public function render_content() { ?>

			<label class="customize-control-select">

			<?php if ( ! empty( $this->label ) ) { ?>

				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>

			<?php } ?>

			<?php
			// Description
			if ( ! empty( $this->description ) ) { ?>

				<span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
			
			<?php } ?>

			<div class="wpex-customizer-chosen-select">

				<?php if ( $this->include_templates && post_type_exists( 'templatera' ) ) { ?>

					<select <?php echo $this->get_link(); ?>>
						
						<option value="">- <?php esc_html_e( 'Select', 'total' ); ?> -</option>

						<?php
						// Add Templatera templates
						$templatera_templates = new WP_Query( array(
							'posts_per_page' => -1,
							'post_type'      => 'templatera',
						) );
						if ( $templatera_templates->have_posts() ) { ?>

							<optgroup label="<?php esc_html_e( 'Templatera', 'total' ); ?>">
								
								<?php while ( $templatera_templates->have_posts() ) {

									$templatera_templates->the_post();

									echo '<option value="'. get_the_ID() .'"'. selected( $this->value(), get_the_ID(), false ) .'>'. get_the_title() .'</option>';

								}
								wp_reset_postdata(); ?>
								
							</optgroup>

						<?php } ?>
						
						<optgroup label="<?php esc_html_e( 'Pages', 'total' ); ?>">
							<?php
							$pages = get_pages( array(
								'exclude' => get_option( 'page_on_front' ),
							) );
							if ( $pages ) {
								foreach ( $pages as $page ) {
									echo '<option value="'. $page->ID .'"'. selected( $this->value(), $page->ID, false ) .'>'. $page->post_title .'</option>';
								}
							} ?>
						</optgroup>

					</select>

				<?php } else {

					$dropdown = wp_dropdown_pages( array(
						'name'              => '_customize-dropdown-pages-' . $this->id,
						'echo'              => 0,
						'show_option_none'  => '&mdash; '. esc_html__( 'Select', 'total' ) .' &mdash;',
						'option_none_value' => '0',
						'selected'          => $this->value(),
					) );

					// Hackily add in the data link parameter.
					echo str_replace( '<select', '<select ' . $this->get_link(), $dropdown );

				} ?>

			</div>

		<?php }
	}

}