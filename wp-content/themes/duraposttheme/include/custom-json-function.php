<?php
add_action ( 'rest_api_init', function () {
    /* Header-footer */
    register_rest_route ( 'wp-api/v2', '/header-footer-json/', array (
        'methods'             => 'GET',
        'callback'            => 'header_footer_json',
        'permission_callback' => '__return_true' // Allows access to all users (for public data)
    ) );
    /* FAQ Listing */
    register_rest_route ( 'wp-api/v2', '/faq', array (
        'methods'  => 'GET',
        'callback' => 'get_faq_with_taxonomy',
    ) );
    /* Case Study Listing */
    register_rest_route ( 'wp-api/v2', '/case-studies', [
        'methods'  => 'GET',
        'callback' => 'get_case_studies_with_taxonomies',
        'args'     => [
            'collection' => [
                'validate_callback' => function ($param, $request, $key) {
                    return is_string ( $param );
                }
            ],
            'product'  => [
                'validate_callback' => function ($param, $request, $key) {
                    return is_string ( $param );
                }
            ],
            'sector' => [
                'validate_callback' => function ($param, $request, $key) {
                    return is_string ( $param );
                }
            ],
            'type' => [
                'validate_callback' => function ($param, $request, $key) {
                    return is_string ( $param );
                }
            ],
            'page'    => [
                'validate_callback' => function ($param, $request, $key) {
                    return is_numeric ( $param ) && $param > 0;
                },
                'default' => 1,
            ],
            'per_page' => [
                'validate_callback' => function ($param, $request, $key) {
                    return is_numeric ( $param ) && $param > 0;
                },
                'default'  => 12,
            ]
        ]
    ] );
    /* Tools & Guides Listing */
    register_rest_route ( 'wp-api/v2', '/tools-guides', [
        'methods'  => 'GET',
        'callback' => 'get_tools_and_guides_with_taxonomies',
        'args'     => [
            'sector' => [
                'validate_callback' => function ($param, $request, $key) {
                    return is_string ( $param );
                }
            ],
            'type'     => [
                'validate_callback' => function ($param, $request, $key) {
                    return is_string ( $param );
                }
            ],
            'page'    => [
                'validate_callback' => function ($param, $request, $key) {
                    return is_numeric ( $param ) && $param > 0;
                },
                'default' => 1,
            ],
            'per_page' => [
                'validate_callback' => function ($param, $request, $key) {
                    return is_numeric ( $param ) && $param > 0;
                },
                'default'  => 12,
            ]
        ]
    ] );
    /* Case Study Listing */
    register_rest_route ( 'wp-api/v2', '/our-insight', [
        'methods'  => 'GET',
        'callback' => 'get_our_insight_with_taxonomies',
        'args'     => [
            'tag' => [
                'validate_callback' => function ($param, $request, $key) {
                    return is_string ( $param );
                }
            ],
            'brand'    => [
                'validate_callback' => function ($param, $request, $key) {
                    return is_string ( $param );
                }
            ],
            'category' => [
                'validate_callback' => function ($param, $request, $key) {
                    return is_string ( $param );
                }
            ],
            'sector' => [
                'validate_callback' => function ($param, $request, $key) {
                    return is_string ( $param );
                }
            ],
            'type' => [
                'validate_callback' => function ($param, $request, $key) {
                    return is_string ( $param );
                }
            ],
            'page'    => [
                'validate_callback' => function ($param, $request, $key) {
                    return is_numeric ( $param ) && $param > 0;
                },
                'default' => 1,
            ],
            'per_page' => [
                'validate_callback' => function ($param, $request, $key) {
                    return is_numeric ( $param ) && $param > 0;
                },
                'default'  => 12,
            ]
        ]
    ] );
    /**/
    register_rest_route ( 'wp-api/v2', '/inspiration', [
        'methods'  => 'GET',
        'callback' => 'get_inspiration_with_taxonomies',
        'args'     => [
            'category' => [
                'validate_callback' => function ($param, $request, $key) {
                    return is_string ( $param );
                }
            ],
            'page'     => [
                'validate_callback' => function ($param, $request, $key) {
                    return is_numeric ( $param ) && $param > 0;
                },
                'default'  => 1,
            ],
            'per_page' => [
                'validate_callback' => function ($param, $request, $key) {
                    return is_numeric ( $param ) && $param > 0;
                },
                'default'  => 12,
            ]
        ]
    ] );
    /* Sample Listing */
    register_rest_route ( 'wp-api/v2', '/sample', [
        'methods'  => 'GET',
        'callback' => 'get_sample_with_taxonomies',
        'args'     => [
            'range' => [
                'validate_callback' => function ($param, $request, $key) {
                    return is_string ( $param );
                }
            ],
            'material' => [
                'validate_callback' => function ($param, $request, $key) {
                    return is_string ( $param );
                }
            ],
            'page'    => [
                'validate_callback' => function ($param, $request, $key) {
                    return is_numeric ( $param ) && $param > 0;
                },
                'default' => 1,
            ],
            'per_page' => [
                'validate_callback' => function ($param, $request, $key) {
                    return is_numeric ( $param ) && $param > 0;
                },
                'default'  => 12,
            ]
        ]
    ] );
    /*  */
    register_rest_route ( 'wp-api/v2', '/sector', array (
        'methods'  => 'GET',
        'callback' => 'get_sector_taxonomy',
    ) );
    /**/
    register_rest_route ( 'wp-api/v2', '/sector-details', array (
        'methods'  => 'GET',
        'callback' => 'get_sector_inner_details',
        'args'     => [
            'slug' => [
                'validate_callback' => function ($param, $request, $key) {
                    return is_string ( $param );
                }
            ],
        ]
    ) );
    /**/
    register_rest_route ( 'wp-api/v2', '/resource', [
        'methods'  => 'GET',
        'callback' => 'get_resource_with_taxonomies',
        'args'     => [
            'category' => [
                'validate_callback' => function ($param, $request, $key) {
                    return is_string ( $param );
                }
            ],
            'page'     => [
                'validate_callback' => function ($param, $request, $key) {
                    return is_numeric ( $param ) && $param > 0;
                },
                'default'  => 1,
            ],
            'per_page' => [
                'validate_callback' => function ($param, $request, $key) {
                    return is_numeric ( $param ) && $param > 0;
                },
                'default'  => 12,
            ]
        ]
    ] );
} );

