<?php
// AFC general setting Menu tab and Setting Function

if( function_exists ( 'acf_add_options_page' ) ) {
    acf_add_options_page ( array (
        'page_title' => 'General Setting',
        'menu_title' => 'General Setting',
        'menu_slug'  => 'theme-general-settings',
        'capability' => 'edit_posts',
        'redirect'   => false
    ) );
}
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
        'menu_position' => 5,
        'show_in_rest'  => true,
        'menu_icon'     => 'dashicons-testimonial',
        'supports'      => array ( 'title', 'editor', 'thumbnail' ),
        'taxonomies'    => array (),
        'hierarchical'  => false,
        'rewrite'       => array ( 'slug' => 'testimonials' ),
    );

    register_post_type ( 'testimonial', $testimonial_args );

}

add_action ( 'init', 'create_post_type' );