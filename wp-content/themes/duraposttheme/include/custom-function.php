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
/* SVG file allow */
function add_file_types_to_uploads($file_types){
	$new_filetypes = array();
	$new_filetypes['svg'] = 'image/svg+xml';
	$file_types = array_merge($file_types, $new_filetypes );
	return $file_types;
}
add_filter('upload_mimes', 'add_file_types_to_uploads');