function header_footer_json() {
    $headerParsedMenu = [];
    /* Top Menu */
    $site_url         = site_url () . '/';
    $top_menu_items   = wp_get_nav_menu_items ( 'Top Menu' );
    if( is_array ( $top_menu_items ) || is_object ( $top_menu_items ) ) {
        foreach ( $top_menu_items as $item ) {
            if( $item->menu_item_parent == 0 ) {
                $topmenu_details[ $item->ID ] = [
                    'title' => $item->title,
                    'url'   => str_replace ( $site_url, '/', $item->url )
                ];
            } else {
                $parent_id = $item->menu_item_parent;
                if( isset ( $topmenu_details[ $parent_id ] ) ) {
                    $topmenu_details[ $parent_id ][ 'children' ][] = [
                        'id'    => $item->ID,
                        'title' => $item->title,
                        'url'   => str_replace ( $site_url, '/', $item->url )
                    ];
                }
            }
        }
        // Add to the final parsed menu array
        $headerParsedMenu[ 'top_menu' ] = array_values ( $topmenu_details );
    } else {
        $headerParsedMenu[ 'top_menu' ] = []; // Return an empty array if no menu items
    }

    /* Header */
    $logo                       = get_field ( 'logo', 'option' );
    $logo_url                   = $logo[ 'logo' ];
    $headerParsedMenu[ 'logo' ] = $logo_url;

    $showTrustpilot                        = get_field ( 'review', 'option' );
    $headerParsedMenu[ 'show_trustpilot' ] = $showTrustpilot;

    $allIcons                           = get_field ( 'icon_details', 'option' );
    $headerParsedMenu[ 'icon_details' ] = $allIcons;

    $country_details         = get_field ( 'country_details', 'option' );
    $updated_country_details = [];
    foreach ( $country_details as $index => $country ) {
        $country[ 'country_id' ]   = $index + 1; // This gives 1, 2, 3, ...
        $updated_country_details[] = $country;
    }
    $headerParsedMenu[ 'country_details' ] = $updated_country_details;

    $menu_items = wp_get_nav_menu_items ( 'Header Menu' );
    if( is_array ( $menu_items ) || is_object ( $menu_items ) ) {
        foreach ( $menu_items as $item ) {
            if( $item->menu_item_parent == 0 ) {
                $headermenu_details[ $item->ID ] = [
                    'title' => $item->title,
                    'url'   => str_replace ( $site_url, '/', $item->url )
                ];
            } else {
                $parent_id = $item->menu_item_parent;
                if( isset ( $headermenu_details[ $parent_id ] ) ) {
                    $headermenu_details[ $parent_id ][ 'children' ][] = [
                        'id'    => $item->ID,
                        'title' => $item->title,
                        'url'   => str_replace ( $site_url, '/', $item->url )
                    ];
                }
            }
        }
        // Add to the final parsed menu array
        $headerParsedMenu[ 'header_menu' ] = array_values ( $headermenu_details );
    } else {
        $headerParsedMenu[ 'header_menu' ] = []; // Return an empty array if no menu items
    }
    /* Json header */
    $json_data_header = json_encode ( $headerParsedMenu );

    $json_file_path_header = ABSPATH . 'wp-content/uploads/sites/2/api/header.json';

    if( file_put_contents ( $json_file_path_header, $json_data_header ) ) {
        
    } else {
        error_log ( 'Error creating JSON file: ' . json_last_error_msg () );
    }

    /* Footer */
    $footerParsedMenu = [];

    $footerLogo                        = $logo[ 'white_logo' ];
    $footerParsedMenu[ 'footer_logo' ] = $footerLogo;

    $phoneNo                        = get_field ( 'phone_no', 'option' );
    $footerParsedMenu[ 'phone_no' ] = $phoneNo;

    $socialInfo                            = get_field ( 'contact_details', 'option' );
    $footerParsedMenu[ 'contact_details' ] = $socialInfo;

    $footerExtraLogo                   = get_field ( 'extra_image', 'option' );
    $footerParsedMenu[ 'extra_image' ] = $footerExtraLogo;

    //Footer menu   Useful Links
    $footertMenuTitles                      = get_field ( 'menu_label_list', 'option' );
    $aboutMenuTitle                         = $footertMenuTitles[ 'menu_1_label' ];
    $footerParsedMenu[ 'about_menu_label' ] = $aboutMenuTitle;
    $aboutMenu                              = wp_get_nav_menu_items ( 'About Menu' );
    if( $aboutMenu ) {
        foreach ( $aboutMenu as $menus ) {
            $aboutMenu_details[] = array (
                "title" => $menus->title,
                "url"   => str_replace ( $site_url, '/', $menus->url )
            );
        }
        $footerParsedMenu[ 'about_menu' ] = $aboutMenu_details;
    } else {
        $footerParsedMenu[ 'about_menu' ] = [];
    }
    $productMenuTitle                         = $footertMenuTitles[ 'menu_2_label' ];
    $footerParsedMenu[ 'product_menu_label' ] = $productMenuTitle;
    $productMenu                              = wp_get_nav_menu_items ( 'Product Menu' );
    if( $productMenu ) {
        foreach ( $productMenu as $menus ) {
            $productMenu_details[] = array (
                "title" => $menus->title,
                "url"   => str_replace ( $site_url, '/', $menus->url )
            );
        }
        $footerParsedMenu[ 'product_menu' ] = $productMenu_details;
    } else {
        $footerParsedMenu[ 'product_menu' ] = [];
    }
    $tradeMenuTitle                         = $footertMenuTitles[ 'menu_3_label' ];
    $footerParsedMenu[ 'trade_menu_label' ] = $tradeMenuTitle;
    $tradeMenu                              = wp_get_nav_menu_items ( 'Trade Menu' );
    if( $tradeMenu ) {
        foreach ( $tradeMenu as $menus ) {
            $tradeMenu_details[] = array (
                "title" => $menus->title,
                "url"   => str_replace ( $site_url, '/', $menus->url )
            );
        }
        $footerParsedMenu[ 'trade_menu' ] = $tradeMenu_details;
    } else {
        $footerParsedMenu[ 'trade_menu' ] = [];
    }
    $tool_guidesMenuTitle                         = $footertMenuTitles[ 'menu_4_label' ];
    $footerParsedMenu[ 'tool_guides_menu_label' ] = $tool_guidesMenuTitle;
    $tool_guidesMenu                              = wp_get_nav_menu_items ( 'Tools & Guides Menu' );
    if( $tool_guidesMenu ) {
        foreach ( $tool_guidesMenu as $menus ) {
            $tool_guidesMenu_details[] = array (
                "title" => $menus->title,
                "url"   => str_replace ( $site_url, '/', $menus->url )
            );
        }
        $footerParsedMenu[ 'tool_guides_menu' ] = $tool_guidesMenu_details;
    } else {
        $footerParsedMenu[ 'tool_guides_menu' ] = [];
    }
    $legalMenuTitle                         = $footertMenuTitles[ 'menu_5_label' ];
    $footerParsedMenu[ 'legal_menu_label' ] = $legalMenuTitle;
    $legalMenu                              = wp_get_nav_menu_items ( 'Legal Menu' );
    if( $legalMenu ) {
        foreach ( $legalMenu as $menus ) {
            $legalMenu_details[] = array (
                "title" => $menus->title,
                "url"   => str_replace ( $site_url, '/', $menus->url )
            );
        }
        $footerParsedMenu[ 'legal_menu' ] = $legalMenu_details;
    } else {
        $footerParsedMenu[ 'legal_menu' ] = [];
    }
    $ideaMenuTitle                         = $footertMenuTitles[ 'menu_6_label' ];
    $footerParsedMenu[ 'idea_menu_label' ] = $ideaMenuTitle;
    $ideaMenu                              = wp_get_nav_menu_items ( 'Inspiration & Ideas Menu' );
    if( $ideaMenu ) {
        foreach ( $ideaMenu as $menus ) {
            $ideaMenu_details[] = array (
                "title" => $menus->title,
                "url"   => str_replace ( $site_url, '/', $menus->url )
            );
        }
        $footerParsedMenu[ 'idea_menu' ] = $ideaMenu_details;
    } else {
        $footerParsedMenu[ 'idea_menu' ] = [];
    }

    $insta_info                         = get_field ( 'instagram_details', 'option' );
    $instaTitle                         = $insta_info [ 'account_name' ];
    $footerParsedMenu[ 'account_name' ] = $instaTitle;
    $instaLink                          = $insta_info [ 'account_url' ];
    $footerParsedMenu[ 'account_url' ]  = $instaLink;

    $birkdale_group                         = get_field ( 'birkdale_group', 'option' );
    $group_details                          = $birkdale_group[ 'group_details' ];
    $footerParsedMenu[ 'group_details' ]    = $group_details;
    $retailer_button                        = $birkdale_group[ 'retailer_button' ];
    $footerParsedMenu[ 'retailer_button' ]  = $retailer_button;
    $installer_button                       = $birkdale_group[ 'installer_button' ];
    $footerParsedMenu[ 'installer_button' ] = $installer_button;

    $copyrights                          = get_field ( 'copyrights', 'option' );
    $copy_right                          = $copyrights[ 'copyrights_text' ];
    $footerParsedMenu[ 'copyrights' ]    = $copy_right;
    $paymentImage                        = $copyrights[ 'payment_image' ];
    $footerParsedMenu[ 'payment_image' ] = $paymentImage;

    /* Json Footer */
    $json_data_footer      = json_encode ( $footerParsedMenu );
    $json_file_path_footer = ABSPATH . 'wp-content/uploads/sites/2/api/footer.json';

    if( file_put_contents ( $json_file_path_footer, $json_data_footer ) ) {
        // Success message (optional)
    } else {
        // Error message (handle file writing issues)
        error_log ( 'Error creating JSON file: ' . json_last_error_msg () );
    }


    return [
        'header' => $headerParsedMenu,
        'footer' => $footerParsedMenu
    ];
}

