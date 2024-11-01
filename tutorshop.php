<?php
/**
 * Plugin Name: Tutorshop
 * Plugin URI: https://tutorshop.com.br
 * Description: A liveshopping plugin for WooCommerce
 * Version: 0.0.1   
 * Author: Tutorshop inc
 * Author URI: https://tutorshop.com.br
 * Text Domain: tutorshop
 * Domain Path: /languages/
 * Requires at least: 5.7
 * Requires PHP: 7.0
 *
 * @package tutorshop
 */


 // If this file is called directly, abort.
 


class TutorshopMain {


    /**
       * Override any of the template functions from woocommerce/woocommerce-template.php
       * with our own template functions file
       */
      function include_template_functions() {
        //include( 'woocommerce-template.php' );
      }

      /**
       * Take care of anything that needs woocommerce to be loaded.
       * For instance, if you need access to the $woocommerce global
       */
       function woocommerce_loaded() {
        // ...
      }

      /**
       * Take care of anything that needs all plugins to be loaded
       */

    function check_requirements() {
        if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
            return true;
        } else {
            add_action('admin_notices',array ($this,'missing_wc_notice'));
            return false;
        }
    }

    function missing_wc_notice() { 
    ?>
        <div class="error notice">
                        <p><strong><?php echo __( 'Tutorshop Plugin', 'tutorshop' ); ?></strong></p>
                        <p><?php echo __( 'Tutorshop plugin needs WooCommerce to be activated to work!', 'tutorshop' ); ?></p>
                    <p><a href='plugins.php'> Go to plugins page </a>
                    </div>
                    <?php
    }

    // Admin Instance



    function plugins_loaded() {
        
            if($this->check_requirements())
            {
                if( is_admin() ) {
                    $tutorshop_admin_page = new Tutorshop_Admin(
                        TUTORSHOP_BASENAME, 
                        TUTORSHOP_PLUGIN_SLUG, 
                        TUTORSHOP_JSON_FILENAME,
                        TUTORSHOP_VERSION
                    );
                }
                // Plugin Instance
                $this->$tutorshop = new Tutorshop();
                // Widget Instance
                /**$my_yt_rec_widget = new My_Youtube_Recommendation_Widget();*/
        
                // Shortcode Instance
                $tutorshopShortcode = new Tutorshop_Shortcode();
                
        
                /*$channel_id = $my_yt_rec_plugin->options['channel_id'];
                if ( $channel_id != "" ){
                    $expiration = $my_yt_rec_plugin->options['cache_expiration'];
                    $my_yt_rec_json = new My_Youtube_Recommendation_Json( 
                        $channel_id, 
                        $expiration, 
                        MY_YOUTUBE_RECOMMENDATION_PLUGIN_SLUG, 
                        MY_YOUTUBE_RECOMMENDATION_JSON_FILENAME 
                    );
                
                }*/
            }
        

    }

    function __construct()
    {
        
        if ( ! defined( 'WPINC' ) ) {
            wp_die();
        }
        
        if (!function_exists('is_plugin_active')) {
            include_once(ABSPATH . 'wp-admin/includes/plugin.php');
        }

        // Plugin Version
        if ( ! defined( 'TUTORSHOP_VERSION' ) ) {
            define( 'TUTORSHOP_VERSION', '0.0.1' );
        }
        
        // Plugin Name
        if ( ! defined( 'TUTORSHOP_NAME' ) ) {
            define( 'TUTORSHOP_NAME', 'TUTORSHOP' );
        }
        
        // Plugin Slug
        if ( ! defined( 'TUTORSHOP_PLUGIN_SLUG' ) ) {
            define( 'TUTORSHOP_PLUGIN_SLUG', 'tutorshop' );
        }
        
        // Plugin Basename
        if ( ! defined( 'TUTORSHOP_BASENAME' ) ) {
            define( 'TUTORSHOP_BASENAME', plugin_basename( __FILE__ ) );
        }
        
        // Plugin Folder
        if ( ! defined( 'TUTORSHOP_PLUGIN_DIR' ) ) {
            define( 'TUTORSHOP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
        }
        
        // JSON File Name
        if ( ! defined( 'TUTORSHOP_JSON_FILENAME' ) ) {
            define( 'TUTORSHOP_JSON_FILENAME', 'tutorshop.json' );
        }
        
        // Load the plugin's translated strings.
        //load_plugin_textdomain(TUTORSHOP_PLUGIN_SLUG, false, TUTORSHOP_PLUGIN_SLUG.'/languages/' );

        // Dependencies
        require_once TUTORSHOP_PLUGIN_DIR . 'includes/class-tutorshop.php';
        /*require_once TUTORSHOP_PLUGIN_DIR . 'includes/class-tutorshop-json.php';
        require_once TUTORSHOP_PLUGIN_DIR . 'includes/class-tutorshop-widget.php';*/
        require_once TUTORSHOP_PLUGIN_DIR . 'includes/class-tutorshop-shortcode.php';
        if( is_admin() )
        {
            require_once TUTORSHOP_PLUGIN_DIR . 'includes/class-tutorshop-admin.php';
            }
        
        // called just before the woocommerce template functions are included
        add_action( 'init', array( $this, 'include_template_functions' ), 20 );

        // called only after woocommerce has finished loading
        add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );

        // called after all plugins have loaded
        //add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );

        // indicates we are being served over ssl
        if ( is_ssl() ) {
        // ...
        }

        
    }



}

  $GLOBALS['tutorshop'] = new TutorshopMain();




