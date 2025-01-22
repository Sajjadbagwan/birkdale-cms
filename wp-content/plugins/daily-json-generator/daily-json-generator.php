<?php

/**
 * Plugin Name: Generate Json for Header Footer
 * Description: API Generate Json for Header Footer.
 * Version: 1.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
  exit;
}


/* Cron Setup */
add_filter('cron_schedules', 'generatejson_header_footer');
function generatejson_header_footer($schedules)
{
  $schedules['daily'] = array(
    'interval'  => 86400,
    'display'   => __('Once Daily', 'textdomain')
  );
  return $schedules;
}

if (!wp_next_scheduled('generatejson_header_footer')) {
  wp_schedule_event(time(), 'daily', 'generatejson_header_footer');
}



add_action('generatejson_header_footer', 'generatejson_cron_function');
function generatejson_cron_function()
{
  $url = site_url() . '/wp-json/wp/v2/generate-json';
  $response = wp_remote_get($url);

  if (is_wp_error($response)) {
    $error_message = $response->get_error_message();
    error_log("API call failed: $error_message");
  } else {
    $body = wp_remote_retrieve_body($response);
    // Process the response
    error_log("API call succeeded: $body");
  }
}