function get_faq_with_taxonomy() {
    $taxonomies      = get_object_taxonomies ( 'faq', 'names' );
    $taxonomies_data = [];
    foreach ( $taxonomies as $taxonomy ) {
        $terms = get_terms ( [
            'taxonomy'   => $taxonomy,
            'orderby'    => 'name',
            'order'      => 'ASC',
            'hide_empty' => false,
                ] );

        if( ! is_wp_error ( $terms ) && ! empty ( $terms ) ) {
            $taxonomies_data[ $taxonomy ] = array_map ( function ($term) {
                return [
                    'term_id' => $term->term_id,
                    'label'    => $term->name,
                    'value'    => $term->slug,
                ];
            }, $terms );
        }
    }

    $args       = [
        'post_type'      => 'faq',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
    ];
    $faqs_query = new WP_Query ( $args );
    $faqs_posts = [];

    $faq_category_terms = get_terms ( [
        'taxonomy'   => 'faq_category',
        'orderby'    => 'name',
        'order'      => 'ASC',
        'hide_empty' => false,
            ] );

    if( $faqs_query->have_posts () ) {
        foreach ( $faq_category_terms as $term ) {
            $faqs_posts[ $term->slug ] = [];
        }
        while ( $faqs_query->have_posts () ) {
            $faqs_query->the_post ();

            $terms = get_the_terms ( get_the_ID (), 'faq_category' );
            if( $terms && ! is_wp_error ( $terms ) ) {
                foreach ( $terms as $term ) {
                    $choose_link_video = get_field ( 'choose_link_video', get_the_ID () );
                    $video             = '';
                    $link_label        = '';
                    $pdf               = '';

                    if( $choose_link_video == 'Video' ) {
                        $video = get_field ( 'video', get_the_ID () );
                    } else if( $choose_link_video == 'Link' ) {
                        $link_label = get_field ( 'link_label', get_the_ID () );
                        $pdf        = get_field ( 'pdf', get_the_ID () );
                    } else {
                        $video      = '';
                        $link_label = '';
                        $pdf        = '';
                    }
                    $faqs_posts[ $term->slug ][] = [
                        'ID'       => get_the_ID (),
                        'question' => get_the_title (),
                        'answer'   => get_the_content (),
                        'video'    => $video ? $video : null,
                        'link'     => $link_label ? $link_label : null,
                        'pdf'      => $pdf ? $pdf : null
                    ];
                }
            }
        }
    }

    // Return both taxonomies and posts grouped by category
    return [
        'taxonomies' => $taxonomies_data,
        'faqs_posts' => $faqs_posts,
    ];
}

