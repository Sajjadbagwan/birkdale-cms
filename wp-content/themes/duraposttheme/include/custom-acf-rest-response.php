<?php

// Main function for handling ACF response
function get_acf_rest_response($item, $request) {
    if( ! isset ( $item[ 'acf' ][ 'flexible_listing' ] ) || isset ( $_GET[ 'per_page' ] ) )
        return $item;

    $global_option = get_field ( 'global_flexible_listing', 'option' );

    foreach ( $item[ 'acf' ][ 'flexible_listing' ] as $key => $relation ) {
        $global_key = 'global__' . $relation[ 'acf_fc_layout' ];

        // Handle global data
        if( isset ( $relation[ $global_key ] ) ) {
            $item[ 'acf' ][ 'flexible_listing' ][ $key ][ $global_key ] = $relation[ $global_key ];

            $globalData = $relation[ $global_key ] === true || $relation[ $global_key ] == 1 ? global_acf_data ( $global_option, $relation[ 'acf_fc_layout' ] ) : null;
            if( $globalData ) {
                $item[ 'acf' ][ 'flexible_listing' ][ $key ] = array_merge ( $relation, $globalData );
            } else {
                $item[ 'acf' ][ 'flexible_listing' ][ $key ][ $global_key ] = false;
            }
        } else {
            $item[ 'acf' ][ 'flexible_listing' ][ $key ][ $global_key ] = false;
        }

        // Special handling for testimonials
        if( $relation[ 'acf_fc_layout' ] == 'testimonial' ) {
            $item[ 'acf' ][ 'flexible_listing' ][ $key ] = array_merge ( $relation, get_testimonials_data ( $relation, $global_option ) );
        }

        if( $relation[ 'acf_fc_layout' ] == 'inspiration_section' ) {
            $item[ 'acf' ][ 'flexible_listing' ][ $key ] = array_merge ( $relation, get_inspiration_data ( $relation, $global_option ) );
        }

        if( $relation[ 'acf_fc_layout' ] == 'resource_section' ) {
            $item[ 'acf' ][ 'flexible_listing' ][ $key ] = array_merge ( $relation, get_resource_data ( $relation, $global_option ) );
        }
    }



    return $item;
}

add_filter ( 'acf/rest_api/page/get_fields', 'get_acf_rest_response', 10, 2 );
add_filter ( 'acf/rest_api/post/get_fields', 'get_acf_rest_response', 10, 2 );

// Helper function to get testimonials data
function get_testimonials_data($relation, $global_option) {

    if( isset ( $relation[ 'global__testimonial' ] ) && $relation[ 'global__testimonial' ] == 1 ) {
        $global_testimonial_data = global_acf_data ( $global_option, 'testimonial' );
        return $global_testimonial_data ? array_merge ( $global_testimonial_data, get_testimonials_data ( $global_testimonial_data, $global_option ) ) : [];
    }
    if( isset ( $relation[ 'all_testimonial' ] ) && $relation[ 'all_testimonial' ] == 1 ) {
        return [ 'testimonials' => get_testimonials ( [], 5 ) ];
    }
    if( ! empty ( $relation[ 'selected_testimonial' ] ) ) {
        return [ 'testimonials' => get_testimonials ( $relation[ 'selected_testimonial' ] ) ];
    }

    return [];
}

function get_testimonials($ids = [], $limit = 5) {
    $args = [
        'post_type'   => 'testimonial',
        'post_status' => 'publish',
    ];

    if( ! empty ( $ids ) ) {
        $args[ 'post__in' ]       = $ids;
        $args[ 'posts_per_page' ] = -1;
        $args[ 'orderby' ]        = 'post__in';
    } else {
        $args[ 'posts_per_page' ] = $limit;
        $args[ 'orderby' ]        = 'date';
        $args[ 'order' ]          = 'DESC';
    }

    $posts = get_posts ( $args );
    return array_map ( function ($post) {
        return [
            'ID'          => $post->ID,
            'name'        => $post->post_title,
            'description' => get_post_field ( 'post_content', $post->ID ),
            'rating'      => get_field ( 'testimonial_rating', $post->ID ),
        ];
    }, $posts );
}

function get_inspiration_data($relation, $global_option) {
    if( isset ( $relation[ 'global__inspiration_section' ] ) && $relation[ 'global__inspiration_section' ] == 1 ) {
        $global_inspiration_data = global_acf_data ( $global_option, 'inspiration_section' );
        return $global_inspiration_data ? array_merge ( $global_inspiration_data, get_inspiration_data ( $global_inspiration_data, $global_option ) ) : [];
    } else {
        return [ 'inspiration' => get_inspiration_posts ( -1 ) ];
    }
}

