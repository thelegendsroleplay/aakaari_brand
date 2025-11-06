<?php
/**
 * The template for displaying comments
 *
 * @package Aakaari_Brand
 */

if ( post_password_required() ) {
    return;
}
?>

<div id="comments" class="comments-area">

    <?php if ( have_comments() ) : ?>
        <h2 class="comments-title">
            <?php
            $comment_count = get_comments_number();
            if ( '1' === $comment_count ) {
                printf(
                    esc_html__( 'One comment on &ldquo;%s&rdquo;', 'aakaari-brand' ),
                    '<span>' . get_the_title() . '</span>'
                );
            } else {
                printf(
                    esc_html( _nx( '%1$s comment on &ldquo;%2$s&rdquo;', '%1$s comments on &ldquo;%2$s&rdquo;', $comment_count, 'comments title', 'aakaari-brand' ) ),
                    number_format_i18n( $comment_count ),
                    '<span>' . get_the_title() . '</span>'
                );
            }
            ?>
        </h2>

        <ol class="comment-list">
            <?php
            wp_list_comments( array(
                'style'       => 'ol',
                'short_ping'  => true,
                'avatar_size' => 50,
            ) );
            ?>
        </ol>

        <?php
        the_comments_navigation( array(
            'prev_text' => __( '&laquo; Older Comments', 'aakaari-brand' ),
            'next_text' => __( 'Newer Comments &raquo;', 'aakaari-brand' ),
        ) );

    endif; // Check for have_comments().

    // If comments are closed and there are comments, let's leave a little note.
    if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
        ?>
        <p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'aakaari-brand' ); ?></p>
        <?php
    endif;

    // Comment form
    comment_form( array(
        'title_reply_before' => '<h2 id="reply-title" class="comment-reply-title">',
        'title_reply_after'  => '</h2>',
        'class_submit'       => 'button',
        'submit_button'      => '<button name="%1$s" type="submit" id="%2$s" class="%3$s">%4$s</button>',
    ) );
    ?>

</div>