function get_case_studies_with_taxonomies($data) {
    // Get the taxonomy filters
    $collection = isset ( $data[ 'collection' ] ) ? $data[ 'collection' ] : '';
    $product    = isset ( $data[ 'product' ] ) ? $data[ 'product' ] : '';
    $sector     = isset ( $data[ 'sector' ] ) ? $data[ 'sector' ] : '';
    $type       = isset ( $data[ 'type' ] ) ? $data[ 'type' ] : '';
    $page       = isset ( $data[ 'page' ] ) ? (int) $data[ 'page' ] : 1;
    $per_page   = isset ( $data[ 'per_page' ] ) ? (int) $data[ 'per_page' ] : 12;

    $collections     = $collection ? explode ( ',', $collection ) : [];
    $products        = $product ? explode ( ',', $product ) : [];
    $sectors         = $sector ? explode ( ',', $sector ) : [];
    $types           = $type ? explode ( ',', $type ) : [];
    // Get all the taxonomies for the custom post type 'case study'
    $taxonomies      = get_object_taxonomies ( 'case_study', 'names' );
    $taxonomies_data = [];

    // Loop through each taxonomy and get terms
    foreach ( $taxonomies as $taxonomy ) {
        $terms = get_terms ( [
            'taxonomy'   => $taxonomy,
            'orderby'    => 'name',
            'order'      => 'ASC',
            'hide_empty' => false,
                ] );

        if( ! is_wp_error ( $terms ) && ! empty ( $terms ) ) {
            $taxonomies_data[ $taxonomy ] = array_map ( function ($term) {
                return [
                    'term_id' => $term->term_id,
                    'label'    => $term->name,
                    'value'    => $term->slug,
                ];
            }, $terms );
        }
    }

    // Prepare the arguments for the WP_Query
    $args      = [
        'post_type'      => 'case_study',
        'posts_per_page' => $per_page,
        'paged'          => $page,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'post_status'    => 'publish',
    ];
    $tax_query = [];
    if( $collection ) {
        $tax_query[] = [
            'taxonomy' => 'case_study_collections',
            'field'    => 'slug',
            'terms'    => $collections,
            'operator' => 'IN',
        ];
    }

    if( $product ) {
        $tax_query[] = [
            'taxonomy' => 'case_study_products',
            'field'    => 'slug',
            'terms'    => $products,
            'operator' => 'IN',
        ];
    }

    if( $sector ) {
        $tax_query[] = [
            'taxonomy' => 'case_study_sectors',
            'field'    => 'slug',
            'terms'    => $sectors,
            'operator' => 'IN',
        ];
    }
    if( $type ) {
        $tax_query[] = [
            'taxonomy' => 'case_study_type',
            'field'    => 'slug',
            'terms'    => $types,
            'operator' => 'IN',
        ];
    }

    if( ! empty ( $tax_query ) ) {
        $args[ 'tax_query' ] = [
            'relation' => 'AND',
            ...$tax_query
        ];
    }
    // Execute the query
    $case_study_query = new WP_Query ( $args );
    $case_study_posts = [];

    if( $case_study_query->have_posts () ) {
        $posts_data = [];
        while ( $case_study_query->have_posts () ) {
            $case_study_query->the_post ();
            $posts_data[] = [
                'ID'          => get_the_ID (),
                'image'       => get_the_post_thumbnail_url ( get_the_ID (), 'full' ),
                'date'        => get_the_date ( 'd.m.y', get_the_ID () ),
                'title'       => get_the_title (),
                'description' => wp_trim_words ( get_the_content (), 40 ),
                'link'        => get_permalink ( get_the_ID () ),
            ];
        }
        $case_study_posts = $posts_data;
    }
    $total_pages = $case_study_query->max_num_pages;
    $total_posts = $case_study_query->found_posts;
    // Return both taxonomies and posts as a response
    return [
        'taxonomies'       => $taxonomies_data,
        'case_study_posts' => $case_study_posts,
        'pagination'       => [
            'current_page'   => $page,
            'total_pages'    => $total_pages,
            'posts_per_page' => $per_page,
            'total_posts'    => $total_posts,
        ]
    ];
}