function get_inspiration_posts($limit = -1) {
    $args = [
        'post_type'      => 'inspiration',
        'post_status'    => 'publish',
        'posts_per_page' => $limit,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ];

    // Fetch the posts
    $posts = get_posts ( $args );

    $latest_post       = null;
    $small_image_posts = [];
    $remaining_posts   = [];
    $inspiration_posts = [];
    $counter           = 0;

    // Process each post
    foreach ( $posts as $post ) {
        $post_id             = $post->ID;
        $feature_image       = get_field ( 'listing_page_image', $post_id );
        $feature_image_style = $feature_image[ 'feature_image_style' ] ?? '';
        $feature_image_url   = $feature_image[ 'image' ] ?? '';
        $button              = $feature_image[ 'button' ];

        $content         = get_post_field ( 'post_content', $post_id );
        $limited_content = wp_trim_words ( $content, 10, '' );

        $post_data = [
            'ID'      => $post_id,
            'style'   => $feature_image_style,
            'image'   => $feature_image_url,
            'content' => $limited_content,
            'link'    => get_permalink ( $post_id ),
            'button'  => $button,
        ];
        if( $counter === 0 ) {
            $latest_post = $post_data;
        } else if( $post_data[ 'style' ] === 'Small Image' ) {
            $small_image_posts[] = $post_data;
        } else {
            $remaining_posts[] = $post_data;
        }

        $counter ++;
    }

    if( $latest_post ) {
        $inspiration_posts[] = $latest_post;
    }
    if( count ( $small_image_posts ) > 0 ) {
        $inspiration_posts[] = $small_image_posts[ 0 ];
    }
    if( count ( $small_image_posts ) > 1 ) {
        $remaining_posts_with_small_image = array_merge (
                array_slice ( $remaining_posts, 0, 4 ),
                [ $small_image_posts[ 1 ] ],
                array_slice ( $remaining_posts, 4 )
        );
        $inspiration_posts                = array_merge ( $inspiration_posts, $remaining_posts_with_small_image );
    } else {
        $inspiration_posts = array_merge ( $inspiration_posts, $remaining_posts );
    }
    if( count ( $small_image_posts ) > 2 ) {
        $inspiration_posts = array_merge ( $inspiration_posts, array_slice ( $small_image_posts, 2 ) );
    }
    return array_slice ( $inspiration_posts, 0, 7 );
}

function get_resource_data($relation, $global_option) {

    if( isset ( $relation[ 'global__resource_section' ] ) && $relation[ 'global__resource_section' ] == 1 ) {
        $global_resource_data = global_acf_data ( $global_option, 'resource_section' );
        return $global_resource_data ? array_merge ( $global_resource_data, get_resource_data ( $global_resource_data, $global_option ) ) : [];
    }

    if( isset ( $relation[ 'resource_select_type' ] ) ) {
        switch ($relation[ 'resource_select_type' ]) {
            case 'Latest':
                return [ 'resources' => get_resources_list ( [], 4 ) ];

            case 'Manually':
                return [ 'resources' => get_resources_list ( $relation[ 'select_resource_manually' ] ) ];

            case 'Specific Category':
                if( isset ( $relation[ 'resource_base_on_category' ] ) ) {
                    return [ 'resources' => get_resources_list ( [], 4, $relation[ 'resource_base_on_category' ] ) ];
                }
                break;
        }
    }

    return [];
}

function get_resources_list($ids = [], $limit = 4, $category = '') {
    $args = [
        'post_type'   => 'resource',
        'post_status' => 'publish',
    ];

    if( ! empty ( $ids ) ) {
        $args[ 'post__in' ]       = $ids;
        $args[ 'posts_per_page' ] = -1;
        $args[ 'orderby' ]        = 'post__in';
    } else {
        $args[ 'posts_per_page' ] = $limit;
        $args[ 'orderby' ]        = 'date';
        $args[ 'order' ]          = 'DESC';
    }

    if( ! empty ( $category ) ) {
        $args[ 'tax_query' ] = [
            [
                'taxonomy' => 'resource_category',
                'field'    => 'id',
                'terms'    => $category,
                'operator' => 'IN',
            ]
        ];
    }

    $posts = get_posts ( $args );

    return array_map ( function ($post) {
        $data           = get_field ( 'cpt_details', 'option' );
        $link_label     = $data[ 'resource_section_link_label' ][ 'read_more_label' ];
        $download_label = $data[ 'resource_section_link_label' ][ 'download_label' ];
        $download_text  = $data[ 'resource_section_link_label' ][ 'download_text_label' ];

        $button_type = get_field ( 'resource_button_type', $post->ID );
        if( $button_type === 'Download' ) {
            $resource_pdf  = get_field ( 'resource_pdf', $post->ID );
            $button_url    = $resource_pdf ? $resource_pdf[ 'url' ] : '';
            $button_label  = $download_label;
            $date_or_label = $download_text;
        } else {
            $button_url    = get_permalink ( $post->ID );
            $button_label  = $link_label;
            $date_or_label = get_the_date ( 'd.m.y', $post->ID );
        }
        $content         = get_post_field ( 'post_content', $post->ID );
        $limited_content = wp_trim_words ( $content, 30, '' );

        return [
            'ID'            => $post->ID,
            'image'         => get_the_post_thumbnail_url ( $post->ID, 'full' ),
            'date_or_label' => $date_or_label,
            'name'          => $post->post_title,
            'description'   => $limited_content,
            'button_url'    => $button_url,
            'button_label'  => $button_label,
        ];
    }, $posts );
}

