<?php
if ( ! function_exists( 'foundationpress_gutenberg_support' ) ) :
function foundationpress_gutenberg_support() {

    // Load colors
    include 'colors.php';
    $editor_colors = array();
    foreach ( $colors as $slug => $color ) {
        $name = ucwords(str_replace(array('-', '_'), ' ', $slug));
        $editor_colors[] = array(
            'name'  => __( $name, 'foundationpress' ),
            'slug'  => $slug,
            'color' => $color,
        );
    }
    add_theme_support( 'editor-color-palette', $editor_colors );

    // Load gradients — include your gradients.php
    include 'gradients.php';

    // $gradients is already in the correct format
    add_theme_support( 'editor-gradient-presets', $gradients );

    // Optional: disable WP defaults
    add_theme_support( 'disable-custom-gradients' );
}
add_action( 'after_setup_theme', 'foundationpress_gutenberg_support' );
endif;