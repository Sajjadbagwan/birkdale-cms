<?php

class Product_Model extends Base_Model {

    public function get_data( $id ) {
        // Fetch product data by ID
        return get_post( $id );
    }

    public function store_data( $qrydata ) {
      
        $detail_response = wp_remote_get( site_url() . '/wp-json/wc/v3/products/?slug='.$qrydata->post_name, array(
            'headers' => array(
                'Authorization' => 'Basic ' . base64_encode(REST_CONSUMER_KEY.':'.REST_CONSUMER_SECRET)
            )
        ));
        if ( is_wp_error( $detail_response ) ) {
            error_log( 'Error fetching page data: ' . $detail_response->get_error_message() );
            return;
        }
        // Parse the body of the response
        $detail_body = wp_remote_retrieve_body( $detail_response );
        
        $detaildata = json_decode( $detail_body, true ); // Decode the JSON response into an array
        if ( ! empty( $detaildata ) ) {
            // Directory to save the JSON file
            $detailuploadPath = wp_upload_dir();
            $detail_docs_folder = $detailuploadPath['basedir'] . '/productdetail/';

            // Ensure the folder exists
            if ( ! file_exists( $detail_docs_folder ) ) {
                wp_mkdir_p( $detail_docs_folder ); // Create the directory if it doesn't exist
            }
            // Create the JSON file path
            $detail_paths = $detail_docs_folder . $qrydata->post_name . ".json";
            // Save the response to a JSON file
            $detail_json = json_encode( $detaildata, JSON_PRETTY_PRINT );
            file_put_contents( $detail_paths, $detail_json );
        } else {
            error_log( 'No data returned for slug: ' . $qrydata->post_name );
        }

        // Store product data
        // $post_id = wp_insert_post( $data );
        // return $post_id;
    }

}