// Function to get global ACF data
function global_acf_data($data, $key) {
    foreach ( $data as $r ) {
        if( $r[ 'acf_fc_layout' ] == $key )
            return $r;
    }
    return null;
}

/**/
add_filter ( 'rest_prepare_case_study', 'add_extra_field_case_study_in_rest', 10, 3 );

function add_extra_field_case_study_in_rest($response, $post, $request) {
    $post_id = $post->ID;

    $case_study = get_field ( 'cpt_details', 'option' );

    $back_to_news                              = $case_study[ 'case_study_back_to_link' ];
    $response->data[ 'acf' ][ 'back_to_page' ] = $back_to_news;

    $social_sharing_title                      = $case_study[ 'case_study_share_title' ];
    $response->data[ 'acf' ][ 'social_title' ] = $social_sharing_title;

    $post_title = urlencode ( $post->post_title );
    $post_url   = urlencode ( get_permalink ( $post_id ) );

    $args = array (
        'post_type'      => 'case_study',
        'posts_per_page' => -1,
        'orderby'        => 'ID',
        'order'          => 'ASC',
        'fields'         => 'ids'
    );

    $posts         = get_posts ( $args );
    $current_index = array_search ( $post_id, $posts );

    $next_post_id     = isset ( $posts[ $current_index + 1 ] ) ? $posts[ $current_index + 1 ] : null;
    $previous_post_id = isset ( $posts[ $current_index - 1 ] ) ? $posts[ $current_index - 1 ] : null;

    $next_post     = $next_post_id ? get_post ( $next_post_id ) : null;
    $previous_post = $previous_post_id ? get_post ( $previous_post_id ) : null;

    $next_perv_label = $case_study[ 'case_study_np_btn_title' ];

    $response->data[ 'acf' ][ 'next_post' ]     = $next_post ? [
        'id'    => $next_post->ID,
        'name'  => $next_post->post_name,
        'label' => $next_perv_label[ 'next_button_title' ],
        'url'   => get_permalink ( $next_post->ID )
            ] : null;
    $response->data[ 'acf' ][ 'previous_post' ] = $previous_post ? [
        'id'     => $previous_post->ID,
        'name'   => $previous_post->post_name,
        'laabel' => $next_perv_label[ 'previous_button_title' ],
        'url'    => get_permalink ( $previous_post->ID )
            ] : null;

    // Replace image ID with image URL for featured media
    if( isset ( $response->data[ 'featured_media' ] ) ) {
        $media_id                           = $response->data[ 'featured_media' ];
        $media_url                          = wp_get_attachment_url ( $media_id );
        $response->data[ 'featured_media' ] = $media_url;
    }

    // Fetch related posts based on the news_category taxonomy
    $related_product_title                              = $case_study[ 'case_study_product_title' ];
    $response->data[ 'acf' ][ 'related_product_title' ] = $related_product_title;

    $related_button_label                              = $case_study[ 'case_study_product_btn_label' ];
    $response->data[ 'acf' ][ 'product_button_label' ] = $related_button_label;

    $about_durapost                              = $case_study[ 'case_study_about_durapost' ];
    $response->data[ 'acf' ][ 'about_durapost' ] = $about_durapost;

    $related_article                                     = $case_study[ 'related_article_details' ];
    $response->data[ 'acf' ][ 'related_article_title' ]  = $related_article[ 'case_study_related_title' ];
    $response->data[ 'acf' ][ 'related_article_button' ] = $related_article[ 'case_study_view_button' ];

    $categories = wp_get_post_terms ( $post_id, 'case_study_collections' );
    if( ! empty ( $categories ) && ! is_wp_error ( $categories ) ) {
        $category_ids = wp_list_pluck ( $categories, 'term_id' );

        // Get related posts (excluding the current post)
        $related_posts = new WP_Query ( array (
            'post_type'      => 'case_study',
            'posts_per_page' => 6,
            'tax_query'      => array (
                array (
                    'taxonomy' => 'case_study_collections',
                    'field'    => 'term_id',
                    'terms'    => $category_ids,
                    'operator' => 'IN',
                ),
            ),
            'post__not_in'   => array ( $post_id ),
                ) );

        if( $related_posts->have_posts () ) {
            $related_posts_data = array ();
            while ( $related_posts->have_posts () ) {
                $related_posts->the_post ();
                $related_posts_data[] = [
                    'ID'          => get_the_ID (),
                    'title'       => get_the_title (),
                    'image'       => get_the_post_thumbnail_url ( get_the_ID (), 'full' ),
                    'date'        => get_the_date ( 'd.m.y', get_the_ID () ),
                    'description' => wp_trim_words ( get_the_content (), 40 ),
                    'link'        => get_permalink ( get_the_ID () ),
                ];
            }
            wp_reset_postdata (); // Reset the global post data
            $response->data[ 'acf' ][ 'related_case_study' ] = $related_posts_data;
        }
    }

    $show_global_information = $case_study[ 'global_case_study_box_information' ];
    if( $show_global_information == 1 ) {
        $global_option = get_field ( 'global_flexible_listing', 'option' );
        $information   = array ();
        if( $global_option ) {
            foreach ( $global_option as $block ) {
                if( isset ( $block[ 'acf_fc_layout' ] ) && $block[ 'acf_fc_layout' ] == 'information_section' ) {
                    $data = $block[ 'informations' ];
                }
            }
        }
        $response->data[ 'acf' ][ 'information' ] = $data;
    } else {
        $information                              = $case_study[ 'case_study_information' ];
        $response->data[ 'acf' ][ 'information' ] = $information;
    }

    return $response;
}

