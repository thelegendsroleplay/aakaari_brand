<?php
/**
 * The template for displaying search forms
 *
 * @package Aakaari_Brand
 */
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <label>
        <span class="screen-reader-text"><?php echo esc_html_x( 'Search for:', 'label', 'aakaari-brand' ); ?></span>
        <input type="search"
               class="search-field"
               placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', 'aakaari-brand' ); ?>"
               value="<?php echo get_search_query(); ?>"
               name="s"
               title="<?php echo esc_attr_x( 'Search for:', 'label', 'aakaari-brand' ); ?>" />
    </label>
    <button type="submit" class="search-submit">
        <?php echo esc_html_x( 'Search', 'submit button', 'aakaari-brand' ); ?>
    </button>
</form>
