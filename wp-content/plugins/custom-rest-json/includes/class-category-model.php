<?php
//if(!defined(ABSPATH)){
//    $pagePath = explode('/wp-content/', dirname(__FILE__));
//    include_once(str_replace('wp-content/' , '', $pagePath[0] . '/wp-load.php'));
//}
class Category_Model extends Base_Model {
    public function __construct() {
       // add_action('woocommerce_init', 'store_data');
    }
    public function get_data( $id ) {
        // Fetch category data by ID
        return get_term( $id, 'category' );
    }

    public function store_data( $data ) {
        $options = get_option('custom_rest_json_options');
        $per_page=$options['products_per_page'];
        
        //$filter = new Product_Filters();
        //$response = $filter->get_filtered_products($params);
        $shopResponse = wp_remote_get(site_url() . '/wp-json/wc/v2/products?per_page='.$per_page.'&status=publish&in_stock=true&orderby=date&order=desc', array(
            'headers' => array(
                'Authorization' => 'Basic ' . base64_encode('donotremove:p8b7 DZGU zMBr KbqF r4va 6ami')
            )
        ));
        // Check if response is valid
        if ( is_wp_error( $shopResponse ) ) {
            error_log( 'Error fetching page data: ' . $shopResponse->get_error_message() );
            return;
        }
        // Parse the body of the response
        $shopBody = wp_remote_retrieve_body( $shopResponse );
        $headers = wp_remote_retrieve_headers($shopResponse);

        $shopData = json_decode( $shopBody, true ); // Decode the JSON response into an array
        
        if ( ! empty( $shopData ) && $shopData['code'] != 'woocommerce_rest_cannot_view') {
            // Directory to save the JSON file
            $uploadPath = wp_upload_dir();
            $docs_folder = $uploadPath['basedir'] . '/categories/';

            // Ensure the folder exists
            if ( ! file_exists( $docs_folder ) ) {
                wp_mkdir_p( $docs_folder ); // Create the directory if it doesn't exist
            }
            $headershopData = array();
            if (!empty($headers)) {                
                // Initialize a header array
                foreach ($headers as $key => $value) {
                    if ($key == "x-wp-total" || $key == "x-wp-totalpages") {
                        $headershopData[$key] = $value;
                    }
                }
            }
            $ShopDataArray = array(
                'data' => $shopData,
                'header' => $headershopData
            );
            // Create the JSON file path
            $page_paths = $docs_folder . "shop.json";
            $shop_json = json_encode( $ShopDataArray, JSON_PRETTY_PRINT );
            file_put_contents( $page_paths, $shop_json );          
        }    

        // $cat_args = array(
        //     'hide_empty' => false,
        // );
        //$product_categories = get_terms( 'product_cat', $cat_args );
        
        if(!empty($data)){
            //foreach($data as $cat){
                //if($cat->count > 0){
                    $data['type'] == "product_cat" ? $cattype = "category" : $cattype = $data['type'];
                    //$catdata = $filter->get_filtered_products($params);
                    $catResponse = wp_remote_get(site_url() . '/wp-json/wc/v2/products?orderby=date&order=desc&per_page='.$per_page.'&status=publish&'.$cattype.'='.$data['type_id'], array(
                        'headers' => array(
                           'Authorization' => 'Basic ' . base64_encode(REST_CONSUMER_KEY.':'.REST_CONSUMER_SECRET)
                        )
                    ));
                    // Check if response is valid
                    if ( is_wp_error( $catResponse ) ) {
                        error_log( 'Error fetching page data: ' . $catResponse->get_error_message() );
                        return;
                    }
                    // Parse the body of the response
                    $body = wp_remote_retrieve_body( $catResponse );
                    $catheaders = wp_remote_retrieve_headers($catResponse);
                    $catdata = json_decode( $body, true ); // Decode the JSON response into an array
                    // Check if response is valid
                    if ( ! empty( $catdata )  && $catdata['code'] != 'woocommerce_rest_cannot_view' ) {
                        // Directory to save the JSON file
                        $catuploadPath = wp_upload_dir();
                        if($data['type'] == "category" || $data['type'] == "product_cat"){
                            $cat_docs_folder = $catuploadPath['basedir'] . '/categories/';    
                        }else{
                            $cat_docs_folder = $catuploadPath['basedir'] . '/'.$data['type'].'/';                            
                        }
            
                        // Ensure the folder exists
                        if ( ! file_exists( $cat_docs_folder ) ) {
                            wp_mkdir_p( $cat_docs_folder ); // Create the directory if it doesn't exist
                        }
                        $headercatData = array();
                        if (!empty($catheaders)) {                
                            // Initialize a header array
                            foreach ($catheaders as $key => $value) {
                                if ($key == "x-wp-total" || $key == "x-wp-totalpages") {
                                    $headercatData[$key] = $value;
                                }
                            }
                        }
                        $CatDataArray = array(
                            'data' => $catdata,
                            'header' => $headercatData
                        );
                        // Create the JSON file path
                        $cat_paths = $cat_docs_folder . $data['type_id'].".json";
                        $cat_json = json_encode( $CatDataArray, JSON_PRETTY_PRINT );
                        file_put_contents( $cat_paths, $cat_json );
                    }
                //}    
            //}
        }
        return "updated";
    }
  

}