/**/
add_filter ( 'rest_prepare_tools_guide', 'add_extra_field_tools_guides_in_rest', 10, 3 );

function add_extra_field_tools_guides_in_rest($response, $post, $request) {
    $post_id = $post->ID;

    $tools_guides = get_field ( 'cpt_details', 'option' );

    $back_to_article                           = $tools_guides[ 'tag_back_to_link' ];
    $response->data[ 'acf' ][ 'back_to_page' ] = $back_to_article;

    $social_sharing_title                      = $tools_guides[ 'tag_share_title' ];
    $response->data[ 'acf' ][ 'social_title' ] = $social_sharing_title;

    // Replace image ID with image URL for featured media
    if( isset ( $response->data[ 'featured_media' ] ) ) {
        $media_id                           = $response->data[ 'featured_media' ];
        $media_url                          = wp_get_attachment_url ( $media_id );
        $response->data[ 'featured_media' ] = $media_url;
    }

    $related_article                                     = $tools_guides[ 'tag_related_article_details' ];
    $response->data[ 'acf' ][ 'related_article_title' ]  = $related_article[ 'tag_related_title' ];
    $response->data[ 'acf' ][ 'related_article_button' ] = $related_article[ 'tag_view_all_button' ];

    $categories = wp_get_post_terms ( $post_id, 'tools_guide_type' );
    if( ! empty ( $categories ) && ! is_wp_error ( $categories ) ) {
        $category_ids = wp_list_pluck ( $categories, 'term_id' );

        // Get related posts (excluding the current post)
        $related_posts = new WP_Query ( array (
            'post_type'      => 'tools_guide',
            'posts_per_page' => 4,
            'tax_query'      => array (
                array (
                    'taxonomy' => 'tools_guide_type',
                    'field'    => 'term_id',
                    'terms'    => $category_ids,
                    'operator' => 'IN',
                ),
            ),
            'post__not_in'   => array ( $post_id ),
                ) );

        if( $related_posts->have_posts () ) {
            $related_posts_data = [];
            while ( $related_posts->have_posts () ) {
                $related_posts->the_post ();
                $post_id = get_the_ID ();

                if( class_exists ( 'acf' ) ) {
                    $cpt_details       = get_field ( 'cpt_details', 'option' );
                    $button_type       = get_field ( 'button_type', $post_id );
                    $pdf               = get_field ( 'pdf', $post_id );
                    $video_type        = get_field ( 'video_type', $post_id );
                    $mp4_video         = get_field ( 'mp4_video', $post_id );
                    $vimeo_youtube_url = get_field ( 'vimeo_youtube_url', $post_id );

                    $pdf_button_label   = $cpt_details[ 'tag_page_list_button_labels' ][ 'pdf_link_label' ];
                    $video_button_label = $cpt_details[ 'tag_page_list_button_labels' ][ 'video_link_label' ];

                    if( $button_type == 'Download' ) {
                        $link_label = $pdf_button_label;
                        $link_url   = $pdf;
                        $video_link = '';
                    } else {
                        $link_label = $video_button_label;
                        $link_url   = get_permalink ( $post_id );
                        $video_link = ( $video_type == 'MP4' ) ? $mp4_video : $vimeo_youtube_url;
                    }
                }

                $related_posts_data[] = [
                    'ID'           => $post_id,
                    'image'        => get_the_post_thumbnail_url ( $post_id, 'full' ),
                    'title'        => get_the_title (),
                    'description'  => wp_trim_words ( get_the_content (), 40 ),
                    'video_link'   => $video_link,
                    'button_label' => $link_label,
                    'button_link'  => $link_url,
                ];
            }
            wp_reset_postdata (); // Reset the global post data
            $response->data[ 'acf' ][ 'related_article' ] = $related_posts_data;
        }
    }

    $show_global_information = $tools_guides[ 'global_tag_get_in_touch' ];
    if( $show_global_information == 1 ) {
        $global_option = get_field ( 'global_flexible_listing', 'option' );
        $information   = array ();
        if( $global_option ) {
            foreach ( $global_option as $block ) {
                if( isset ( $block[ 'acf_fc_layout' ] ) && $block[ 'acf_fc_layout' ] == 'get_in_touch' ) {
                    $data = array (
                        'title'        => $block[ 'get_in_touch_title' ],
                        'form_details' => $block[ 'get_in_touch_form_details' ],
                        'image'        => $block[ 'get_in_touch_image' ],
                    );
                }
            }
        }
        $response->data[ 'acf' ][ 'get_in_touch' ] = $data;
    } else {
        $information                               = $tools_guides[ 'tag_get_in_touch_section' ];
        $response->data[ 'acf' ][ 'get_in_touch' ] = $information;
    }

    return $response;
}

