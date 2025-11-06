<?php
/**
 * Custom template tags for this theme
 *
 * @package Aakaari_Brand
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Display post meta information
 */
if ( ! function_exists( 'aakaari_brand_posted_on' ) ) :
    function aakaari_brand_posted_on() {
        $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
        if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
            $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
        }

        $time_string = sprintf(
            $time_string,
            esc_attr( get_the_date( DATE_W3C ) ),
            esc_html( get_the_date() ),
            esc_attr( get_the_modified_date( DATE_W3C ) ),
            esc_html( get_the_modified_date() )
        );

        $posted_on = sprintf(
            esc_html_x( 'Posted on %s', 'post date', 'aakaari-brand' ),
            '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
        );

        echo '<span class="posted-on">' . $posted_on . '</span>';
    }
endif;

/**
 * Display post author information
 */
if ( ! function_exists( 'aakaari_brand_posted_by' ) ) :
    function aakaari_brand_posted_by() {
        $byline = sprintf(
            esc_html_x( 'by %s', 'post author', 'aakaari-brand' ),
            '<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
        );

        echo '<span class="byline"> ' . $byline . '</span>';
    }
endif;

/**
 * Display category list
 */
if ( ! function_exists( 'aakaari_brand_entry_categories' ) ) :
    function aakaari_brand_entry_categories() {
        if ( 'post' === get_post_type() ) {
            $categories_list = get_the_category_list( esc_html__( ', ', 'aakaari-brand' ) );
            if ( $categories_list ) {
                printf(
                    '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'aakaari-brand' ) . '</span>',
                    $categories_list
                );
            }
        }
    }
endif;

/**
 * Display tag list
 */
if ( ! function_exists( 'aakaari_brand_entry_tags' ) ) :
    function aakaari_brand_entry_tags() {
        if ( 'post' === get_post_type() ) {
            $tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'aakaari-brand' ) );
            if ( $tags_list ) {
                printf(
                    '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'aakaari-brand' ) . '</span>',
                    $tags_list
                );
            }
        }
    }
endif;

/**
 * Display comment count
 */
if ( ! function_exists( 'aakaari_brand_comment_count' ) ) :
    function aakaari_brand_comment_count() {
        if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
            echo '<span class="comments-link">';
            comments_popup_link(
                sprintf(
                    wp_kses(
                        __( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'aakaari-brand' ),
                        array(
                            'span' => array(
                                'class' => array(),
                            ),
                        )
                    ),
                    get_the_title()
                )
            );
            echo '</span>';
        }
    }
endif;

/**
 * Display post thumbnail with link
 */
if ( ! function_exists( 'aakaari_brand_post_thumbnail' ) ) :
    function aakaari_brand_post_thumbnail( $size = 'post-thumbnail' ) {
        if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
            return;
        }

        if ( is_singular() ) :
            ?>
            <div class="post-thumbnail">
                <?php the_post_thumbnail( $size ); ?>
            </div>
            <?php
        else :
            ?>
            <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                <?php
                the_post_thumbnail(
                    $size,
                    array(
                        'alt' => the_title_attribute(
                            array(
                                'echo' => false,
                            )
                        ),
                    )
                );
                ?>
            </a>
            <?php
        endif;
    }
endif;

/**
 * Custom pagination
 */
if ( ! function_exists( 'aakaari_brand_pagination' ) ) :
    function aakaari_brand_pagination() {
        the_posts_pagination(
            array(
                'mid_size'  => 2,
                'prev_text' => sprintf(
                    '<span class="nav-prev-text">%s</span>',
                    esc_html__( '&laquo; Newer posts', 'aakaari-brand' )
                ),
                'next_text' => sprintf(
                    '<span class="nav-next-text">%s</span>',
                    esc_html__( 'Older posts &raquo;', 'aakaari-brand' )
                ),
            )
        );
    }
endif;

/**
 * Display breadcrumbs
 */
if ( ! function_exists( 'aakaari_brand_breadcrumbs' ) ) :
    function aakaari_brand_breadcrumbs() {
        if ( is_front_page() ) {
            return;
        }

        echo '<nav class="breadcrumbs">';
        echo '<a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'aakaari-brand' ) . '</a>';
        echo ' &raquo; ';

        if ( is_category() || is_single() ) {
            the_category( ' &bull; ' );
            if ( is_single() ) {
                echo ' &raquo; ';
                the_title();
            }
        } elseif ( is_page() ) {
            echo the_title();
        } elseif ( is_search() ) {
            echo esc_html__( 'Search Results for', 'aakaari-brand' ) . ' "' . get_search_query() . '"';
        } elseif ( is_404() ) {
            echo esc_html__( '404 Not Found', 'aakaari-brand' );
        } elseif ( is_archive() ) {
            the_archive_title();
        }

        echo '</nav>';
    }
endif;
