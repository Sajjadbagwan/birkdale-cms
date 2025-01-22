<?php

class PostList_Model extends Base_Model {
    public function __construct() {
       // add_action('woocommerce_init', 'store_data');
    }
    public function get_data( $id ) {
        // Fetch category data by ID
        return get_term( $id, 'category' );
    }

    public function store_data( $data ) {
        $per_page = get_option( 'posts_per_page' );       
        $page = $data;
        $PostResponse = wp_remote_get(site_url() . '/wp-json/wp/v2/posts?per_page='.$per_page.'&page='.$page.'&status=publish', array(
            'headers' => array(
                'Authorization' => 'Basic ' . base64_encode(REST_CONSUMER_KEY.':'.REST_CONSUMER_SECRET)
            )
        ));
        // Check if response is valid
        if ( is_wp_error( $PostResponse ) ) {
            error_log( 'Error fetching post list data: ' . $PostResponse->get_error_message() );
            return;
        }
        // Parse the body of the response
        $postBody = wp_remote_retrieve_body( $PostResponse );
        $headers = wp_remote_retrieve_headers($PostResponse);
        $postData = json_decode( $postBody, true ); // Decode the JSON response into an array
        if ( ! empty( $postData ) && $postData['data']['status'] != 400) {
            // Directory to save the JSON file
            $uploadPath = wp_upload_dir();
            $docs_folder = $uploadPath['basedir'] . '/post-list/';

            // Ensure the folder exists
            if ( ! file_exists( $docs_folder ) ) {
                wp_mkdir_p( $docs_folder ); // Create the directory if it doesn't exist
            }
            $headerData = array();
            if (!empty($headers)) {                
                // Initialize a header array
                foreach ($headers as $key => $value) {
                    if ($key == "x-wp-total" || $key == "x-wp-totalpages") {
                        $headerData[$key] = $value;
                    }
                }
            }
            $CatDataArray = array(
                'data' => $postData,
                'header' => $headerData
            );

            // Create the JSON file path
            $page_paths = $docs_folder . "page-".$page.".json";
            $post_json = json_encode( $CatDataArray, JSON_PRETTY_PRINT );
            file_put_contents( $page_paths, $post_json );          
        }    

        return "updated";
    }
  

}