/**/
add_filter ( 'rest_prepare_our_insight', 'add_extra_field_our_insight_in_rest', 10, 3 );

function add_extra_field_our_insight_in_rest($response, $post, $request) {
    $post_id = $post->ID;

    $our_insight = get_field ( 'cpt_details', 'option' );

    $back_to_news                              = $our_insight[ 'insight_back_to_link' ];
    $response->data[ 'acf' ][ 'back_to_page' ] = $back_to_news;

    $social_sharing_title                      = $our_insight[ 'insight_share_this_title' ];
    $response->data[ 'acf' ][ 'social_title' ] = $social_sharing_title;

    $post_title = urlencode ( $post->post_title );
    $post_url   = urlencode ( get_permalink ( $post_id ) );

    $args = array (
        'post_type'      => 'our_insight',
        'posts_per_page' => -1,
        'orderby'        => 'ID',
        'order'          => 'ASC',
        'fields'         => 'ids'
    );

    $posts         = get_posts ( $args );
    $current_index = array_search ( $post_id, $posts );

    $next_post_id     = isset ( $posts[ $current_index + 1 ] ) ? $posts[ $current_index + 1 ] : null;
    $previous_post_id = isset ( $posts[ $current_index - 1 ] ) ? $posts[ $current_index - 1 ] : null;

    $next_post     = $next_post_id ? get_post ( $next_post_id ) : null;
    $previous_post = $previous_post_id ? get_post ( $previous_post_id ) : null;

    $next_perv_label = $our_insight[ 'insight_np_button_title' ];

    $response->data[ 'acf' ][ 'next_post' ]     = $next_post ? [
        'id'    => $next_post->ID,
        'name'  => $next_post->post_name,
        'label' => $next_perv_label[ 'next_button_title' ],
        'url'   => get_permalink ( $next_post->ID )
            ] : null;
    $response->data[ 'acf' ][ 'previous_post' ] = $previous_post ? [
        'id'     => $previous_post->ID,
        'name'   => $previous_post->post_name,
        'laabel' => $next_perv_label[ 'previous_button_title' ],
        'url'    => get_permalink ( $previous_post->ID )
            ] : null;

    // Replace image ID with image URL for featured media
    if( isset ( $response->data[ 'featured_media' ] ) ) {
        $media_id                           = $response->data[ 'featured_media' ];
        $media_url                          = wp_get_attachment_url ( $media_id );
        $response->data[ 'featured_media' ] = $media_url;
    }

    // Fetch related posts based on the news_category taxonomy
    $related_product_details                            = $our_insight[ 'insight_related_product_details' ];
    $response->data[ 'acf' ][ 'related_product_title' ] = $related_product_details[ 'insight_rel_product_title' ];
    $response->data[ 'acf' ][ 'product_button_label' ]  = $related_product_details[ 'insight_rel_product_btn_label' ];

    $related_article                                     = $our_insight[ 'insight_related_article_details' ];
    $response->data[ 'acf' ][ 'related_article_title' ]  = $related_article[ 'insight_related_title' ];
    $response->data[ 'acf' ][ 'related_article_button' ] = $related_article[ 'insight_view_all_button' ];
    $response->data[ 'acf' ][ 'article_button_label' ]   = $related_article[ 'insight_article_button_label' ];

    $categories = wp_get_post_terms ( $post_id, 'insight_category' );
    if( ! empty ( $categories ) && ! is_wp_error ( $categories ) ) {
        $category_ids = wp_list_pluck ( $categories, 'term_id' );

        // Get related posts (excluding the current post)
        $related_posts = new WP_Query ( array (
            'post_type'      => 'our_insight',
            'posts_per_page' => 4,
            'tax_query'      => array (
                array (
                    'taxonomy' => 'insight_category',
                    'field'    => 'term_id',
                    'terms'    => $category_ids,
                    'operator' => 'IN',
                ),
            ),
            'post__not_in'   => array ( $post_id ),
                ) );

        if( $related_posts->have_posts () ) {
            $related_posts_data = array ();
            while ( $related_posts->have_posts () ) {
                $related_posts->the_post ();
                $related_posts_data[] = [
                    'ID'          => get_the_ID (),
                    'title'       => get_the_title (),
                    'image'       => get_the_post_thumbnail_url ( get_the_ID (), 'full' ),
                    'date'        => get_the_date ( 'd.m.y', get_the_ID () ),
                    'description' => wp_trim_words ( get_the_content (), 40 ),
                    'link'        => get_permalink ( get_the_ID () ),
                ];
            }
            wp_reset_postdata (); // Reset the global post data
            $response->data[ 'acf' ][ 'related_insight' ] = $related_posts_data;
        }
    }

    $show_global_information = $our_insight[ 'global_insight_box_information' ];
    if( $show_global_information == 1 ) {
        $global_option = get_field ( 'global_flexible_listing', 'option' );
        $information   = array ();
        if( $global_option ) {
            foreach ( $global_option as $block ) {
                if( isset ( $block[ 'acf_fc_layout' ] ) && $block[ 'acf_fc_layout' ] == 'information_section' ) {
                    $data = $block[ 'informations' ];
                }
            }
        }
        $response->data[ 'acf' ][ 'information' ] = $data;
    } else {
        $information                              = $our_insight[ 'insight_box_information' ];
        $response->data[ 'acf' ][ 'information' ] = $information;
    }



    $show_global_information = $our_insight[ 'global_insight_get_in_touch' ];
    if( $show_global_information == 1 ) {
        $global_option = get_field ( 'global_flexible_listing', 'option' );
        $information   = array ();
        if( $global_option ) {
            foreach ( $global_option as $block ) {
                if( isset ( $block[ 'acf_fc_layout' ] ) && $block[ 'acf_fc_layout' ] == 'get_in_touch' ) {
                    $data = array (
                        'title'        => $block[ 'get_in_touch_title' ],
                        'form_details' => $block[ 'get_in_touch_form_details' ],
                        'image'        => $block[ 'get_in_touch_image' ],
                    );
                }
            }
        }
        $response->data[ 'acf' ][ 'get_in_touch' ] = $data;
    } else {
        $get_in_touch                              = $our_insight[ 'insight_get_in_touch' ];
        $response->data[ 'acf' ][ 'get_in_touch' ] = $get_in_touch;
    }

    return $response;
}