function get_tools_and_guides_with_taxonomies($data) {
    // Get the taxonomy filters
    $type     = isset ( $data[ 'type' ] ) ? $data[ 'type' ] : '';
    $sector   = isset ( $data[ 'sector' ] ) ? $data[ 'sector' ] : '';
    $page     = isset ( $data[ 'page' ] ) ? (int) $data[ 'page' ] : 1;
    $per_page = isset ( $data[ 'per_page' ] ) ? (int) $data[ 'per_page' ] : 12;

    $sectors         = $sector ? explode ( ',', $sector ) : [];
    $types           = $type ? explode ( ',', $type ) : [];
    // Get all the taxonomies for the custom post type 'case study'
    $taxonomies      = get_object_taxonomies ( 'tools_guide', 'names' );
    $taxonomies_data = [];

    // Loop through each taxonomy and get terms
    foreach ( $taxonomies as $taxonomy ) {
        $terms = get_terms ( [
            'taxonomy'   => $taxonomy,
            'orderby'    => 'name',
            'order'      => 'ASC',
            'hide_empty' => false,
                ] );

        if( ! is_wp_error ( $terms ) && ! empty ( $terms ) ) {
            $taxonomies_data[ $taxonomy ] = array_map ( function ($term) {
                return [
                    'term_id' => $term->term_id,
                    'label'    => $term->name,
                    'value'    => $term->slug,
                ];
            }, $terms );
        }
    }

    // Prepare the arguments for the WP_Query
    $args      = [
        'post_type'      => 'tools_guide',
        'posts_per_page' => $per_page,
        'paged'          => $page,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'post_status'    => 'publish',
    ];
    $tax_query = [];

    if( $type ) {
        $tax_query[] = [
            'taxonomy' => 'tools_guide_type',
            'field'    => 'slug',
            'terms'    => $types,
            'operator' => 'IN',
        ];
    }

    if( $sector ) {
        $tax_query[] = [
            'taxonomy' => 'tools_guide_sector',
            'field'    => 'slug',
            'terms'    => $sectors,
            'operator' => 'IN',
        ];
    }

    if( ! empty ( $tax_query ) ) {
        $args[ 'tax_query' ] = [
            'relation' => 'AND',
            ...$tax_query
        ];
    }
    // Execute the query
    $tool_and_guide_query = new WP_Query ( $args );
    $tool_and_guide_posts = [];

    if( $tool_and_guide_query->have_posts () ) {
        $posts_data = [];
        while ( $tool_and_guide_query->have_posts () ) {
            $tool_and_guide_query->the_post ();
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

            $posts_data[] = [
                'ID'           => $post_id,
                'image'        => get_the_post_thumbnail_url ( $post_id, 'full' ),
                'title'        => get_the_title (),
                'description'  => wp_trim_words ( get_the_content (), 40 ),
                'video_link'   => $video_link,
                'button_label' => $link_label,
                'button_link'  => $link_url,
            ];
        }
        $tool_and_guide_posts = $posts_data;
    }
    $total_pages = $tool_and_guide_query->max_num_pages;
    $total_posts = $tool_and_guide_query->found_posts;
    // Return both taxonomies and posts as a response
    return [
        'taxonomies'           => $taxonomies_data,
        'tool_and_guide_posts' => $tool_and_guide_posts,
        'pagination'           => [
            'current_page'   => $page,
            'total_pages'    => $total_pages,
            'posts_per_page' => $per_page,
            'total_posts'    => $total_posts,
        ]
    ];
}

