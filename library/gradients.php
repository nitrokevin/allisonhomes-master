<?php
// functions/gradients.php

// Include your colors
require_once get_template_directory() . '/library/colors.php'; // adjust path if needed


// Helper: lighten a hex color
  function hex2rgb($hex) {
        $hex = str_replace('#', '', $hex);
        return [
            'r' => hexdec(substr($hex,0,2)),
            'g' => hexdec(substr($hex,2,2)),
            'b' => hexdec(substr($hex,4,2))
        ];
    }

    function lightness($hex) {
        $rgb = hex2rgb($hex);
        $max = max($rgb);
        $min = min($rgb);
        return (($max + $min) / 2) / 255 * 100;
    }

    function lighten($hex, $percent) {
        $rgb = hex2rgb($hex);
        foreach ($rgb as $k => $v) {
            $rgb[$k] = min(255, $v + 255 * $percent / 100);
        }
        return sprintf("#%02x%02x%02x", $rgb['r'], $rgb['g'], $rgb['b']);
    }

    function darken($hex, $percent) {
        $rgb = hex2rgb($hex);
        foreach ($rgb as $k => $v) {
            $rgb[$k] = max(0, $v - 255 * $percent / 100);
        }
        return sprintf("#%02x%02x%02x", $rgb['r'], $rgb['g'], $rgb['b']);
    }

    // ----------------------
    // 4️⃣ Single-color gradients (light → dark)
    // ----------------------
    $gradients = [];
    foreach ($colors as $slug => $hex) {
        if (!is_string($hex) || $hex === 'transparent') continue;

        $l = lightness($hex);
        if ($l > 15 && $l < 95) {
            $light = lighten($hex, 12);
            $dark  = darken($hex, 8);
            $gradients[] = [
                'name'     => ucwords(str_replace(['-', '_'], ' ', $slug)),
                'slug'     => $slug,
                'gradient' => "linear-gradient(135deg, $light 0%, $dark 100%)",
            ];
        }
    }

    // ----------------------
    // 5️⃣ Cross-color gradients
    // ----------------------
    $cross_pairs = [
        ['primary', 'secondary'],
        ['theme-color-1', 'theme-color-2'],
        ['theme-color-3', 'theme-color-4'],
        ['theme-color-4', 'theme-color-5'],
    ];

    foreach ($cross_pairs as $pair) {
        [$start, $end] = $pair;
        if (!isset($colors[$start], $colors[$end])) continue;

        // ✅ Ensure slug is valid: lowercase letters, numbers, hyphens
        $slug = strtolower($start . '-to-' . $end);

        $gradients[] = [
            'name'     => ucwords(str_replace(['-', '_'], ' ', $start)) . ' to ' . ucwords(str_replace(['-', '_'], ' ', $end)),
            'slug'     => $slug,
            'gradient' => "linear-gradient(135deg, {$colors[$start]} 0%, {$colors[$end]} 100%)",
        ];
    }

// Register gradients in Gutenberg
add_action('after_setup_theme', function() use ($gradients) {
    add_theme_support('editor-gradient-presets', $gradients);
});

?>