/**/
add_filter ( 'rest_prepare_inspiration', 'add_extra_field_inspiration_in_rest', 10, 3 );

function add_extra_field_inspiration_in_rest($response, $post, $request) {
    $post_id = $post->ID;

    $inspiration = get_field ( 'cpt_details', 'option' );

    $back_to_news                              = $inspiration[ 'inspiration_back_to_link' ];
    $response->data[ 'acf' ][ 'back_to_page' ] = $back_to_news;

    $social_sharing_title                      = $inspiration[ 'inspiration_share_this_title' ];
    $response->data[ 'acf' ][ 'social_title' ] = $social_sharing_title;

    $show_global_information = $inspiration[ 'global_inspiration_box_information' ];
    if( $show_global_information == 1 ) {
        $global_option = get_field ( 'global_flexible_listing', 'option' );
        $information   = array ();
        if( $global_option ) {
            foreach ( $global_option as $block ) {
                if( isset ( $block[ 'acf_fc_layout' ] ) && $block[ 'acf_fc_layout' ] == 'information_section' ) {
                    $data = $block[ 'informations' ];
                }
            }
        }
        $response->data[ 'acf' ][ 'information' ] = $data;
    } else {
        $information                              = $inspiration[ 'inspiration_box_information' ];
        $response->data[ 'acf' ][ 'information' ] = $information;
    }



    $show_global_information = $inspiration[ 'global_inspiration_get_in_touch' ];
    if( $show_global_information == 1 ) {
        $global_option = get_field ( 'global_flexible_listing', 'option' );
        $information   = array ();
        if( $global_option ) {
            foreach ( $global_option as $block ) {
                if( isset ( $block[ 'acf_fc_layout' ] ) && $block[ 'acf_fc_layout' ] == 'get_in_touch' ) {
                    $data = array (
                        'title'        => $block[ 'get_in_touch_title' ],
                        'form_details' => $block[ 'get_in_touch_form_details' ],
                        'image'        => $block[ 'get_in_touch_image' ],
                    );
                }
            }
        }
        $response->data[ 'acf' ][ 'get_in_touch' ] = $data;
    } else {
        $get_in_touch                              = $inspiration[ 'inspiration_get_in_touch' ];
        $response->data[ 'acf' ][ 'get_in_touch' ] = $get_in_touch;
    }

    return $response;
}

