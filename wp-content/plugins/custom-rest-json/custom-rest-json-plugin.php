<?php
/**
 * Plugin Name: Custom Rest Json Plugin
 * Description: Defines models for various post types with Get_Data and Store_Data functions.
 * Version: 1.0
 * Author: CDA
 * Text Domain: custom-rest-json-plugin
 */
$options             = get_option ( 'custom_rest_json_options' );
$consumer_key        = isset ( $options[ 'consumer_key' ] ) ? $options[ 'consumer_key' ] : '';
$consumer_secret_key = isset ( $options[ 'consumer_secret_key' ] ) ? $options[ 'consumer_secret_key' ] : '';
$post_per_page       = isset ( $options[ 'products_per_page' ] ) ? $options[ 'products_per_page' ] : '';
if( ! defined ( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if( ! defined ( 'REST_CONSUMER_KEY' ) ) {
    define ( 'REST_CONSUMER_KEY', $consumer_key );
}

if( ! defined ( 'REST_CONSUMER_SECRET' ) ) {
    define ( 'REST_CONSUMER_SECRET', $consumer_secret_key );
}
if( ! defined ( 'POST_PER_PAGE' ) ) {
    define ( 'POST_PER_PAGE', $post_per_page );
}

final class Custom_Rest_Json {

    private $container = array ();

    public function __construct() {
        register_activation_hook ( __FILE__, array ( $this, 'rest_json_plugin_install' ) );

        register_deactivation_hook ( __FILE__, array ( $this, 'rest_json_plugin_uninstall' ) );
        //add_action( 'plugins_loaded', 'crjp_load_classes' );
        //add_action( 'wp_loaded', 'crjp_load_classes' );
        add_action ( 'admin_notices', array ( $this, 'render_missing_woocommerce_notice' ) );

        if( class_exists ( 'WooCommerce' ) ) {
            add_action ( 'woocommerce_loaded', array ( $this, 'init_plugin' ) );
        } else {
            add_action ( 'init', array ( $this, 'init_plugin' ) );
        }

        //add_action( 'woocommerce_init',array($this,'init_plugin') );
        add_action ( 'admin_menu', array ( $this, 'register_custom_plugin_menu' ) );
        add_action ( 'save_post', array ( $this, 'on_update_data' ), 10, 1 );

        if( ! wp_next_scheduled ( 'my_custom_cron_hook' ) ) {
            $this->schedule_my_cron ();
        }
        add_action ( 'my_custom_cron_hook', array ( $this, 'custom_cron_rest_json' ) );
        add_action ( 'all_cms_custom_cron_hook', array ( $this, 'all_cms_generate' ) );
        add_action ( 'all_post_custom_cron_hook', array ( $this, 'all_post_generate' ) );
        if( class_exists ( 'WooCommerce' ) ) {
            add_action ( 'all_product_custom_cron_hook', array ( $this, 'all_product_generate' ) );
        }
        add_action ( 'admin_enqueue_scripts', array ( $this, 'my_custom_admin_plugin_scripts' ) );
        //add_action('wp_ajax_my_custom_action', array($this,'custom_cron_rest_json'));

        register_setting ( 'custom_rest_json_options_group', 'custom_rest_json_options' );

        add_action ( 'wp_ajax_bpu_update_products', array ( $this, 'bpu_update_products' ) );
        add_action ( 'rest_api_init', [ $this, 'register_custom_routes' ] );

        add_action ( 'acf/save_post', array ( $this, 'update_json_on_acf_save' ), 20 );
    }

    public function register_custom_routes() {
        if( class_exists ( 'WooCommerce' ) ) {
            register_rest_route ( 'wp/v2', '/jsonNotfound', array (
                'methods'             => 'GET',
                'callback'            => [ $this, 'jsonNotfoundFunc' ],
                'permission_callback' => '__return_true'
            ) );
        }
        register_rest_route ( 'wp/v2', '/getjson', array (
            'methods'             => 'GET',
            'callback'            => [ $this, 'getjson_response' ],
            'permission_callback' => '__return_true'
        ) );
        register_rest_route ( 'wp/v2', '/custom_cron_rest_json_api', array (
            'methods'             => 'GET',
            'callback'            => [ $this, 'custom_cron_rest_json' ],
            'permission_callback' => '__return_true'
        ) );
    }

    public static function init() {
        static $instance = false;

        if( ! $instance ) {
            $instance = new static();
        }
        return $instance;
    }

    public function init_plugin() {
        $this->includes ();
        $this->init_classes ();
        do_action ( 'prafe_loaded' );
    }

    public function init_classes() {
        $this->container[ 'cms_model' ]      = new CMS_Model();
        $this->container[ 'blog_model' ]     = new Blog_Model();
        $this->container[ 'warmer_model' ]   = new Warmer_Model();
        $this->container[ 'postlist_model' ] = new PostList_Model();
        if( class_exists ( 'WooCommerce' ) ) {
            $this->container[ 'category_model' ] = new Category_Model();
            $this->container[ 'product_model' ]  = new Product_Model();
        }
    }

    public function includes() {
        require_once plugin_dir_path ( __FILE__ ) . 'includes/class-rest-json.php';
        require_once plugin_dir_path ( __FILE__ ) . 'includes/class-blog-model.php';
        require_once plugin_dir_path ( __FILE__ ) . 'includes/class-postlist-model.php';
        require_once plugin_dir_path ( __FILE__ ) . 'includes/class-cms-model.php';
        require_once plugin_dir_path ( __FILE__ ) . 'includes/class-warmer-model.php';
        if( class_exists ( 'WooCommerce' ) ) {
            require_once plugin_dir_path ( __FILE__ ) . 'includes/class-taxonomy.php';
            require_once plugin_dir_path ( __FILE__ ) . 'includes/class-product-model.php';
            require_once plugin_dir_path ( __FILE__ ) . 'includes/class-category-model.php';
            require_once plugin_dir_path ( __FILE__ ) . 'includes/class-filtered-products.php';
        }
        require_once plugin_dir_path ( __FILE__ ) . 'includes/core-function.php';
        // $this->custom_cron_rest_json();
    }

    public function render_missing_woocommerce_notice() {

        // if ( ! self::has_woocommerce() && current_user_can( 'activate_plugins' ) ) {
        // 	require_once PRAEF_TEMPLATES . '/admin-notice.php';
        // }
    }

    public function schedule_my_cron() {
        if( ! wp_next_scheduled ( 'my_custom_cron_hook' ) ) {
            wp_schedule_event ( time (), 'every_five_minutes', 'my_custom_cron_hook' );
        }
        if( ! wp_next_scheduled ( 'all_cms_generate' ) ) {
            wp_schedule_event ( time (), 'daily', 'all_cms_generate' ); // Change as needed
        }
        if( ! wp_next_scheduled ( 'all_post_generate' ) ) {
            wp_schedule_event ( time (), 'daily', 'all_post_generate' ); // Change as needed
        }
        if( class_exists ( 'WooCommerce' ) ) {
            if( ! wp_next_scheduled ( 'all_product_generate' ) ) {
                wp_schedule_event ( time (), 'daily', 'all_product_generate' ); // Change as needed
            }
        }
    }

    public function all_cms_generate() {
        $cms_model = new CMS_Model();
        $page_ids  = $this->get_all_page_ids ( "page" );
        if( ! empty ( $page_ids ) ) {
            foreach ( $page_ids as $page ) {
                $cms_data = $cms_model->get_data ( $page );
                $cms_model->store_data ( $cms_data );
            }
            $this->custom_log ( "All page generated" );
        }
    }

    public function all_post_generate() {
        $blog_model = new Blog_Model();
        $cms_model  = new CMS_Model();
        $page_ids   = $this->get_all_page_ids ( "post" );
        if( ! empty ( $page_ids ) ) {
            foreach ( $page_ids as $page ) {
                $post_data = $cms_model->get_data ( $page );
                $blog_model->store_data ( $post_data );
            }
            $this->custom_log ( "All post generated" );
        }
    }

    public function all_product_generate() {
        if( class_exists ( 'WooCommerce' ) ) {
            $product_model = new Product_Model();
            $cms_model     = new CMS_Model();
            $page_ids      = $this->get_all_page_ids ( "product" );
            if( ! empty ( $page_ids ) ) {
                foreach ( $page_ids as $page ) {
                    $post_data = $cms_model->get_data ( $page );
                    $product_model->store_data ( $post_data );
                }
                $this->custom_log ( "All product generated" );
            }
        }
    }

    public function on_update_data($id) {
        $cms_model = new CMS_Model();
        if( class_exists ( 'WooCommerce' ) ) {
            $product_model = new Product_Model();
        }
        $blog_model   = new Blog_Model();
        $warmer_model = new Warmer_Model();
        $cms_data     = $cms_model->get_data ( $id );
        if( $cms_data->post_type == "page" ) {
            $cms_model->store_data ( $cms_data );
        }
        if( $cms_data->post_type == "product" && class_exists ( 'WooCommerce' ) ) {
            $product_model->store_data ( $cms_data );
            $this->set_cron_job_entry ( $cms_data, "product" );
        }
        if( $cms_data->post_type == "post" ) {
            $blog_model->store_data ( $cms_data );
            $this->set_cron_job_post_entry ( $cms_data, "post" );
        }
    }

    public function update_json_on_acf_save($post_id) {
        if( $post_id === 'options' ) {
            $this->all_cms_generate ();  
            $this->all_post_generate ();
            if( class_exists ( 'WooCommerce' ) ) {
                $this->all_product_generate (); 
            }
            error_log ( 'JSON files updated after options page save' );
        }
    }

    // Function to update JSON for all pages
    public function update_all_pages_json() {
        $cms_model = new CMS_Model();
        $page_ids  = $this->get_all_page_ids ( "page" );
        if( ! empty ( $page_ids ) ) {
            foreach ( $page_ids as $page ) {
                $cms_data = $cms_model->get_data ( $page );
                $cms_model->store_data ( $cms_data );
            }
            $this->custom_log ( "All page generated" );
        }
    }

    public function rest_json_plugin_install() {
        global $wpdb;
        $table_name      = $wpdb->prefix . 'rest_json_cron'; // Table name with prefix
        $charset_collate = $wpdb->get_charset_collate ();

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            `page_type` varchar(100) NOT NULL,
            `type` varchar(100) NOT NULL,
            `slug` varchar(150) NOT NULL,
            `type_id` int NOT NULL,
            `status` int NOT NULL,
            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta ( $sql );
        add_action ( 'init', array ( $this, 'initialize_data' ) );
    }

    public function rest_json_plugin_uninstall() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'rest_json_cron'; // Table name with prefix

        $sql = "DROP TABLE IF EXISTS $table_name;";
        $wpdb->query ( $sql );
    }

    public function custom_cron_rest_json() {
        require_once plugin_dir_path ( __FILE__ ) . 'includes/class-category-model.php';
        global $wpdb;

        $table_name     = $wpdb->prefix . 'rest_json_cron'; // Table name with prefix
        $qry_row        = $wpdb->get_results ( "SELECT * FROM $table_name WHERE status = 1", ARRAY_A );
        $category_model = new Category_Model();
        $postlist_model = new PostList_Model();
        if( ! empty ( $qry_row ) ) {
            foreach ( $qry_row as $row ) {
                if( $row[ 'page_type' ] == "product" && class_exists ( 'WooCommerce' ) ) {
                    $category_model->store_data ( $row );
                }
                if( $row[ 'page_type' ] == "post" ) {
                    for ( $i = 1; $i <= 5; $i ++ ) {
                        $postlist_model->store_data ( $i );
                    }
                }
                //$this->clear_wp_engine_cache();
                $wpdb->query ( "UPDATE $table_name SET `status` = 0  WHERE `id` =" . $row[ 'id' ] );
            }
        }
    }

    // public function clear_wp_engine_cache() {
    //     if ( function_exists( 'wpe_cache_flush' ) ) {
    //         wpe_cache_flush();
    //     }
    // }

    public function get_terms_obj_data($product_id, $taxonomy) {
        if( class_exists ( 'WooCommerce' ) ) {
            global $wpdb;
            $sql   = $wpdb->prepare (
                    "SELECT t.*
                FROM {$wpdb->terms} t
                INNER JOIN {$wpdb->term_taxonomy} tt ON t.term_id = tt.term_id
                INNER JOIN {$wpdb->term_relationships} tr ON tt.term_taxonomy_id = tr.term_taxonomy_id
                WHERE tr.object_id = %d
                AND tt.taxonomy = %s",
                    $product_id,
                    $taxonomy
            );
            $terms = $wpdb->get_results ( $sql );
            return $terms;
        }
    }

    public function set_cron_job_post_entry() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'rest_json_cron';
        $query      = $wpdb->prepare ( "SELECT * FROM {$table_name} WHERE page_type = %d and status=%d", "post", "product_cat", 1 );
        $post       = $wpdb->get_row ( $query );
        if( empty ( $post ) ) {
            $wpdb->insert ( $table_name, array (
                "page_type" => "post",
                "type"      => "post",
                "slug"      => "",
                "type_id"   => "",
                "status"    => 1,
            ) );
        }
    }

    public function set_cron_job_entry($data, $type) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'rest_json_cron';
        $taxonomies = GetTaxomonyClass::getTaxonomyArray ();
        if( ! empty ( $taxonomies ) ) {
            foreach ( $taxonomies as $term ) {
                $term_obj = get_the_terms ( $data->ID, $term );
                if( ! empty ( $term_obj ) ) {
                    foreach ( $term_obj as $t ) {
                        if( $t->count > 0 ) {
                            $query = $wpdb->prepare ( "SELECT * FROM {$table_name} WHERE type_id = %d and type = %s and status=%d", $t->term_id, $term, 1 );
                            $post  = $wpdb->get_row ( $query );
                            if( empty ( $post ) ) {
                                $wpdb->insert ( $table_name, array (
                                    "page_type" => $type,
                                    "type"      => $term,
                                    "slug"      => $t->slug,
                                    "type_id"   => $t->term_id,
                                    "status"    => 1,
                                ) );
                            }
                        }
                    }
                }
            }
        }
    }

    public function initialize_data() {
        global $wpdb;
        $cms_model     = $this->container[ 'cms_model' ];
        $blog_model    = $this->container[ 'blog_model' ];
        $product_model = null;
        if( class_exists ( 'WooCommerce' ) ) {
            $product_model = $this->container[ 'product_model' ];
        }
        // Debugging
        if( ! $cms_model ) {
            error_log ( 'CMS_Model is not initialized.' );
        }
        if( ! $blog_model ) {
            error_log ( 'Blog_Model is not initialized.' );
        }
        if( $product_model === null ) {
            error_log ( 'Product_Model is not initialized or WooCommerce is not active.' );
        }
        // Fetch existing posts of different types to store them in your custom table.
        $post_types = [ 'page', 'post', 'product' ];

        foreach ( $post_types as $post_type ) {
            $args = array (
                'post_type'      => $post_type,
                'posts_per_page' => -1,
                'post_status'    => 'publish',
            );

            $query = new WP_Query ( $args );
            if( $query->have_posts () ) {
                foreach ( $query->posts as $post ) {

                    if( $post_type == "page" ) {
                        $cms_model->store_data ( $post );
                    }
                    if( $post_type == "product" && class_exists ( 'WooCommerce' ) ) {
                        // Only call product_model if WooCommerce is active
                        if( $product_model !== null ) {
                            $product_model->store_data ( $post );
                        }
                    }
                    if( $post_type == "post" ) {
                        $blog_model->store_data ( $post );
                    }
                }
            }
        }
    }

    public function my_custom_admin_plugin_scripts() {
        wp_enqueue_script ( 'my-custom-admin-script', plugin_dir_url ( __FILE__ ) . 'js/custom-rest-admin.js', array ( 'jquery' ), null, true );
        wp_localize_script ( 'my-custom-admin-script', 'myCustomAdmin', array (
            'ajax_url' => admin_url ( 'admin-ajax.php' )
        ) );
    }

    public function register_custom_plugin_menu() {
        // Main Menu Page
        add_menu_page (
                'Cache Warming', // Page title
                'Cache Warming', // Menu title
                'manage_options', // Capability
                'cache-warming-models', // Menu slug
                [ $this, 'display_models_dashboard' ], // Function to display content
                'dashicons-admin-generic', // Icon
                50 // Position
        );
    }

    public function my_custom_cron_function() {
        error_log ( 'Cron job started' );
        if( class_exists ( 'WooCommerce' ) ) {
            // Your cron job logic here
            $product_id = 3495; // Example product ID
            $product    = wc_get_product ( $product_id );

            if( ! $product ) {
                error_log ( 'Product not found' );
            } else {
                error_log ( 'Product found: ' . $product->get_name () );
            }
        } else {
            error_log ( 'WooCommerce is not active, skipping product-related tasks.' );
        }
    }

    public function display_models_dashboard() {
        if( class_exists ( 'WooCommerce' ) ) {
            $taxonomies = GetTaxomonyClass::getTaxonomyArray ();
        }
        ?>
        <h1>Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields ( 'custom_rest_json_options_group' );
            do_settings_sections ( 'custom_rest_json_plugin' );
//            $options = get_option ( 'custom_rest_json_options' );
            if( class_exists ( 'WooCommerce' ) ) {
                ?>
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row"><label for="posts_per_page">Product per page</label></th>
                            <td><input name="custom_rest_json_options[products_per_page]" type="number" step="1" min="1" id="products_per_page" value="<?php echo POST_PER_PAGE; ?>" class="small-text"> products</td>
                        </tr>
                    </tbody>
                </table>
            <?php } ?>
            <h2>API Key</h2>
            <table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row"><label for="posts_per_page">Consumer key</label></th>
                        <td><input name="custom_rest_json_options[consumer_key]" type="text" id="consumer_key" value="<?php echo REST_CONSUMER_KEY; ?>" /></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="posts_per_page">Consumer secret</label></th>
                        <td><input name="custom_rest_json_options[consumer_secret_key]" type="text" id="consumer_secret_key" value="<?php echo REST_CONSUMER_SECRET; ?>" /></td>
                    </tr>
                </tbody>
            </table>
            <p class="submit"><input type="submit" name="submit" id="product-meta-button" class="button button-primary" value="Save Changes"></p>
        </form>
        <h2>Bulk Update</h2>
        <table class="form-table">
            <tbody>
                <?php if( class_exists ( 'WooCommerce' ) ) { ?>
                    <tr>
                        <th scope="row"><label for="posts_per_page">Product</label></th>
                        <td>
                            <button style="display: inline-block;" data-id="product" class="start-update button button-primary">Product Update</button>
                            <div class="spinner-show" style="display: none;"><img src="<?php echo plugin_dir_url ( __FILE__ ); ?>images/spinner.gif" /></div>
                            <div class="status" style="margin-top: 10px;"></div>
                            <!-- <div id="progress-bar" style="width: 100%; background: #f3f3f3; border: 1px solid #ccc; margin-top: 20px;">
                                <div id="progress" style="width: 0%; height: 12px; background: #4caf50;"></div>
                            </div> -->
                        </td>
                    </tr>
                <?php } ?>
                <tr>
                    <th scope="row"><label for="posts_per_page">Page</label></th>
                    <td>
                        <button style="display: inline-block;" data-id="page" class="start-update button button-primary">Page Update</button>
                        <div class="spinner-show" style="display: none;"><img src="<?php echo plugin_dir_url ( __FILE__ ); ?>images/spinner.gif" /></div>
                        <div class="status" style="margin-top: 10px;"></div>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="posts_per_page">Post</label></th>
                    <td>
                        <button style="display: inline-block;" data-id="post" class="start-update button button-primary">Post Update</button>
                        <div class="spinner-show" style="display: none;"><img src="<?php echo plugin_dir_url ( __FILE__ ); ?>images/spinner.gif" /></div>
                        <div class="status" style="margin-top: 10px;"></div>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="posts_per_page">Post List</label></th>
                    <td>
                        <button style="display: inline-block;" data-id="post-list" class="start-update button button-primary">Post List Update</button>
                        <div class="spinner-show" style="display: none;"><img src="<?php echo plugin_dir_url ( __FILE__ ); ?>images/spinner.gif" /></div>
                        <div class="status" style="margin-top: 10px;"></div>
                    </td>
                </tr>
                <?php if( class_exists ( 'WooCommerce' ) ) { ?>
                    <tr>
                        <td scope="row"><h3>Product List Update</h3></label></td>
                    </tr>
                <?php } ?>
                <?php
                if( ! empty ( $taxonomies ) ) {
                    foreach ( $taxonomies as $term ) {
                        ?>
                        <tr>
                            <th scope="row"><label for="posts_per_page"><?php echo $term; ?></label></th>
                            <td>
                                <button style="display: inline-block;" data-id=<?php echo $term; ?> class="start-update button button-primary"><?php echo $term; ?> Update</button>
                                <div class="spinner-show" style="display: none;"><img src="<?php echo plugin_dir_url ( __FILE__ ); ?>images/spinner.gif" /></div>
                                <div class="status" style="margin-top: 10px;"></div>
                            </td>
                        </tr>           
                        <?php
                    }
                }
                ?>

            </tbody>
        </table>
        <h4>Note :</h4>
        <table class="form-table">
            <tbody>
                <tr>
                    <td>
                        <label for="posts_per_page"><h4>Rest API</h4></label>
                        <p><b>Manual for creating JSON via API (type : Post,Post and Product)</b></p>
                        <p>https://xyz.com/wp-json/wp/v2/jsonNotfound?type=page&slug=about-us</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="posts_per_page"><h4>Cron Events</h4></label>
                        <p><b>Add this plugin ( WP Crontrol ) :</b>   https://wordpress.org/plugins/wp-crontrol/</p>
                        <p><b>Following this step, set up cron.</b></p>
                        <p><b>Event Type :</b> Standard cron event</p>
                        <p><b>Hook Name :</b> my_custom_cron_hook</p>

                        <p><b>Daily basis setup cron</b></p>
                        <p><b>Hook Name :</b> all_cms_custom_cron_hook</p>
                        <p><b>Hook Name :</b> all_post_custom_cron_hook</p>
                        <?php if( class_exists ( 'WooCommerce' ) ) { ?>
                            <p><b>Hook Name :</b> all_product_custom_cron_hook</p>
                        <?php } ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php
    }

    public function custom_log($message) {
        if( defined ( 'WP_DEBUG' ) && WP_DEBUG ) {
            // Define the log file path
            $log_file  = __DIR__ . '/custom-log.txt'; // Adjust the path as needed
            // Prepare the message with a timestamp
            $timestamp = date ( 'Y-m-d H:i:s' );
            $log_entry = "[$timestamp] $message" . PHP_EOL;

            // Write the log entry to the log file
            file_put_contents ( $log_file, $log_entry, FILE_APPEND );
        }
    }

    public function get_all_page_ids($post_type) {
        $args = array (
            'post_type'      => $post_type,
            'posts_per_page' => -1, // Get all pages
            'fields'         => 'ids', // Only return IDs
        );

        $pages = get_posts ( $args );
        return $pages;
    }

    public function bpu_update_products() {
        // Simulate product updating process
        $processed = 0;
        if( class_exists ( 'WooCommerce' ) ) {
            $product_model  = new Product_Model();
            $category_model = new Category_Model();
            $taxonomies     = GetTaxomonyClass::getTaxonomyArray ();
        }
        $cms_model      = new CMS_Model();
        $blog_model     = new Blog_Model();
        $postlist_model = new PostList_Model();
        if( isset ( $_GET[ 'parmas' ] ) ) {
            if( $_GET[ 'parmas' ] == 'product' && class_exists ( 'WooCommerce' ) ) {
                $product_ids = $this->get_all_page_ids ( "product" );
                if( ! empty ( $product_ids ) ) {
                    foreach ( $product_ids as $p ) {
                        $post_data = $cms_model->get_data ( $p );
                        $product_model->store_data ( $post_data );
                        $processed ++;
                    }
                }
            }
            if( $_GET[ 'parmas' ] == 'page' ) {
                $page_ids = $this->get_all_page_ids ( "page" );
                if( ! empty ( $page_ids ) ) {
                    foreach ( $page_ids as $p ) {
                        $post_data = $cms_model->get_data ( $p );
                        $cms_model->store_data ( $post_data );
                        $processed ++;
                    }
                }
            }
            if( $_GET[ 'parmas' ] == 'post' ) {
                $post_ids = $this->get_all_page_ids ( "post" );
                if( ! empty ( $post_ids ) ) {
                    foreach ( $post_ids as $p ) {
                        $post_data = $cms_model->get_data ( $p );
                        $blog_model->store_data ( $post_data );
                        $processed ++;
                    }
                }
            }
            if( $_GET[ 'parmas' ] == 'post-list' ) {
                for ( $i = 1; $i <= 5; $i ++ ) {
                    $postlist_model->store_data ( $i );
                }
            }

            if( ! empty ( $taxonomies ) ) {
                foreach ( $taxonomies as $term ) {
                    if( $_GET[ 'parmas' ] == $term ) {
                        $categories = get_terms ( array (
                            'taxonomy'   => $term, // Product category taxonomy
                            'hide_empty' => true, // Set to false to show empty categories
                                ) );
                        if( ! empty ( $categories ) ) {
                            foreach ( $categories as $c ) {
                                $row[ 'type_id' ] = $c->term_id;
                                $row[ 'type' ]    = $c->taxonomy == "product_cat" ? "category" : $c->taxonomy;
                                $category_model->store_data ( $row );
                            }
                        }
                    }
                }
            }
        }

        wp_send_json_success ( [ 'msg' => $_GET[ 'parmas' ] . " Updated!." ] );
    }

    public function jsonNotfoundFunc(WP_REST_Request $request) {
        if( class_exists ( 'WooCommerce' ) ) {
            if( $request[ 'type' ] == "product" ) {
                $detail_response = wp_remote_get ( site_url () . '/wp-json/wc/v3/products/?slug=' . $request[ 'slug' ], array (
                    'headers' => array (
                        'Authorization' => 'Basic ' . base64_encode ( REST_CONSUMER_KEY . ':' . REST_CONSUMER_SECRET )
                    )
                        ) );
                if( is_wp_error ( $detail_response ) ) {
                    error_log ( 'Error fetching page data: ' . $detail_response->get_error_message () );
                    return;
                }
                // Parse the body of the response
                $detail_body = wp_remote_retrieve_body ( $detail_response );

                $detaildata = json_decode ( $detail_body, true ); // Decode the JSON response into an array
                if( ! empty ( $detaildata ) ) {
                    // Directory to save the JSON file
                    $detailuploadPath   = wp_upload_dir ();
                    $detail_docs_folder = $detailuploadPath[ 'basedir' ] . '/productdetail/';

                    // Ensure the folder exists
                    if( ! file_exists ( $detail_docs_folder ) ) {
                        wp_mkdir_p ( $detail_docs_folder ); // Create the directory if it doesn't exist
                    }
                    // Create the JSON file path
                    $detail_paths = $detail_docs_folder . $request[ 'slug' ] . ".json";
                    // Save the response to a JSON file
                    $detail_json  = json_encode ( $detaildata, JSON_PRETTY_PRINT );
                    file_put_contents ( $detail_paths, $detail_json );
                } else {
                    error_log ( 'No data returned for slug: ' . $request[ 'slug' ] );
                }
            }
        }
        if( $request[ 'type' ] == "page" ) {
            $response = wp_remote_get ( site_url () . '/wp-json/wp/v2/pages?slug=' . $request[ 'slug' ], array (
                'headers' => array (
                    'Authorization' => 'Basic ' . base64_encode ( REST_CONSUMER_KEY . ':' . REST_CONSUMER_SECRET )
                )
                    ) );
            // Check if response is valid
            if( is_wp_error ( $response ) ) {
                error_log ( 'Error fetching page data: ' . $response->get_error_message () );
                return;
            }
            // Parse the body of the response
            $body = wp_remote_retrieve_body ( $response );

            $data = json_decode ( $body, true ); // Decode the JSON response into an array
            if( ! empty ( $data ) ) {
                // Directory to save the JSON file
                $uploadPath  = wp_upload_dir ();
                $docs_folder = $uploadPath[ 'basedir' ] . '/pages/';

                // Ensure the folder exists
                if( ! file_exists ( $docs_folder ) ) {
                    wp_mkdir_p ( $docs_folder ); // Create the directory if it doesn't exist
                }
                // Create the JSON file path
                $page_paths = $docs_folder . $request[ 'slug' ] . ".json";
                // Save the response to a JSON file
                $page_json  = json_encode ( $data, JSON_PRETTY_PRINT );
                file_put_contents ( $page_paths, $page_json );
            } else {
                error_log ( 'No data returned for slug: ' . $request[ 'slug' ] );
            }
        }
        if( $request[ 'type' ] == 'post' ) {
            $response = wp_remote_get ( site_url () . '/wp-json/wp/v2/posts?slug=' . $request[ 'slug' ], array (
                'headers' => array (
                    'Authorization' => 'Basic ' . base64_encode ( REST_CONSUMER_KEY . ':' . REST_CONSUMER_SECRET )
                )
                    ) );
            // Check if response is valid
            if( is_wp_error ( $response ) ) {
                error_log ( 'Error fetching page data: ' . $response->get_error_message () );
                return;
            }
            // Parse the body of the response
            $body = wp_remote_retrieve_body ( $response );

            $data = json_decode ( $body, true ); // Decode the JSON response into an array
            if( ! empty ( $data ) ) {
                // Directory to save the JSON file
                $uploadPath  = wp_upload_dir ();
                $docs_folder = $uploadPath[ 'basedir' ] . '/posts/';

                // Ensure the folder exists
                if( ! file_exists ( $docs_folder ) ) {
                    wp_mkdir_p ( $docs_folder ); // Create the directory if it doesn't exist
                }
                // Create the JSON file path
                $page_paths = $docs_folder . $request[ 'slug' ] . ".json";
                // Save the response to a JSON file
                $page_json  = json_encode ( $data, JSON_PRETTY_PRINT );
                file_put_contents ( $page_paths, $page_json );
            } else {
                error_log ( 'No data returned for slug: ' . $request[ 'slug' ] );
            }
        }

        // Example data to return.
        $data = array (
            'message' => 'json created!',
            'status'  => 'success',
        );

        return new WP_REST_Response ( $data, 200 );
    }

    public function getjson_response(WP_REST_Request $request) {
        if( $request[ 'type' ] == "cms" ) {
            $uploadPath  = wp_upload_dir ();
            $docs_folder = $uploadPath[ 'baseurl' ] . '/pages/' . $request[ 'slug' ] . '.json';
            echo $docs_folder;
            $jsonData    = file_get_contents ( $docs_folder );
            if( $jsonData === false ) {
                die ( 'Error reading JSON file' );
            }
            $dataArray = json_decode ( $jsonData, true );
            if( json_last_error () !== JSON_ERROR_NONE ) {
                die ( 'Error decoding JSON: ' . json_last_error_msg () );
            }
            print_r ( $dataArray );
        }
    }
}

if( ! function_exists ( 'Custom_Rest_Json' ) ) {

    /**
     * Load Custom_Rest_Json Plugin
     *
     * @return Custom_Rest_Json
     */
    function custom_rest_rson_plugin() {
        return Custom_Rest_Json::init ();
    }

}

custom_rest_rson_plugin ();
// Priority 20 to ensure ACF fields are saved first