function get_our_insight_with_taxonomies($data) {
    // Get the taxonomy filters
    $tag      = isset ( $data[ 'tag' ] ) ? $data[ 'tag' ] : '';
    $brand    = isset ( $data[ 'brand' ] ) ? $data[ 'brand' ] : '';
    $category = isset ( $data[ 'category' ] ) ? $data[ 'category' ] : '';
    $sector   = isset ( $data[ 'sector' ] ) ? $data[ 'sector' ] : '';
    $type     = isset ( $data[ 'type' ] ) ? $data[ 'type' ] : '';
    $page     = isset ( $data[ 'page' ] ) ? (int) $data[ 'page' ] : 1;
    $per_page = isset ( $data[ 'per_page' ] ) ? (int) $data[ 'per_page' ] : 12;

    $brands     = $brand ? explode ( ',', $brand ) : [];
    $categories = $category ? explode ( ',', $category ) : [];
    $sectors    = $sector ? explode ( ',', $sector ) : [];
    $types      = $type ? explode ( ',', $type ) : [];

    // Get all the taxonomies for the custom post type 'case study'
    $taxonomies      = get_object_taxonomies ( 'our_insight', 'names' );
    $taxonomies_data = [];

    $tags_data = [];
    $tags      = get_terms ( [
        'taxonomy'   => 'insight_tag',
        'orderby'    => 'name',
        'order'      => 'ASC',
        'hide_empty' => false,
            ] );

    if( ! is_wp_error ( $tags ) && ! empty ( $tags ) ) {
        $tags_data = array_map ( function ($term) {
            return [
                'term_id' => $term->term_id,
                'name'    => $term->name,
                'slug'    => $term->slug,
            ];
        }, $tags );
    }

    // Loop through each taxonomy and get terms
    foreach ( $taxonomies as $taxonomy ) {
        if( 'insight_tag' === $taxonomy ) {
            continue; // Skip the default 'post_tag'
        }
        $terms = get_terms ( [
            'taxonomy'   => $taxonomy,
            'orderby'    => 'name',
            'order'      => 'ASC',
            'hide_empty' => false,
                ] );

        if( ! is_wp_error ( $terms ) && ! empty ( $terms ) ) {
            $taxonomies_data[ $taxonomy ] = array_map ( function ($term) {
                return [
                    'term_id' => $term->term_id,
                    'label'    => $term->name,
                    'value'    => $term->slug,
                ];
            }, $terms );
        }
    }

    // Prepare the arguments for the WP_Query
    $args      = [
        'post_type'      => 'our_insight',
        'posts_per_page' => $per_page,
        'paged'          => $page,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'post_status'    => 'publish',
    ];
    $tax_query = [];

    if( $tag ) {
        $tax_query[] = [
            'taxonomy' => 'insight_tag',
            'field'    => 'slug',
            'terms'    => $tag,
            'operator' => 'IN',
        ];
    }

    if( $brands ) {
        $tax_query[] = [
            'taxonomy' => 'insight_brand',
            'field'    => 'slug',
            'terms'    => $brands,
            'operator' => 'IN',
        ];
    }
    if( $category ) {
        $tax_query[] = [
            'taxonomy' => 'insight_category',
            'field'    => 'slug',
            'terms'    => $categories,
            'operator' => 'IN',
        ];
    }

    if( $brands ) {
        $tax_query[] = [
            'taxonomy' => 'insight_brand',
            'field'    => 'slug',
            'terms'    => $brands,
            'operator' => 'IN',
        ];
    }

    if( $sectors ) {
        $tax_query[] = [
            'taxonomy' => 'insight_sector',
            'field'    => 'slug',
            'terms'    => $sectors,
            'operator' => 'IN',
        ];
    }

    if( $types ) {
        $tax_query[] = [
            'taxonomy' => 'insight_type',
            'field'    => 'slug',
            'terms'    => $types,
            'operator' => 'IN',
        ];
    }

    if( ! empty ( $tax_query ) ) {
        $args[ 'tax_query' ] = [
            'relation' => 'AND', // Apply AND logic across all taxonomy filters
            ...$tax_query
        ];
    }

    // Execute the query
    $our_insight_query = new WP_Query ( $args );
    $our_insight_posts = [];

    if( $our_insight_query->have_posts () ) {
        $posts_data = [];
        while ( $our_insight_query->have_posts () ) {
            $our_insight_query->the_post ();
            $posts_data[] = [
                'ID'          => get_the_ID (),
                'image'       => get_the_post_thumbnail_url ( get_the_ID (), 'full' ),
                'date'        => get_the_date ( 'd.m.y', get_the_ID () ),
                'title'       => get_the_title (),
                'description' => wp_trim_words ( get_the_content (), 40 ),
                'link'        => get_permalink ( get_the_ID () ),
            ];
        }
        $our_insight_posts = $posts_data;
    }
    $total_pages = $our_insight_query->max_num_pages;
    $total_posts = $our_insight_query->found_posts;
    // Return both taxonomies and posts as a response
    return [
        'tags'          => $tags_data,
        'taxonomies'    => $taxonomies_data,
        'insight_posts' => $our_insight_posts,
        'pagination'    => [
            'current_page'   => $page,
            'total_pages'    => $total_pages,
            'posts_per_page' => $per_page,
            'total_posts'    => $total_posts,
        ]
    ];
}

