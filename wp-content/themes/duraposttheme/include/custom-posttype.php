<?php
/* Custom Posttype */

function create_post_type() {
    /* Testimonial CPT */
    $testimonial_args = array (
        'labels'        => array (
            'name'               => 'Testimonials',
            'singular_name'      => 'Testimonial',
            'menu_name'          => 'Testimonials',
            'name_admin_bar'     => 'Testimonial',
            'add_new'            => 'Add New',
            'add_new_item'       => 'Add New Testimonial',
            'new_item'           => 'New Testimonial',
            'edit_item'          => 'Edit Testimonial',
            'view_item'          => 'View Testimonial',
            'all_items'          => 'All Testimonials',
            'search_items'       => 'Search Testimonials',
            'not_found'          => 'No testimonials found.',
            'not_found_in_trash' => 'No testimonials found in Trash.',
            'parent_item_colon'  => '',
            'all_items'          => 'All Testimonials',
        ),
        'public'        => true,
        'has_archive'   => true,
        'show_ui'       => true,
        'show_in_menu'  => true,
        'menu_position' => 10,
        'show_in_rest'  => true,
        'menu_icon'     => 'dashicons-testimonial',
        'supports'      => array ( 'title', 'editor', 'thumbnail' ),
        'taxonomies'    => array (),
        'hierarchical'  => false,
        'rewrite'       => array ( 'slug' => 'testimonials' ),
    );

    register_post_type ( 'testimonial', $testimonial_args );

    $case_study_args = array (
        'labels'        => array (
            'name'               => 'Case Studies',
            'singular_name'      => 'Case Study',
            'menu_name'          => 'Case Studies',
            'name_admin_bar'     => 'Case Study',
            'add_new'            => 'Add New',
            'add_new_item'       => 'Add New Case Study',
            'new_item'           => 'New Case Study',
            'edit_item'          => 'Edit Case Study',
            'view_item'          => 'View Case Study',
            'all_items'          => 'All Case Studies',
            'search_items'       => 'Search Case Studies',
            'not_found'          => 'No case studies found.',
            'not_found_in_trash' => 'No case studies found in Trash.',
            'parent_item_colon'  => '',
        ),
        'public'        => true,
        'has_archive'   => true,
        'show_ui'       => true,
        'show_in_menu'  => true,
        'menu_position' => 10,
        'show_in_rest'  => true,
        'menu_icon'     => 'dashicons-format-gallery',
        'supports'      => array ( 'title', 'editor', 'thumbnail', 'custom-fields' ),
        'taxonomies'    => array ( 'case_study_collections', 'case_study_products', 'case_study_sectors', 'case_study_type' ),
        'hierarchical'  => false,
        'rewrite'       => array ( 'slug' => 'case-studies' ),
    );
    register_post_type ( 'case_study', $case_study_args );

    // FAQ Post Type
    $faq_args = array (
        'labels'        => array (
            'name'               => 'FAQs',
            'singular_name'      => 'FAQ',
            'menu_name'          => 'FAQs',
            'name_admin_bar'     => 'FAQ',
            'add_new'            => 'Add New',
            'add_new_item'       => 'Add New FAQ',
            'new_item'           => 'New FAQ',
            'edit_item'          => 'Edit FAQ',
            'view_item'          => 'View FAQ',
            'all_items'          => 'All FAQs',
            'search_items'       => 'Search FAQs',
            'not_found'          => 'No FAQs found.',
            'not_found_in_trash' => 'No FAQs found in Trash.',
            'parent_item_colon'  => '',
        ),
        'public'        => true,
        'has_archive'   => true,
        'show_ui'       => true,
        'show_in_menu'  => true,
        'menu_position' => 10,
        'show_in_rest'  => true,
        'menu_icon'     => 'dashicons-format-chat',
        'supports'      => array ( 'title', 'editor' ),
        'taxonomies'    => array ( 'faq_category' ),
        'hierarchical'  => false,
        'rewrite'       => array ( 'slug' => 'faqs' ),
    );
    register_post_type ( 'faq', $faq_args );

    // Samples Post Type
    $sample_args = array (
        'labels'        => array (
            'name'               => 'Samples',
            'singular_name'      => 'Sample',
            'menu_name'          => 'Samples',
            'name_admin_bar'     => 'Sample',
            'add_new'            => 'Add New',
            'add_new_item'       => 'Add New Sample',
            'new_item'           => 'New Sample',
            'edit_item'          => 'Edit Sample',
            'view_item'          => 'View Sample',
            'all_items'          => 'All Samples',
            'search_items'       => 'Search Samples',
            'not_found'          => 'No samples found.',
            'not_found_in_trash' => 'No samples found in Trash.',
            'parent_item_colon'  => '',
        ),
        'public'        => true,
        'has_archive'   => true,
        'show_ui'       => true,
        'show_in_menu'  => true,
        'menu_position' => 10,
        'show_in_rest'  => true,
        'menu_icon'     => 'dashicons-art',
        'supports'      => array ( 'title', 'editor', 'thumbnail' ),
        'taxonomies'    => array ( 'sample_range', 'sample_material' ),
        'hierarchical'  => false,
        'rewrite'       => array ( 'slug' => 'samples' ),
    );
    register_post_type ( 'sample', $sample_args );

    // Tools & Guides Post Type
    $tools_guides_args = array (
        'labels'        => array (
            'name'               => 'Tools & Guides',
            'singular_name'      => 'Tool & Guide',
            'menu_name'          => 'Tools & Guides',
            'name_admin_bar'     => 'Tool & Guide',
            'add_new'            => 'Add New',
            'add_new_item'       => 'Add New Tool & Guide',
            'new_item'           => 'New Tool & Guide',
            'edit_item'          => 'Edit Tool & Guide',
            'view_item'          => 'View Tool & Guide',
            'all_items'          => 'All Tools & Guides',
            'search_items'       => 'Search Tools & Guides',
            'not_found'          => 'No tools & guides found.',
            'not_found_in_trash' => 'No tools & guides found in Trash.',
            'parent_item_colon'  => '',
        ),
        'public'        => true,
        'has_archive'   => true,
        'show_ui'       => true,
        'show_in_menu'  => true,
        'menu_position' => 10,
        'show_in_rest'  => true,
        'menu_icon'     => 'dashicons-welcome-learn-more',
        'supports'      => array ( 'title', 'editor', 'thumbnail' ),
        'taxonomies'    => array ( 'tools_guide_type', 'tools_guide_sector' ),
        'hierarchical'  => false,
        'rewrite'       => array ( 'slug' => 'tools-guides' ),
    );
    register_post_type ( 'tools_guide', $tools_guides_args );

    // Register Custom Post Type for Inspiration
    $inspiration_args = array (
        'labels'        => array (
            'name'               => 'Inspirations',
            'singular_name'      => 'Inspiration',
            'menu_name'          => 'Inspirations',
            'name_admin_bar'     => 'Inspiration',
            'add_new'            => 'Add New',
            'add_new_item'       => 'Add New Inspiration',
            'new_item'           => 'New Inspiration',
            'edit_item'          => 'Edit Inspiration',
            'view_item'          => 'View Inspiration',
            'all_items'          => 'All Inspirations',
            'search_items'       => 'Search Inspirations',
            'not_found'          => 'No Inspirations found.',
            'not_found_in_trash' => 'No Inspirations found in Trash.',
            'parent_item_colon'  => '',
        ),
        'public'        => true,
        'has_archive'   => true,
        'show_ui'       => true,
        'show_in_menu'  => true,
        'menu_position' => 10,
        'show_in_rest'  => true,
        'menu_icon'     => 'dashicons-lightbulb', // Icon for Inspiration CPT
        'supports'      => array ( 'title', 'editor', 'thumbnail' ),
        'taxonomies'    => array ( 'inspiration_tax' ), // Register taxonomy for this CPT
        'hierarchical'  => false,
        'rewrite'       => array ( 'slug' => 'inspirations' ),
    );
    register_post_type ( 'inspiration', $inspiration_args );

    $insight_args = array (
        'labels'        => array (
            'name'               => 'Our Insights',
            'singular_name'      => 'Our Insight',
            'menu_name'          => 'Our Insights',
            'name_admin_bar'     => 'Our Insight',
            'add_new'            => 'Add New',
            'add_new_item'       => 'Add New Insight',
            'new_item'           => 'New Insight',
            'edit_item'          => 'Edit Insight',
            'view_item'          => 'View Insight',
            'all_items'          => 'All Insights',
            'search_items'       => 'Search Insights',
            'not_found'          => 'No Insights found.',
            'not_found_in_trash' => 'No Insights found in Trash.',
            'parent_item_colon'  => '',
        ),
        'public'        => true,
        'has_archive'   => true,
        'show_ui'       => true,
        'show_in_menu'  => true,
        'menu_position' => 10,
        'show_in_rest'  => true,
        'menu_icon'     => 'dashicons-media-text',
        'supports'      => array ( 'title', 'editor', 'excerpt', 'thumbnail' ),
        'taxonomies'    => array ( 'insight_brand', 'insight_category', 'insight_sector', 'insight_type', 'insight_tag' ),
        'hierarchical'  => false,
        'rewrite'       => array ( 'slug' => 'our-insights' ),
    );

    register_post_type ( 'our_insight', $insight_args );

    $resource_args = array (
        'labels'        => array (
            'name'               => 'Resources',
            'singular_name'      => 'Resource',
            'menu_name'          => 'Resources',
            'name_admin_bar'     => 'Resource',
            'add_new'            => 'Add New',
            'add_new_item'       => 'Add New Resource',
            'new_item'           => 'New Resource',
            'edit_item'          => 'Edit Resource',
            'view_item'          => 'View Resource',
            'all_items'          => 'All Resources',
            'search_items'       => 'Search Resources',
            'not_found'          => 'No resources found.',
            'not_found_in_trash' => 'No resources found in Trash.',
            'parent_item_colon'  => '',
        ),
        'public'        => true,
        'has_archive'   => true,
        'show_ui'       => true,
        'show_in_menu'  => true,
        'menu_position' => 10,
        'show_in_rest'  => true,
        'menu_icon'     => 'dashicons-admin-tools',
        'supports'      => array ( 'title', 'editor', 'thumbnail' ),
        'taxonomies'    => array ( 'resource_category' ),
        'hierarchical'  => false,
        'rewrite'       => array ( 'slug' => 'resources' ),
    );

    register_post_type ( 'resource', $resource_args );

    // Installer
    $installer_args = array(
        'labels' => array(
            'name'                  => 'Installers',
            'singular_name'         => 'Installer',
            'menu_name'             => 'Installers',
            'name_admin_bar'        => 'Installer',
            'add_new'               => 'Add New',
            'add_new_item'          => 'Add New Installer',
            'new_item'              => 'New Installer',
            'edit_item'             => 'Edit Installer',
            'view_item'             => 'View Installer',
            'all_items'             => 'All Installers',
            'search_items'          => 'Search Installers',
            'not_found'             => 'No installers found.',
            'not_found_in_trash'    => 'No installers found in Trash.',
            'parent_item_colon'     => '',
        ),
        'public'                => true,
        'has_archive'           => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 20, // Adjust position if needed
        'show_in_rest'          => true,
        'menu_icon'             => 'dashicons-admin-users', // You can change this if needed
        'supports'              => array( 'title', 'editor', 'thumbnail' ),
        'taxonomies'            => array(),
        'hierarchical'          => false,
        'rewrite'               => array( 'slug' => 'installers' ),
    );

    register_post_type( 'installer', $installer_args );


    // Register Taxonomies for each Post Type
    // Case Studies Taxonomies
    register_taxonomy ( 'case_study_collections', 'case_study', array (
        'labels'            => array (
            'name'              => 'Collections',
            'singular_name'     => 'Collection',
            'search_items'      => 'Search Collections',
            'all_items'         => 'All Collections',
            'parent_item'       => 'Parent Collection',
            'parent_item_colon' => 'Parent Collection:',
            'edit_item'         => 'Edit Collection',
            'update_item'       => 'Update Collection',
            'add_new_item'      => 'Add New Collection',
            'new_item_name'     => 'New Collection Name',
            'menu_name'         => 'Collections',
        ),
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'show_in_rest'      => true,
    ) );
    register_taxonomy ( 'case_study_products', 'case_study', array (
        'labels'            => array (
            'name'              => 'Products',
            'singular_name'     => 'Product',
            'search_items'      => 'Search Products',
            'all_items'         => 'All Products',
            'parent_item'       => 'Parent Product',
            'parent_item_colon' => 'Parent Product:',
            'edit_item'         => 'Edit Product',
            'update_item'       => 'Update Product',
            'add_new_item'      => 'Add New Product',
            'new_item_name'     => 'New Product Name',
            'menu_name'         => 'Products',
        ),
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'show_in_rest'      => true,
    ) );
    register_taxonomy ( 'case_study_sectors', 'case_study', array (
        'labels'            => array (
            'name'              => 'Sectors',
            'singular_name'     => 'Sectors',
            'search_items'      => 'Search Sectors',
            'all_items'         => 'All Sectors',
            'parent_item'       => 'Parent Sectors',
            'parent_item_colon' => 'Parent Sectors:',
            'edit_item'         => 'Edit Sectors',
            'update_item'       => 'Update Sectors',
            'add_new_item'      => 'Add New Sectors',
            'new_item_name'     => 'New Sectors Name',
            'menu_name'         => 'Sectors',
        ),
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'show_in_rest'      => true,
    ) );
    register_taxonomy ( 'case_study_type', 'case_study', array (
        'labels'            => array (
            'name'              => 'Type',
            'singular_name'     => 'Type',
            'search_items'      => 'Search Type',
            'all_items'         => 'All Type',
            'parent_item'       => 'Parent Type',
            'parent_item_colon' => 'Parent Type:',
            'edit_item'         => 'Edit Type',
            'update_item'       => 'Update Type',
            'add_new_item'      => 'Add New Type',
            'new_item_name'     => 'New Type Name',
            'menu_name'         => 'Type',
        ),
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'show_in_rest'      => true,
    ) );

    // Sector Taxonomy
    register_taxonomy ( 'faq_category', 'faq', array (
        'labels'            => array (
            'name'              => 'FAQ Categories',
            'singular_name'     => 'FAQ Category',
            'search_items'      => 'Search FAQ Categories',
            'all_items'         => 'All FAQ Categories',
            'parent_item'       => 'Parent FAQ Category',
            'parent_item_colon' => 'Parent FAQ Category:',
            'edit_item'         => 'Edit FAQ Category',
            'update_item'       => 'Update FAQ Category',
            'add_new_item'      => 'Add New FAQ Category',
            'new_item_name'     => 'New FAQ Category Name',
            'menu_name'         => 'FAQ Categories',
        ),
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'show_in_rest'      => true,
        'rewrite'           => array ( 'slug' => 'faq-category' ),
    ) );

    // Samples Taxonomies
    register_taxonomy ( 'sample_range', 'sample', array (
        'labels'            => array (
            'name'              => 'Range',
            'singular_name'     => 'Range',
            'search_items'      => 'Search Range',
            'all_items'         => 'All Range',
            'parent_item'       => 'Parent Range',
            'parent_item_colon' => 'Parent Range:',
            'edit_item'         => 'Edit Range',
            'update_item'       => 'Update Range',
            'add_new_item'      => 'Add New Range',
            'new_item_name'     => 'New Range Name',
            'menu_name'         => 'Range',
        ),
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'show_in_rest'      => true,
    ) );
    register_taxonomy ( 'sample_material', 'sample', array (
        'labels'            => array (
            'name'              => 'Materials',
            'singular_name'     => 'Material',
            'search_items'      => 'Search Materials',
            'all_items'         => 'All Materials',
            'parent_item'       => 'Parent Material',
            'parent_item_colon' => 'Parent Material:',
            'edit_item'         => 'Edit Material',
            'update_item'       => 'Update Material',
            'add_new_item'      => 'Add New Material',
            'new_item_name'     => 'New Material Name',
            'menu_name'         => 'Materials',
        ),
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'show_in_rest'      => true,
    ) );

    // Tools & Guides Taxonomies
    register_taxonomy ( 'tools_guide_type', 'tools_guide', array (
        'labels'            => array (
            'name'              => 'Type',
            'singular_name'     => 'Type',
            'search_items'      => 'Search Type',
            'all_items'         => 'All Type',
            'parent_item'       => 'Parent Type',
            'parent_item_colon' => 'Parent Type:',
            'edit_item'         => 'Edit Type',
            'update_item'       => 'Update Type',
            'add_new_item'      => 'Add New Type',
            'new_item_name'     => 'New Type Name',
            'menu_name'         => 'Type',
        ),
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'show_in_rest'      => true,
    ) );
    register_taxonomy ( 'tools_guide_sector', 'tools_guide', array (
        'labels'            => array (
            'name'              => 'Sector',
            'singular_name'     => 'Sector',
            'search_items'      => 'Search Sector',
            'all_items'         => 'All Sector',
            'parent_item'       => 'Parent Sector',
            'parent_item_colon' => 'Parent Sector:',
            'edit_item'         => 'Edit Sector',
            'update_item'       => 'Update Sector',
            'add_new_item'      => 'Add New Sector',
            'new_item_name'     => 'New Sector Name',
            'menu_name'         => 'Sector',
        ),
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'show_in_rest'      => true,
    ) );

    // Register Custom Taxonomy for Inspiration Categories
    register_taxonomy ( 'inspiration_tax', 'inspiration', array (
        'labels'            => array (
            'name'              => 'Inspiration Categories',
            'singular_name'     => 'Inspiration Category',
            'search_items'      => 'Search Inspiration Categories',
            'all_items'         => 'All Inspiration Categories',
            'parent_item'       => 'Parent Inspiration Category',
            'parent_item_colon' => 'Parent Inspiration Category:',
            'edit_item'         => 'Edit Inspiration Category',
            'update_item'       => 'Update Inspiration Category',
            'add_new_item'      => 'Add New Inspiration Category',
            'new_item_name'     => 'New Inspiration Category Name',
            'menu_name'         => 'Inspiration Categories',
        ),
        'hierarchical'      => true, // Hierarchical like categories
        'show_ui'           => true,
        'show_in_menu'      => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'show_in_rest'      => true,
        'rewrite'           => array ( 'slug' => 'inspiration-category' ),
    ) );

    register_taxonomy (
            'insight_tag', // Custom taxonomy name
            'our_insight', // Post type this taxonomy is for
            array (
                'labels'            => array (
                    'name'          => 'Insight Tags',
                    'singular_name' => 'Insight Tag',
                    'search_items'  => 'Search Insight Tags',
                    'all_items'     => 'All Insight Tags',
                    'edit_item'     => 'Edit Insight Tag',
                    'update_item'   => 'Update Insight Tag',
                    'add_new_item'  => 'Add New Insight Tag',
                    'new_item_name' => 'New Insight Tag Name',
                    'menu_name'     => 'Insight Tags',
                ),
                'hierarchical'      => false, // Non-hierarchical like post tags
                'show_ui'           => true,
                'show_in_menu'      => true,
                'show_admin_column' => true,
                'query_var'         => true,
                'show_in_rest'      => true, // Enable support for Gutenberg editor
                'rewrite'           => array ( 'slug' => 'tag' ), // Set the URL slug
            )
    );
    register_taxonomy (
            'insight_brand',
            'our_insight',
            array (
                'labels'            => array (
                    'name'          => 'Brands',
                    'singular_name' => 'Brand',
                    'search_items'  => 'Search Brands',
                    'all_items'     => 'All Brands',
                    'edit_item'     => 'Edit Brand',
                    'update_item'   => 'Update Brand',
                    'add_new_item'  => 'Add New Brand',
                    'new_item_name' => 'New Brand Name',
                    'menu_name'     => 'Brands',
                ),
                'hierarchical'      => true,
                'show_ui'           => true,
                'show_in_menu'      => true,
                'show_admin_column' => true,
                'query_var'         => true,
                'show_in_rest'      => true,
                'rewrite'           => array ( 'slug' => 'brand' ),
            )
    );

    register_taxonomy (
            'insight_category',
            'our_insight',
            array (
                'labels'            => array (
                    'name'          => 'Categories',
                    'singular_name' => 'Category',
                    'search_items'  => 'Search Categories',
                    'all_items'     => 'All Categories',
                    'edit_item'     => 'Edit Category',
                    'update_item'   => 'Update Category',
                    'add_new_item'  => 'Add New Category',
                    'new_item_name' => 'New Category Name',
                    'menu_name'     => 'Categories',
                ),
                'hierarchical'      => true,
                'show_ui'           => true,
                'show_in_menu'      => true,
                'show_admin_column' => true,
                'query_var'         => true,
                'show_in_rest'      => true,
                'rewrite'           => array ( 'slug' => 'category' ),
            )
    );

    register_taxonomy (
            'insight_sector',
            'our_insight',
            array (
                'labels'            => array (
                    'name'          => 'Sectors',
                    'singular_name' => 'Sector',
                    'search_items'  => 'Search Sectors',
                    'all_items'     => 'All Sectors',
                    'edit_item'     => 'Edit Sector',
                    'update_item'   => 'Update Sector',
                    'add_new_item'  => 'Add New Sector',
                    'new_item_name' => 'New Sector Name',
                    'menu_name'     => 'Sectors',
                ),
                'hierarchical'      => true,
                'show_ui'           => true,
                'show_in_menu'      => true,
                'show_admin_column' => true,
                'query_var'         => true,
                'show_in_rest'      => true,
                'rewrite'           => array ( 'slug' => 'sector' ),
            )
    );

    register_taxonomy (
            'insight_type',
            'our_insight',
            array (
                'labels'            => array (
                    'name'          => 'Types',
                    'singular_name' => 'Type',
                    'search_items'  => 'Search Types',
                    'all_items'     => 'All Types',
                    'edit_item'     => 'Edit Type',
                    'update_item'   => 'Update Type',
                    'add_new_item'  => 'Add New Type',
                    'new_item_name' => 'New Type Name',
                    'menu_name'     => 'Types',
                ),
                'hierarchical'      => true,
                'show_ui'           => true,
                'show_in_menu'      => true,
                'show_admin_column' => true,
                'query_var'         => true,
                'show_in_rest'      => true,
                'rewrite'           => array ( 'slug' => 'type' ),
            )
    );
    register_taxonomy ( 'resource_category', 'resource', array (
        'labels'            => array (
            'name'              => 'Resource Categories',
            'singular_name'     => 'Resource Category',
            'search_items'      => 'Search Resource Categories',
            'all_items'         => 'All Resource Categories',
            'parent_item'       => 'Parent Resource Category',
            'parent_item_colon' => 'Parent Resource Category:',
            'edit_item'         => 'Edit Resource Category',
            'update_item'       => 'Update Resource Category',
            'add_new_item'      => 'Add New Resource Category',
            'new_item_name'     => 'New Resource Category Name',
            'menu_name'         => 'Resource Category',
        ),
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'show_in_rest'      => true,
    ) );
}

add_action ( 'init', 'create_post_type' );

