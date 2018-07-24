<?php
/**
 * Social share functions
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.6
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Returns social sharing sites
 *
 * @since 2.0.0
 */
function wpex_social_share_sites() {
    $sites = wpex_get_mod( 'social_share_sites', array( 'twitter', 'facebook', 'google_plus', 'linkedin', 'email' ) );
    $sites = apply_filters( 'wpex_social_share_sites', $sites );
    if ( $sites && ! is_array( $sites ) ) {
        $sites = explode( ',', $sites );
    }
    return $sites;
}

/**
 * Get social links array
 *
 * @since 4.5.5
 */
function wpex_get_social_items() {
    return apply_filters( 'wpex_get_social_items', array(
        'twitter' => array(
            'li_class'   => 'wpex-twitter',
            'icon_class' => 'fa fa-twitter',
            'label'      => __( 'Tweet', 'total' ),
            'site'       => 'Twitter',
        ),
        'facebook' => array(
            'li_class'   => 'wpex-facebook',
            'icon_class' => 'fa fa-facebook',
            'label'      => __( 'Share', 'total' ),
            'site'       => 'Facebook',
        ),
        'google_plus' => array(
            'li_class'   => 'wpex-googleplus',
            'icon_class' => 'fa fa-google-plus',
            'label'      => __( 'Plus one', 'total' ),
            'site'       => 'Google Plus',
        ),
        'pinterest' => array(
            'li_class'   => 'wpex-pinterest',
            'icon_class' => 'fa fa-pinterest',
            'label'      => __( 'Pin It', 'total' ),
            'site'       => 'Pinterest',
        ),
        'linkedin' => array(
            'li_class'   => 'wpex-linkedin',
            'icon_class' => 'fa fa-linkedin',
            'label'      => __( 'Share', 'total' ),
            'site'       => 'LinkedIn',
        ),
        'email' => array(
            'li_class'   => 'wpex-email',
            'icon_class' => 'fa fa-envelope',
            'label'      => __( 'Email', 'total' ),
            'site'       => 'Email',
        ),
    ) );
}

/**
 * Returns correct social share position
 *
 * @since 2.0.0
 */
function wpex_social_share_position() {
    $position = wpex_get_mod( 'social_share_position' );
    $position = $position ? $position : 'horizontal';
    return apply_filters( 'wpex_social_share_position', $position );
}

/**
 * Returns correct social share style
 *
 * @since 2.0.0
 */
function wpex_social_share_style() {
    $style = wpex_get_mod( 'social_share_style' );
    $style = $style ? $style : 'flat';
    return apply_filters( 'wpex_social_share_style', $style );
}

/**
 * Returns the social share heading
 *
 * @since 2.0.0
 */
function wpex_social_share_heading() {
    return apply_filters( 'wpex_social_share_heading', wpex_get_translated_theme_mod( 'social_share_heading', __( 'Share This', 'total' ) ) );
}


/**
 * Return social share data
 *
 * @since 4.5.5.1
 */
function wpex_get_social_share_data( $post_id = '', $sites = '' ) {

    $post_id = $post_id ? $post_id : wpex_get_current_post_id();
    $sites   = $sites ? $sites : wpex_social_share_sites();
    $url     = apply_filters( 'wpex_social_share_url', wpex_get_current_url() );

    $data = array();

    // Singular data
    if ( $post_id ) {

        $title = wpex_get_esc_title();

        if ( in_array( 'pinterest', $sites ) || in_array( 'linkedin', $sites ) ) {
            
            $summary = wpex_get_excerpt( array(
                'post_id'         => $post_id,
                'length'          => 30,
                'echo'            => false,
                'ignore_more_tag' => true,
                'more'            => '',
                'context'         => 'social_share',
            ) );

        }

    }

    // Most likely an archive
    else {
        $title   = get_the_archive_title();
        $summary = get_the_archive_description();
    }

    // Share source
    $source = apply_filters( 'wpex_social_share_data_source', home_url( '/' ) );
    $data['source'] = rawurlencode( esc_url( $source ) );

    // Share url
    $url = apply_filters( 'wpex_social_share_data_url', $url );
    $data['url'] = rawurlencode( esc_url( $url ) );

    // Share title
    $title = apply_filters( 'wpex_social_share_data_title', $title );
    $data['title'] = html_entity_decode( wp_strip_all_tags( $title ) );

    // Thumbnail
    if ( is_singular() && has_post_thumbnail() ) {
        $image = apply_filters( 'wpex_social_share_data_image', wp_get_attachment_url( get_post_thumbnail_id( $post_id ) ) );
        $data['image'] = rawurlencode( esc_url( $image ) );
    }

    // Add twitter handle
    if ( $handle = wpex_get_mod( 'social_share_twitter_handle' ) ) {
        $data['twitter-handle'] = esc_attr( $handle );
    }

    // Share summary
    if ( ! empty( $summary ) ) {
        $summary = apply_filters( 'wpex_social_share_data_summary', wp_strip_all_tags( strip_shortcodes( $summary ) ) );
        $data['summary'] = rawurlencode( html_entity_decode( $summary ) );
    }

    // Get WordPress SEO meta share values
    if ( defined( 'WPSEO_VERSION' ) ) {
        if ( $twitter_title = get_post_meta( $post_id, '_yoast_wpseo_twitter-title', true ) ) {
            $data['twitter-title'] = html_entity_decode( wp_strip_all_tags( $twitter_title ) );
        }
        if ( $twitter_desc = get_post_meta( $post_id, '_yoast_wpseo_twitter-description', true ) ) {
            if ( $twitter_title ) {
                $data['twitter-title'] = html_entity_decode( wp_strip_all_tags( $twitter_title . ': ' . $twitter_desc ) );
            } else { 
                $data['twitter-title'] = $data['title'] . ':' . html_entity_decode( wp_strip_all_tags( $twitter_desc ) );
            }
        }
    }

    // Email data
    if ( in_array( 'email', $sites ) ) {
        $data['email-subject'] = apply_filters( 'wpex_social_share_data_email_subject', esc_html__( 'I wanted you to see this link', 'total' ) );
        $body = esc_html__( 'I wanted you to see this link', 'total' ) . ' '. rawurlencode( esc_url( $url ) );
        $data['email-body'] = apply_filters( 'wpex_social_share_data_email_body', $body );
    }

    // Specs
    $data['specs'] = 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600';

    return apply_filters( 'wpex_get_social_share_data', $data );
    
}