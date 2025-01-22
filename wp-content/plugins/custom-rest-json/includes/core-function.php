<?php 
// add_filter('cron_schedules', 'custom_cron_schedules');
// function custom_cron_schedules($schedules)
// {
//   $schedules['daily'] = array(
//     'interval'  => 86400,
//     'display'   => __('Once Daily', 'textdomain')
//   );
//   return $schedules;
// }

// if (!wp_next_scheduled('custom_cron_schedules')) {
//   wp_schedule_event(time(), 'daily', 'custom_cron_schedules');
// }



// add_action('custom_cron_schedules', 'custom_cron_rest_json');
//custom_cron_rest_json();
function get_the_terms_cust( $post, $taxonomy ) {
	$post = get_post( $post );

	if ( ! $post ) {
		return false;
	}

	$terms = get_object_term_cache( $post->ID, $taxonomy );

	if ( false === $terms ) {
		$terms = wp_get_object_terms( $post->ID, $taxonomy );
		if ( ! is_wp_error( $terms ) ) {
			$term_ids = wp_list_pluck( $terms, 'term_id' );
			wp_cache_add( $post->ID, $term_ids, $taxonomy . '_relationships' );
		}
	}

	/**
	 * Filters the list of terms attached to the given post.
	 *
	 * @since 3.1.0
	 *
	 * @param WP_Term[]|WP_Error $terms    Array of attached terms, or WP_Error on failure.
	 * @param int                $post_id  Post ID.
	 * @param string             $taxonomy Name of the taxonomy.
	 */
	$terms = apply_filters( 'get_the_terms', $terms, $post->ID, $taxonomy );

	if ( empty( $terms ) ) {
		return false;
	}

	return $terms;
}


// // Register a custom schedule interval
// add_filter('cron_schedules', 'custom_cron_schedules');
// function custom_cron_schedules($schedules) {
//     $schedules['custom_interval'] = array(
//         'interval' => get_dynamic_cron_interval(), // Dynamic interval
//         'display'  => __('Custom Interval'),
//     );
//     return $schedules;
// }

// // Fetch the dynamic interval from the database
// function get_dynamic_cron_interval() {
//     global $wpdb;
//     $table_name = $wpdb->prefix . 'rest_json_cron'; // Table name with prefix

//     // Get the interval from the database (example: fetching the interval in seconds)
//     $interval_row = $wpdb->get_row("SELECT cron_time FROM $table_name WHERE id = 1", ARRAY_A);

//     // Set a default interval if no value found
//     $default_interval = 3600; // Default to 1 hour if not set in the database

//     return !empty($interval_row['cron_time']) ? intval($interval_row['cron_time']) : $default_interval;
// }

// // Schedule the custom cron event
// function schedule_custom_cron_event() {
//     if (!wp_next_scheduled('custom_cron_hook')) {
//         $interval = get_dynamic_cron_interval();
//         wp_schedule_event($interval, 'custom_interval', 'custom_cron_hook');
//     }
// }

// // Update the cron schedule if the interval changes
// add_action('custom_cron_hook', 'update_custom_cron_schedule');
// function update_custom_cron_schedule() {
//     $current_timestamp = wp_next_scheduled('custom_cron_hook');
//     if ($current_timestamp) {
//         wp_unschedule_event($current_timestamp, 'custom_cron_hook');
//     }

//     $interval = get_dynamic_cron_interval();
//     wp_schedule_event($interval, 'custom_interval', 'custom_cron_hook');
// }

// // Define the cron job callback
// add_action('custom_cron_hook', 'custom_cron_job_function');
// function custom_cron_job_function($id) {
//     // Your custom code here
//     $interval = get_dynamic_cron_interval();
//     print_r($interval);exit;
//     //$product_model = new Product_Model();
//     error_log('Custom Cron Job Function Executed'); // Example logging
// }