/**/
add_filter ( 'rest_prepare_sample', 'add_extra_field_sample_in_rest', 10, 3 );

function add_extra_field_sample_in_rest($response, $post, $request) {
    $post_id    = $post->ID;
    $post_title = get_the_title ( $post_id );

    $sample = get_field ( 'cpt_details', 'option' );

    $sample_form_details                                  = $sample[ 'sample_form_details' ];
    $response->data[ 'acf' ][ 'form_details' ]            = $sample_form_details;
    $response->data[ 'acf' ][ 'form_details' ][ 'title' ] = $sample_form_details[ 'title' ] . ' { ' . $post_title . ' }';

    $about_section = get_field ( 'about_show_from_global', $post_id );
    if( $about_section == 1 ) {
        $about_section_info                             = $sample[ 'sample_about_section' ];
        $response->data[ 'acf' ][ 'about_title' ]       = $about_section_info[ 'about_title' ] . ' { ' . $post_title . ' }';
        $response->data[ 'acf' ][ 'about_description' ] = $about_section_info[ 'about_description' ];
        $response->data[ 'acf' ][ 'about_image' ]       = $about_section_info[ 'about_image' ];
    } else {
        $response->data[ 'acf' ][ 'about_title' ]       = get_field ( 'about_title', $post_id ) . ' { ' . $post_title . ' }';
        $response->data[ 'acf' ][ 'about_description' ] = get_field ( 'about_description', $post_id );
        $response->data[ 'acf' ][ 'about_image' ]       = get_field ( 'about_image', $post_id );
    }

    $sample_information      = $sample[ 'sample_box_inform_section' ];
    $show_global_information = $sample_information[ 'show_box_information' ];
    if( $show_global_information == 1 ) {
        $global_option = get_field ( 'global_flexible_listing', 'option' );
        $information   = array ();
        if( $global_option ) {
            foreach ( $global_option as $block ) {
                if( isset ( $block[ 'acf_fc_layout' ] ) && $block[ 'acf_fc_layout' ] == 'information_section' ) {
                    $data = $block[ 'informations' ];
                }
            }
        }
        $response->data[ 'acf' ][ 'information' ] = $data;
    } else {
        $information                              = $sample_information[ 'informations' ];
        $response->data[ 'acf' ][ 'information' ] = $information;
    }
    return $response;
}

/**/
add_filter ( 'rest_prepare_case_study_sectors', 'add_extra_field_sector_in_rest', 10, 3 );

function add_extra_field_sector_in_rest($response, $post, $request) {
    $post_id = $post->ID;

    $sector = get_field ( 'cpt_details', 'option' );

    $back_to_news                              = $sector[ 'sector_back_to_link' ];
    $response->data[ 'acf' ][ 'back_to_page' ] = $back_to_news;

    $show_global_information = $sector[ 'global_sector_get_in_touch' ];
    if( $show_global_information == 1 ) {
        $global_option = get_field ( 'global_flexible_listing', 'option' );
        $information   = array ();
        if( $global_option ) {
            foreach ( $global_option as $block ) {
                if( isset ( $block[ 'acf_fc_layout' ] ) && $block[ 'acf_fc_layout' ] == 'get_in_touch' ) {
                    $data = array (
                        'title'        => $block[ 'get_in_touch_title' ],
                        'form_details' => $block[ 'get_in_touch_form_details' ],
                        'image'        => $block[ 'get_in_touch_image' ],
                    );
                }
            }
        }
        $response->data[ 'acf' ][ 'get_in_touch' ] = $data;
    } else {
        $get_in_touch                              = $sector[ 'sector_get_in_touch_section' ];
        $response->data[ 'acf' ][ 'get_in_touch' ] = $get_in_touch;
    }

    return $response;
}

/**/
add_filter ( 'rest_prepare_resource', 'add_extra_field_resource_in_rest', 10, 3 );