function get_inspiration_with_taxonomies($data) {
    // Get the taxonomy filters
    $category = isset ( $data[ 'category' ] ) ? $data[ 'category' ] : '';
    $page     = isset ( $data[ 'page' ] ) ? (int) $data[ 'page' ] : 1;
    $per_page = isset ( $data[ 'per_page' ] ) ? (int) $data[ 'per_page' ] : 12;

    $categories = $category ? explode ( ',', $category ) : [];

    $taxonomies_data = [];

    // Fetch taxonomy terms
    $taxonomy = get_terms ( [
        'taxonomy'   => 'inspiration_tax',
        'orderby'    => 'name',
        'order'      => 'ASC',
        'hide_empty' => false,
            ] );

    if( ! is_wp_error ( $taxonomy ) && ! empty ( $taxonomy ) ) {
        $taxonomies_data[] = [
            'term_id' => 0,
            'label'    => 'All',
            'value'    => 'all',
        ];

        $taxonomies_data = array_merge ( $taxonomies_data, array_map ( function ($term) {
                    return [
                        'term_id' => $term->term_id,
                        'label'    => $term->name,
                        'value'    => $term->slug,
                    ];
                }, $taxonomy ) );
    }

    $args = [
        'post_type'      => 'inspiration',
        'posts_per_page' => $per_page,
        'paged'          => $page,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'post_status'    => 'publish',
    ];

    $tax_query = [];

    if( $category && $category !== 'all' ) {
        $tax_query[] = [
            'taxonomy' => 'inspiration_tax',
            'field'    => 'slug',
            'terms'    => $categories,
            'operator' => 'IN',
        ];
    }

    if( ! empty ( $tax_query ) ) {
        $args[ 'tax_query' ] = [
            'relation' => 'AND',
            ...$tax_query
        ];
    }

    // Execute the query
    $inspiration_query = new WP_Query ( $args );
    $inspiration_posts = [];

    if( $inspiration_query->have_posts () ) {
        $latest_post      = null;
        $small_image_post = null;
        $remaining_posts  = [];

        while ( $inspiration_query->have_posts () ) {
            $inspiration_query->the_post ();
            $post_id             = get_the_ID ();
            $feature_image       = get_field ( 'listing_page_image', $post_id );
            $feature_image_style = $feature_image[ 'feature_image_style' ];
            $feature_image_url   = $feature_image[ 'image' ];
            $global_button_label = get_field ( 'cpt_details', 'option' );
            $button_label        = $global_button_label[ 'inspirtaion_lp_button_label' ];

            $post_data = [
                'ID'           => $post_id,
                'image'        => $feature_image_url,
                'description'  => wp_trim_words ( get_the_content (), 10, '' ),
                'button_label' => $button_label,
                'link'         => get_permalink (),
            ];

            if( ! $latest_post ) {
                $latest_post = $post_data;
            } elseif( $feature_image_style == 'Small Image' && ! $small_image_post ) {
                $small_image_post = $post_data;
            } else {
                $remaining_posts[] = $post_data;
            }
        }

        $inspiration_posts = [];
        if( $latest_post ) {
            $inspiration_posts[] = $latest_post;
        }
        if( $small_image_post ) {
            $inspiration_posts[] = $small_image_post;
        }
        $inspiration_posts = array_merge ( $inspiration_posts, $remaining_posts );
    }

    $total_pages = $inspiration_query->max_num_pages;
    $total_posts = $inspiration_query->found_posts;

    return [
        'taxonomies'        => $taxonomies_data,
        'inspiration_posts' => $inspiration_posts,
        'pagination'        => [
            'current_page'   => $page,
            'total_pages'    => $total_pages,
            'posts_per_page' => $per_page,
            'total_posts'    => $total_posts,
        ]
    ];
}

function get_sample_with_taxonomies($data) {
    // Get the taxonomy filters
    $range    = isset ( $data[ 'range' ] ) ? $data[ 'range' ] : '';
    $material = isset ( $data[ 'material' ] ) ? $data[ 'material' ] : '';
    $page     = isset ( $data[ 'page' ] ) ? (int) $data[ 'page' ] : 1;
    $per_page = isset ( $data[ 'per_page' ] ) ? (int) $data[ 'per_page' ] : 12;

    $ranges    = $range ? explode ( ',', $range ) : [];
    $materials = $material ? explode ( ',', $material ) : [];

    $taxonomies      = get_object_taxonomies ( 'sample', 'names' );
    $taxonomies_data = [];

    foreach ( $taxonomies as $taxonomy ) {
        $terms = get_terms ( [
            'taxonomy'   => $taxonomy,
            'orderby'    => 'name',
            'order'      => 'ASC',
            'hide_empty' => false,
                ] );

        if( ! is_wp_error ( $terms ) && ! empty ( $terms ) ) {
            $taxonomies_data[ $taxonomy ] = array_map ( function ($term) {
                return [
                    'term_id' => $term->term_id,
                    'label'    => $term->name,
                    'value'    => $term->slug,
                ];
            }, $terms );
        }
    }

    $args      = [
        'post_type'      => 'sample',
        'posts_per_page' => $per_page,
        'paged'          => $page,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'post_status'    => 'publish',
    ];
    $tax_query = [];

    if( $range ) {
        $tax_query[] = [
            'taxonomy' => 'sample_range',
            'field'    => 'slug',
            'terms'    => $ranges,
            'operator' => 'IN',
        ];
    }

    if( $material ) {
        $tax_query[] = [
            'taxonomy' => 'sample_material',
            'field'    => 'slug',
            'terms'    => $materials,
            'operator' => 'IN',
        ];
    }

    if( ! empty ( $tax_query ) ) {
        $args[ 'tax_query' ] = [
            'relation' => 'AND',
            ...$tax_query
        ];
    }
    // Execute the query
    $sample_query = new WP_Query ( $args );
    $sample_posts = [];

    if( $sample_query->have_posts () ) {
        $posts_data = [];
        while ( $sample_query->have_posts () ) {
            $sample_query->the_post ();
            $post_id = get_the_ID ();
            if( class_exists ( 'acf' ) ) {
                $retailer_link = get_field ( 'find_a_retailer_link', $post_id );
                $btn_labels    = get_field ( 'cpt_details', 'option' );
            }
            $posts_data[] = [
                'ID'                 => $post_id,
                'image'              => get_the_post_thumbnail_url ( $post_id, 'full' ),
                'title'              => get_the_title (),
                'order_sample_label' => $btn_labels[ 'sample_button_labels' ][ 'order_a_sample_label' ],
                'order_sample_link'  => get_permalink (),
                'reatiler_label'     => $btn_labels[ 'sample_button_labels' ][ 'find_a_retailer_label' ],
                'retailer_link'      => $retailer_link,
            ];
        }
        $sample_posts = $posts_data;
    }
    $total_pages = $sample_query->max_num_pages;
    $total_posts = $sample_query->found_posts;
    // Return both taxonomies and posts as a response
    return [
        'taxonomies'   => $taxonomies_data,
        'sample_posts' => $sample_posts,
        'pagination'   => [
            'current_page'   => $page,
            'total_pages'    => $total_pages,
            'posts_per_page' => $per_page,
            'total_posts'    => $total_posts,
        ]
    ];
}

