<?php

class Blog_Model extends Base_Model {

    public function get_data( $id ) {
        // Fetch blog post data by ID
        return get_post( $id );
    }

    public function store_data( $qrydata ) {
         if( ! $qrydata || ! isset ( $qrydata->post_name ) ) {
            error_log ( 'Invalid qrydata or missing post_name property.' );
            return;
        }
         // Fetch page data via REST API
         $response = wp_remote_get( site_url() . '/wp-json/wp/v2/posts?slug=' . $qrydata->post_name, array(
            'headers' => array(
                'Authorization' => 'Basic ' . base64_encode(REST_CONSUMER_KEY.':'.REST_CONSUMER_SECRET)
            )
        ));
        // Check if response is valid
        if ( is_wp_error( $response ) ) {
            error_log( 'Error fetching page data: ' . $response->get_error_message() );
            return;
        }
        // Parse the body of the response
        $body = wp_remote_retrieve_body( $response );
        
        $data = json_decode( $body, true ); // Decode the JSON response into an array
        if ( ! empty( $data ) ) {
            // Directory to save the JSON file
            $uploadPath = wp_upload_dir();
            $docs_folder = $uploadPath['basedir'] . '/posts/';

            // Ensure the folder exists
            if ( ! file_exists( $docs_folder ) ) {
                wp_mkdir_p( $docs_folder ); // Create the directory if it doesn't exist
            }
            // Create the JSON file path
            $page_paths = $docs_folder . $qrydata->post_name . ".json";
            // Save the response to a JSON file
            $page_json = json_encode( $data, JSON_PRETTY_PRINT );
            file_put_contents( $page_paths, $page_json );
        } else {
            error_log( 'No data returned for slug: ' . $qrydata->post_name );
        }
        return "updated";
        // Store blog post data
        // $post_id = wp_insert_post( $data );
        // return $post_id;
    }

}