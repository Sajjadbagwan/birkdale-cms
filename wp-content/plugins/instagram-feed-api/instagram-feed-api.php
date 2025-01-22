<?php
/**
 * Plugin Name: Instagram Feed API
 * Plugin URI: https://example.com
 * Description: A simple plugin to expose Instagram feeds via REST API.
 * Version: 1.0
 * Author: KGN
 * Author URI: https://example.com
 * License: GPL2
 */

function register_instagram_feed_route() {
    register_rest_route('instagram/v1', '/get-feeds', array(
        'methods'             => 'GET',
        'callback'            => 'getFeed',
        'permission_callback' => '__return_true', // Allow public access to the API (use caution)
    ));
}
add_action('rest_api_init', 'register_instagram_feed_route');

function instagram_feed_json_activate() {
}

register_activation_hook(__FILE__, 'instagram_feed_json_activate');

function instagram_feed_json_deactivate() {
}

register_deactivation_hook(__FILE__, 'instagram_feed_json_deactivate');

function getFeed() {
    $feed = do_shortcode('[instagram-feed feed=1]');
    if (strpos($feed, 'placeholder.jpg') !== false) {
        return new WP_Error('no_feed', 'Instagram feed could not be fetched.', array('status' => 500));
    }
    $dom = new DOMDocument;
    @$dom->loadHTML($feed);

    $images = [];

    foreach ($dom->getElementsByTagName('a') as $img) {
        $data_full_res = $img->getAttribute('data-full-res');
        $link          = $img->getAttribute('href');

        if ($data_full_res) {
            $images[] = [
                'img'  => $data_full_res,
                'link' => $link,
            ];
        }
    }
    return rest_ensure_response($images);
}
