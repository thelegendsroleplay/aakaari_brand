<?php
/**
 * WooCommerce Color Attributes
 *
 * Adds color picker functionality to WooCommerce product attributes
 * Allows admins to assign actual hex colors to attribute terms
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add color field when adding new attribute term
 */
function aakaari_add_attribute_color_field() {
	?>
	<div class="form-field">
		<label for="attribute-color"><?php esc_html_e( 'Color', 'aakaari-brand' ); ?></label>
		<input type="text" id="attribute-color" name="attribute_color" value="" class="aakaari-color-picker" />
		<p class="description"><?php esc_html_e( 'Choose a color for this attribute (leave empty if not a color attribute)', 'aakaari-brand' ); ?></p>
	</div>
	<?php
}

/**
 * Add color field when editing attribute term
 */
function aakaari_edit_attribute_color_field( $term ) {
	$color = get_term_meta( $term->term_id, 'attribute_color', true );
	?>
	<tr class="form-field">
		<th scope="row">
			<label for="attribute-color"><?php esc_html_e( 'Color', 'aakaari-brand' ); ?></label>
		</th>
		<td>
			<input type="text" id="attribute-color" name="attribute_color" value="<?php echo esc_attr( $color ); ?>" class="aakaari-color-picker" />
			<p class="description"><?php esc_html_e( 'Choose a color for this attribute (leave empty if not a color attribute)', 'aakaari-brand' ); ?></p>
		</td>
	</tr>
	<?php
}

/**
 * Save color field value
 */
function aakaari_save_attribute_color_field( $term_id ) {
	if ( isset( $_POST['attribute_color'] ) ) {
		update_term_meta( $term_id, 'attribute_color', sanitize_hex_color( $_POST['attribute_color'] ) );
	}
}

/**
 * Add color picker column to attribute terms list
 */
function aakaari_add_attribute_color_column( $columns ) {
	$columns['attribute_color'] = __( 'Color', 'aakaari-brand' );
	return $columns;
}

/**
 * Display color in the attribute terms list column
 */
function aakaari_display_attribute_color_column( $content, $column_name, $term_id ) {
	if ( 'attribute_color' === $column_name ) {
		$color = get_term_meta( $term_id, 'attribute_color', true );
		if ( $color ) {
			$content = sprintf(
				'<span class="attribute-color-preview" style="display:inline-block;width:30px;height:30px;background-color:%s;border:1px solid #ddd;border-radius:4px;vertical-align:middle;"></span> <span style="vertical-align:middle;">%s</span>',
				esc_attr( $color ),
				esc_html( $color )
			);
		} else {
			$content = '—';
		}
	}
	return $content;
}

/**
 * Enqueue color picker assets in admin
 */
function aakaari_enqueue_color_picker_admin( $hook ) {
	// Only load on taxonomy edit pages
	if ( 'edit-tags.php' !== $hook && 'term.php' !== $hook ) {
		return;
	}

	// Check if we're editing a product attribute
	if ( ! isset( $_GET['taxonomy'] ) || strpos( $_GET['taxonomy'], 'pa_' ) !== 0 ) {
		return;
	}

	// Enqueue WordPress color picker
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'wp-color-picker' );

	// Add inline script to initialize color picker
	wp_add_inline_script( 'wp-color-picker', '
		jQuery(document).ready(function($) {
			// Initialize color picker
			$(".aakaari-color-picker").wpColorPicker();

			// Re-initialize on AJAX add term (for adding new terms without page reload)
			$(document).ajaxComplete(function(event, xhr, settings) {
				if (settings.data && settings.data.indexOf("action=add-tag") !== -1) {
					setTimeout(function() {
						$(".aakaari-color-picker").wpColorPicker();
					}, 100);
				}
			});
		});
	' );
}

/**
 * Hook into all product attribute taxonomies
 */
function aakaari_init_color_attributes() {
	// Get all product attributes
	$attribute_taxonomies = wc_get_attribute_taxonomies();

	if ( empty( $attribute_taxonomies ) ) {
		return;
	}

	foreach ( $attribute_taxonomies as $attribute ) {
		$taxonomy = wc_attribute_taxonomy_name( $attribute->attribute_name );

		// Add fields to add/edit forms
		add_action( "{$taxonomy}_add_form_fields", 'aakaari_add_attribute_color_field' );
		add_action( "{$taxonomy}_edit_form_fields", 'aakaari_edit_attribute_color_field' );

		// Save field values
		add_action( "created_{$taxonomy}", 'aakaari_save_attribute_color_field' );
		add_action( "edited_{$taxonomy}", 'aakaari_save_attribute_color_field' );

		// Add column to terms list
		add_filter( "manage_edit-{$taxonomy}_columns", 'aakaari_add_attribute_color_column' );
		add_filter( "manage_{$taxonomy}_custom_column", 'aakaari_display_attribute_color_column', 10, 3 );
	}
}

// Initialize
add_action( 'admin_init', 'aakaari_init_color_attributes' );
add_action( 'admin_enqueue_scripts', 'aakaari_enqueue_color_picker_admin' );