function get_sector_taxonomy() {
    $taxonomy = 'case_study_sectors';
    $terms    = get_terms ( array (
        'taxonomy'   => $taxonomy,
        'hide_empty' => false,
            ) );

    if( ! empty ( $terms ) && ! is_wp_error ( $terms ) ) {
        $sector_list = array ();
        foreach ( $terms as $term ) {
            $option     = get_field ( 'cpt_details', 'option' );
            $link_label = $option[ 'sector_taxonomy_button_label' ];
            $image      = get_field ( 'case_study_sectors_image', 'term_' . $term->term_id );
            $image      = ! empty ( $image ) ? $image : '';
            $term_link  = get_term_link ( $term );

            $sector_list[] = array (
                'id'          => $term->term_id,
                'image'       => $image,
                'name'        => $term->name,
                'description' => $term->description,
                'link_label'  => $link_label,
                'link'        => $term_link,
            );
        }

        return $sector_list;
    }

    return false;
}

function get_sector_inner_details($data) {
    $slug = isset ( $data[ 'slug' ] ) ? $data[ 'slug' ] : '';

    if( empty ( $slug ) ) {
        return array ();
    }

    $args = array (
        'post_type'      => 'case_study',
        'posts_per_page' => -1,
        'tax_query'      => array (
            array (
                'taxonomy' => 'case_study_sectors',
                'field'    => 'slug',
                'terms'    => $slug,
                'operator' => 'IN',
            ),
        ),
    );

    $query = new WP_Query ( $args );

    if( $query->have_posts () ) {
        $posts = array ();

        while ( $query->have_posts () ) {
            $query->the_post ();

            $posts[] = array (
                'ID'          => get_the_ID (),
                'title'       => get_the_title (),
                'description' => wp_trim_words ( get_the_content (), 40, '' ),
                'image'       => get_the_post_thumbnail_url ( get_the_ID (), 'full' ),
                'permalink'   => get_permalink (),
            );
        }
        wp_reset_postdata ();

        return $posts;
    } else {
        return array ();
    }
}

function get_resource_with_taxonomies($data) {
    // Get the taxonomy filters
    $category = isset ( $data[ 'category' ] ) ? $data[ 'category' ] : '';
    $page     = isset ( $data[ 'page' ] ) ? (int) $data[ 'page' ] : 1;
    $per_page = isset ( $data[ 'per_page' ] ) ? (int) $data[ 'per_page' ] : 12;

    $categories = $category ? explode ( ',', $category ) : [];

    $taxonomies_data = [];

    // Fetch taxonomy terms
    $taxonomy = get_terms ( [
        'taxonomy'   => 'resource_category',
        'orderby'    => 'name',
        'order'      => 'ASC',
        'hide_empty' => false,
            ] );

    if( ! is_wp_error ( $taxonomy ) && ! empty ( $taxonomy ) ) {
        $taxonomies_data[] = [
            'term_id' => 0,
            'label'    => 'All',
            'value'    => 'all',
        ];

        $taxonomies_data = array_merge ( $taxonomies_data, array_map ( function ($term) {
                    return [
                        'term_id' => $term->term_id,
                        'label'    => $term->name,
                        'value'    => $term->slug,
                    ];
                }, $taxonomy ) );
    }

    $args = [
        'post_type'      => 'resource',
        'posts_per_page' => $per_page,
        'paged'          => $page,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'post_status'    => 'publish',
    ];

    $tax_query = [];

    if( $category && $category !== 'all' ) {
        $tax_query[] = [
            'taxonomy' => 'resource_category',
            'field'    => 'slug',
            'terms'    => $categories,
            'operator' => 'IN',
        ];
    }

    if( ! empty ( $tax_query ) ) {
        $args[ 'tax_query' ] = [
            'relation' => 'AND',
            ...$tax_query
        ];
    }

    // Execute the query
    $resource_query = new WP_Query ( $args );
    $resource_posts = [];

    if( $resource_query->have_posts () ) {

        while ( $resource_query->have_posts () ) {
            $resource_query->the_post ();
            $post_id = get_the_ID ();

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

            $resource_posts[]    = [
                'ID'            => get_the_ID (),
                'image'         => get_the_post_thumbnail_url ( get_the_ID (), 'full' ),
                'date_or_label' => $date_or_label,
                'name'          => get_the_title (),
                'description'   => wp_trim_words ( get_the_content (), 30 ),
                'button_url'    => $button_url,
                'button_label'  => $button_label,
            ];
            $resource_posts_list = $resource_posts;
        }
    }

    $total_pages = $resource_query->max_num_pages;
    $total_posts = $resource_query->found_posts;

    return [
        'taxonomies'     => $taxonomies_data,
        'resource_posts' => $resource_posts_list,
        'pagination'     => [
            'current_page'   => $page,
            'total_pages'    => $total_pages,
            'posts_per_page' => $per_page,
            'total_posts'    => $total_posts,
        ]
    ];
}