function add_extra_field_resource_in_rest($response, $post, $request) {
    $post_id = $post->ID;

    $resource = get_field ( 'cpt_details', 'option' );

    $back_to_news                              = $resource[ 'resource_back_to_link' ];
    $response->data[ 'acf' ][ 'back_to_page' ] = $back_to_news;

    $social_sharing_title                      = $resource[ 'resource_share_this_title' ];
    $response->data[ 'acf' ][ 'social_title' ] = $social_sharing_title;

    $related_article                                     = $resource[ 'resource_related_article_details' ];
    $response->data[ 'acf' ][ 'related_article_title' ]  = $related_article[ 'resource_related_title' ];
    $response->data[ 'acf' ][ 'related_article_button' ] = $related_article[ 'resource_view_all_button' ];

    $categories = wp_get_post_terms ( $post_id, 'resource_category' );
    if( ! empty ( $categories ) && ! is_wp_error ( $categories ) ) {
        $category_ids = wp_list_pluck ( $categories, 'term_id' );

        // Get related posts (excluding the current post)
        $related_posts = new WP_Query ( array (
            'post_type'      => 'resource',
            'posts_per_page' => 4,
            'tax_query'      => array (
                array (
                    'taxonomy' => 'resource_category',
                    'field'    => 'term_id',
                    'terms'    => $category_ids,
                    'operator' => 'IN',
                ),
            ),
            'post__not_in'   => array ( $post_id ),
                ) );

        if( $related_posts->have_posts () ) {
            $related_posts_data = array ();
            while ( $related_posts->have_posts () ) {
                $related_posts->the_post ();

                $data           = get_field ( 'cpt_details', 'option' );
                $link_label     = $data[ 'resource_section_link_label' ][ 'read_more_label' ];
                $download_label = $data[ 'resource_section_link_label' ][ 'download_label' ];
                $download_text  = $data[ 'resource_section_link_label' ][ 'download_text_label' ];

                $button_type = get_field ( 'resource_button_type', get_the_ID () );
                if( $button_type === 'Download' ) {
                    $resource_pdf  = get_field ( 'resource_pdf', get_the_ID () );
                    $button_url    = $resource_pdf ? $resource_pdf[ 'url' ] : '';
                    $button_label  = $download_label;
                    $date_or_label = $download_text;
                } else {
                    $button_url    = get_permalink ( get_the_ID () );
                    $button_label  = $link_label;
                    $date_or_label = get_the_date ( 'd.m.y', get_the_ID () );
                }

                $related_posts_data[] = [
                    'ID'            => get_the_ID (),
                    'image'         => get_the_post_thumbnail_url ( get_the_ID (), 'full' ),
                    'date_or_label' => $date_or_label,
                    'name'          => get_the_title (),
                    'description'   => wp_trim_words ( get_the_content (), 30 ),
                    'button_url'    => $button_url,
                    'button_label'  => $button_label,
                ];
            }
            wp_reset_postdata (); // Reset the global post data
            $response->data[ 'acf' ][ 'related_resource' ] = $related_posts_data;
        }
    }

    $show_global_information = $resource[ 'global_resource_box_information' ];
    if( $show_global_information == 1 ) {
        $global_option = get_field ( 'global_flexible_listing', 'option' );
        $information   = array ();
        if( $global_option ) {
            foreach ( $global_option as $block ) {
                if( isset ( $block[ 'acf_fc_layout' ] ) && $block[ 'acf_fc_layout' ] == 'information_section' ) {
                    $data = $block[ 'informations' ];
                }
            }
        }
        $response->data[ 'acf' ][ 'information' ] = $data;
    } else {
        $information                              = $resource[ 'resource_box_information' ];
        $response->data[ 'acf' ][ 'information' ] = $information;
    }



    $show_global_information = $resource[ 'global_resource_get_in_touch' ];
    if( $show_global_information == 1 ) {
        $global_option = get_field ( 'global_flexible_listing', 'option' );
        $information   = array ();
        if( $global_option ) {
            foreach ( $global_option as $block ) {
                if( isset ( $block[ 'acf_fc_layout' ] ) && $block[ 'acf_fc_layout' ] == 'get_in_touch' ) {
                    $data = array (
                        'title'        => $block[ 'get_in_touch_title' ],
                        'form_details' => $block[ 'get_in_touch_form_details' ],
                        'image'        => $block[ 'get_in_touch_image' ],
                    );
                }
            }
        }
        $response->data[ 'acf' ][ 'get_in_touch' ] = $data;
    } else {
        $get_in_touch                              = $resource[ 'resource_get_in_touch' ];
        $response->data[ 'acf' ][ 'get_in_touch' ] = $get_in_touch;
    }

    return $response;
}
