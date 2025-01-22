<?php
class Product_Filters {
    public function __construct() {
    //    add_action('woocommerce_init', 'get_filtered_products');
    }

    public function get_filtered_products($params) {
        
            // Setup default args
            $args = array(
                'post_type' => 'product',
                'posts_per_page'=>-1,
                'tax_query' => array(
                    'relation' => 'AND',
                ),
                'meta_query' => array(
                    'relation' => 'AND',
                ),
                'orderby' => isset($params['orderby']) ? $params['orderby'] : 'date',
                'order' => isset($params['order']) ? $params['order'] : 'DESC',
            );
            if (isset($params['orderby']) && $params['orderby'] == 'popularity') {
                $args['meta_key'] = 'total_sales';
                $args['orderby'] = 'meta_value_num';
            }
            // Custom handling for ordering by price
            if (isset($params['orderby']) && $params['orderby'] == 'price') {
                $args['meta_key'] = '_price';
                $args['orderby'] = 'meta_value_num';
            }
            $args['post_status'] = !empty($params['status']) ? $params['status'] : 'publish';
            // Filter by category if set
            if (isset($params['category'])) {
                $args['tax_query'][] = array(
                    'taxonomy' => 'product_cat',
                    'field'    => 'id',
                    'terms'    => $params['category'],
                );
            }
            // Filter by category if set
            if (isset($params['brand'])) {
                $args['tax_query'][] = array(
                    'taxonomy' => 'brand',
                    'field'    => 'id',
                    'terms'    => $params['brand'],
                );
            }
            // Filter by tag if set
            if (isset($params['tag'])) {
                $args['tax_query'][] = array(
                    'taxonomy' => 'product_tag',
                    'field'    => 'id',
                    'terms'    => $params['tag'],
                );
            }
            
         
        
            // Filter by SKU if set
            if (isset($params['sku'])) {
                $args['meta_query'][] = array(
                    'key'     => '_sku',
                    'value'   => $params['sku'],
                    'compare' => '=',
                );
            }
            
            // Filter by specific attribute and term if set
            if (isset($params['attribute']) && isset($params['attribute_term'])) {
                foreach($params['attribute_term'] as $key=>$term_id){    
                    if($params['attribute'][$key] == 'srsltid'){
                        continue;
                    }    
                       // Sanitize taxonomy and terms
                $taxonomy = sanitize_text_field($params['attribute'][$key]);
                $terms = explode(",", $term_id);
                // Filter out non-numeric terms
                $valid_terms = array_filter($terms, 'is_numeric');     
        
                $args['tax_query'][] = array(
                    'taxonomy' => sanitize_text_field($params['attribute'][$key]),
                    'field'    => 'id',
                    'terms'    => $valid_terms,
                );
                }
            }
        
        
                // Remove invalid tax_query entries
                if (isset($args['tax_query']) && is_array($args['tax_query'])) {
                    foreach ($args['tax_query'] as $index => $query) {
                        if (!isset($query['terms']) || empty($query['terms'])) {
                            unset($args['tax_query'][$index]);
                        }
                    }
                    // Re-index array to ensure it’s properly indexed
                    $args['tax_query'] = array_values($args['tax_query']);
                }
         
            // Filter by stock status if set
            if (isset($params['stock_status'])) {
                $args['meta_query'][] = array(
                    'key'     => '_stock_status',
                    'value'   => sanitize_text_field($params['stock_status']),
                    'compare' => '=',
                );
            }
            if(!empty($args['tax_query'][1]) && $args['tax_query'][1]['taxonomy'] == 'srsltid' ){
                unset($args['tax_query'][1]);
            }
            if(!empty($args['tax_query'][0]) && $args['tax_query'][0]['taxonomy'] == 'srsltid' ){
                unset($args['tax_query'][0]);
            }
            // Fetch products
            $productsObjCount = wc_get_products($args);
            // echo "load product arg";
            // echo "<pre>";
            // print_r($args);
            // echo "</pre>";
            // print_r($productsObjCount);exit;
            $total_products = count($productsObjCount);
            $per_page = !empty($params['per_page']) ? intval($params['per_page']) : 12;
            $total_products_page = ceil($total_products / $per_page);
            $args['posts_per_page'] = $per_page;
            $args['paged'] = !empty($params['page']) ? intval($params['page']) : 1;
            $productsObj = wc_get_products($args);
            $productsArray = array();
            if ($productsObj) {
                foreach($productsObj as $product){
                    $attributes = array();
                    $images = array();
                    foreach ($product->get_attributes() as $attribute_name => $attribute) {
                        $attributes[] = array(
                            "id" => $attribute->get_id(),
                            "name" => $attribute->get_name(),
                            "slug" => $attribute->get_name(),
                            "options" => $attribute->get_options(),
                        );
                    }
                    if($product->get_image_id()){
                        $images[] = array(
                            "src" => wp_get_attachment_image_src($product->get_image_id(), 'full')[0],
                            "alt" => get_post_meta( $product->get_image_id(), '_wp_attachment_image_alt', true )
                        );
                    }
                    if($product->get_gallery_image_ids()){
                        foreach ($product->get_gallery_image_ids() as $image) {
                            $images[] = array(
                                "src" => wp_get_attachment_image_src($image, 'full')[0],
                                "alt" => get_post_meta( $image, '_wp_attachment_image_alt', true )
                            );
                        }
                    }
                    $productsArray[]=array(
                    "id"=>$product->get_id(),  
                    "status"=>$product->get_status(),  
                    "type"=>$product->get_type(),
                    "name"=>$product->get_name(),
                    "slug"=>$product->get_slug(),
                    "price"=>$product->get_price(),
                    "regular_price"=>$product->get_regular_price(),
                    "sale_price"=>$product->get_sale_price(),
                    "image_id"=>$product->get_image_id(),
                    "image"=>$product->get_image(),
                    "gallery_image_ids"=>$product->get_gallery_image_ids(),
                    "short_description"=>$product->get_short_description(),
                    "price_html"=>$product->get_price_html(),
                    "meta_data"=>$product->get_meta_data(),
                    "images"=>$images,
                    "attributes"=>$attributes,
                    "stock_status"=>$product->get_stock_status(),
                );
                    //$product = wc_get_product( $post->ID );
                    //$products[] = $product;
                }
            }
            return json_encode($productsArray);
            // $response->header( 'X-WP-Total', $total_products );
            // $response->header( 'X-WP-TotalPages',  $total_products_page );
            // Restore original Post Data
            //wp_reset_postdata();
            //print_r($response);exit;
            // Return response
            //return $response;
        }
    
}