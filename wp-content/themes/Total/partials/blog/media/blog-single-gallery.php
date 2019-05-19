<?php
/**
 * Blog single post gallery format media
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 4.8.5
 *
 * @todo update to use new wpex_get_post_media_gallery_slider() function
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get attachments
$attachments = wpex_get_gallery_ids( get_the_ID() );

// Return standard entry style if password protected or there aren't any attachments
if ( post_password_required() || empty( $attachments ) ) {
	get_template_part( 'partials/blog/media/blog-entry' );
	return;
}

// Slider data (used to check for thumbnails)
$slider_data = wpex_get_post_slider_settings( array(
	'filter_tag' => 'wpex_blog_slider_data_atrributes',
) );

// Check if lightbox is enabled
if ( wpex_gallery_is_lightbox_enabled() || wpex_get_mod( 'blog_post_image_lightbox' ) ) {
	$lightbox_enabled = true;
	$lightbox_title   = apply_filters( 'wpex_blog_gallery_lightbox_title', false );
	wpex_enqueue_ilightbox_skin(); // Load lightbox skin stylesheet
} else {
	$lightbox_enabled = $lightbox_title = false;
} ?>

<div id="post-media" class="clr">

	<div class="gallery-format-post-slider">

		<div class="wpex-slider-preloaderimg">
			<?php
			// Display first image as a placeholder while the others load
			wpex_blog_post_thumbnail( array(
				'attachment'    => $attachments[0],
				'alt'           => get_post_meta( $attachments[0], '_wp_attachment_image_alt', true ),
			) ); ?>
		</div><!-- .wpex-slider-preloaderimg -->

		<div class="wpex-slider slider-pro" <?php wpex_slider_data( $slider_data ); ?>>

			<?php $slides_attrs = array(
				'class' => 'wpex-slider-slides sp-slides',
			);
			if ( $lightbox_enabled ) {
				$slides_attrs['class'] .= ' wpex-lightbox-group';
			}
			if ( ! $lightbox_title ) {
				$slides_attrs['data-show_title'] = 'false';
			} ?>

			<div <?php echo wpex_parse_attrs( $slides_attrs ); ?>>

				<?php
				// Loop through attachments
				foreach ( $attachments as $attachment ) : ?>

					<?php
					// Get attachment data
					$attachment_data  = wpex_get_attachment_data( $attachment );
					$attachment_alt   = $attachment_data['alt'];
					$attachment_video = $attachment_data['video'];

					// Generate video
					if ( $attachment_video ) {
						$attachment_video = wpex_video_oembed( $attachment_video, 'sp-video', array(
							'youtube' => array(
								'enablejsapi' => '1',
							)
						) );
					}

					// Get image output
					$attachment_html = wpex_get_blog_post_thumbnail( array(
						'attachment'    => $attachment,
						'alt'           => $attachment_alt,
					) ); ?>

					<div class="wpex-slider-slide sp-slide">

						<?php
						// Display attachment video
						if ( $attachment_video ) : ?>

							<div class="wpex-slider-video responsive-video-wrap"><?php echo wpex_add_sp_video_to_oembed( $attachment_video ); ?></div>

						<?php
						// Display attachment image
						else : ?>

							<div class="wpex-slider-media clr">

								<?php
								// Display with lightbox
								if ( $lightbox_enabled ) :

									$lightbox_link_attrs = array(
										'href'      => esc_url( wpex_get_lightbox_image( $attachment ) ),
										'title'     => esc_attr( $attachment_alt ),
										'data-type' => 'image',
										'class'     => 'wpex-lightbox-group-item',
									);

									if ( $lightbox_title ) {
										$lightbox_link_attrs['data-title'] = esc_attr( $attachment_alt );
									}

									if ( $animation = wpex_entry_image_animation_classes() ) {
										$lightbox_link_attrs['class'] = ' ' . trim( $animation );
									}

									$thumbnail = wpex_get_blog_entry_thumbnail( array(
										'attachment' => $attachment,
										'alt'        => $attachment_alt,
									) );

									// Display image with lightbox link
									echo wpex_parse_html( 'a', $lightbox_link_attrs, $thumbnail );

								// Display single image
								else : ?>

									<?php echo $attachment_html; ?>

								<?php endif; ?>

								<?php if ( ! empty( $attachment_data['caption'] ) ) : ?>
									<div class="wpex-slider-caption sp-layer sp-black sp-padding clr" data-position="bottomCenter" data-show-transition="up" data-hide-transition="down" data-width="100%" data-show-delay="500">
											<?php echo wp_kses_post( $attachment_data['caption'] ); ?>
										</div><!-- .wpex-slider-caption -->
								<?php endif; ?>

							</div><!-- .wpex-slider-media -->

						<?php endif; ?>

					</div><!-- .wpex-slider-slide sp-slide -->

				<?php endforeach; ?>

			</div><!-- .wpex-slider-slides .sp-slides -->

			<?php if ( isset( $slider_data['thumbnails'] ) && 'true' == $slider_data['thumbnails'] ) : ?>

				<div class="wpex-slider-thumbnails sp-thumbnails">

					<?php
					// Loop through attachments
					foreach ( $attachments as $attachment ) : ?>

						<?php
						// Display image thumbnail
						wpex_blog_entry_thumbnail( array(
							'attachment'    => $attachment,
							'class'         => 'wpex-slider-thumbnail sp-thumbnail',
							'alt'           => get_post_meta( $attachments, '_wp_attachment_image_alt', true ),
						) ); ?>

					<?php endforeach; ?>

				</div><!-- .wpex-slider-thumbnails -->

			<?php endif; ?>

		</div><!-- .wpex-slider .slider-pro -->

	</div><!-- .gallery-format-post-slider -->

</div><!-- .blog-entry-media -->