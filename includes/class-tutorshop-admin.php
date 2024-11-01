<?php
if(!class_exists('Tutorshop_Admin')){

    class Tutorshop_Admin {
        private $options;
        private $plugin_basename;
        private $plugin_slug;
        private $json_filename;
        private $plugin_version;

        public function __construct ($basename,$slug,$json_filename,$version)
        {
            $this-> options = get_option('tutorshop_options');
            $this-> plugin_basename = $basename;
            $this-> plugin_slug = $slug;
            $this-> json_filename = $json_filename;
            $this-> plugin_version  = $version;

            add_action('admin_menu',array ($this,'add_plugin_page'));
            add_action('admin_init',array ($this,'page_init'));
            //add_action('admin_init', 'famous_script' );
            add_action('admin_footer_text',array ($this,'page_footer'));
            add_action('admin_notices',array ($this,'show_notices'));
            //add_action('plugin_action_links'.$this->plugin_basename,array ($this,'add_settings_link'));
            add_action('admin_enqueue_scripts', 'james_adds_to_the_head');
            
            
            
	    }
        
        function james_adds_to_the_head() {
 
            //wp_enqueue_script('jquery');
         
            wp_register_script( 'tutorshop-admin-frontend', plugin_dir_url( __FILE__ ) . 'js/tutorshop-admin-frontend.js', array('jquery'),'',true  );
           
            wp_enqueue_script('tutorshop-admin-frontend');
         
        }

        

        function famous_script() {
            wp_register_script( 'tutorshop-admin-frontend', plugin_dir_url( __FILE__ ) . 'js/tutorshop-admin-frontend.js', array('jquery'),'',false  );
            wp_enqueue_script( 'tutorshop-admin-frontend' );
        }

        function my_assets() {
            wp_enqueue_script( 'theme-scripts', plugin_dir_url( __FILE__ ) . 'js/tutorshop-admin-frontend.js', array(), '-1', true );
        }
        
        
        

         function add_plugin_page () {
            add_options_page(__('Tutorshop for WooCommerce','tutorshop'),'Tutorshop','manage_options',$this->plugin_slug, array($this,'create_admin_page'));
        
        }

        /**
         * Add settings link on plugins page (IS NOT WORKING!!)
         */
        public function add_settings_link( $links ) {
            $settings_link = '<a href="options-general.php?page='. $this->plugin_slug .'">' . __( 'Settings' ) . '</a>';
            array_unshift( $links, $settings_link );
            return $links;
        }

        /**
         * Show notices on admin dashboard
         */
        public function show_notices() {
            $value = isset( $this->options['livestreams'] ) ? esc_attr( $this->options['livestreams'] ) : '';
            if(empty($value)){$value = '';}
            global $pagenow;
            if ($value == '' and !in_array( $pagenow, array('options-general.php'))){
                ?>
                <div class="error notice">
                <?php echo esc_html($channel_id) ?>
                    <p><strong><?php  echo esc_html( 'Tutorshop Plugin', 'tutorshop' ); ?></strong></p>
                    <p><?php echo esc_html( 'You have no lives scheduled', 'tutorshop' ); ?></p>
                   <p><a href='options-general.php?page=<?php echo esc_url($this->plugin_slug)?>'> Go to settings page </a>
                </div>
                <?php 
            }
        }

        public function create_admin_page() {
            if ( !current_user_can( 'manage_options' ) )  {
                wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
            }
            ?>
            <div class="wrap">
                <h1><?php echo __('Tutorshop' , 'tutorshop'); ?></h1>
                <form method="post" action="options.php">
                <?php
                    
                    // This prints out all hidden setting fields
                    settings_fields( 'tutorshop_options' );
                    do_settings_sections( 'tutorshop-admin' );
                    submit_button();
                ?>
                </form>
            </div>
            <?php
        }

        public function page_init() {   
            
            register_setting(
                'tutorshop_options', // Option group
                'tutorshop_options', // Option name
                array( $this, 'sanitize' ) // Sanitize
            );


                add_settings_section(
                    'setting_section_id_1', // ID
                    __('General Settings' , 'tutorshop'), // Title
                    null, // Callback
                    'tutorshop-admin' // Page
                ); 
    
                        add_settings_field(
                            'livestreams', 
                            __('Live streams' , 'tutorshop'), 
                            array( $this, 'livestreams_callback' ), 
                            'tutorshop-admin', 
                            'setting_section_id_1'
                        );    
        }

        public function page_footer(){
            return __("Plugin Version: ") . TUTORSHOP_VERSION;
        }

         /**
         * Sanitize each setting field as needed
         *
         * @param array $input Contains all settings fields as array keys
         */
        public function sanitize( $input ) {

            $new_input = array();          
            if(isset($input['livestreams'])){
                if(count($input['livestreams']) > 0)
                {
                    //$new_input['livestreams'] = $input['livestreams'];
                    $new_input['livestreams'] =  array();
                    for($i = 0; $i < count($input['livestreams']);$i++)
                    {
                        $inputLine = $input['livestreams'][$i];
                        $isFull = true;
                        if(!is_null($inputLine['name']))
                        { $inputLine['name'] = sanitize_text_field($inputLine['name']);}
                        else { $isFull = false;}
                        if(!is_null($inputLine['url']))
                        { $inputLine['url'] = sanitize_text_field($inputLine['url']);}
                        else { $isFull = false;}   
                        if(!is_null($inputLine['start_date']))
                        { $inputLine['start_date'] = ($inputLine['start_date']);}
                        else { $isFull = false;}  
                        if(!is_null($inputLine['platform']))
                        { $inputLine['platform'] = ($inputLine['platform']);}
                        else { $isFull = false;}  
                        if($isFull)
                        {
                            $new_input['livestreams'][count($new_input['livestreams'])] = $inputLine;
                        }
                        

                    }
                }
                else
                {
                    $new_input['livestreams'] = [];
                }
                
                   
                
                }
                
            return $new_input;
        }

        public function livestreams_callback(){
            $date = date_format(date_create("1969-04-20 10:00"));
            $array_value = array(
                0=>array("name"=>"Test stream :D","url"=>"jsdhquwduhas","start_date"=>'1969-04-20 13:00:00'),
                1=>array("name"=>"Test stream2 :D","url"=>"jsdhquwduhas","start_date"=>'1969-04-20 13:00:00'),
                2=>array("name"=>"Test stream3 :D","url"=>"jsdhquwduhas","start_date"=>'1969-04-20 13:00:00')
            );
            $streamingPlatforms = array(
                0=>array("name"=>"Facebook","value"=>"fb","icon"=>"fb_icon"),
                1=>array("name"=>"Twitch","value"=>"twitch","icon"=>"twitch_icon"),
                2=>array("name"=>"Youtube","value"=>"youtube","icon"=>"youtube_icon"));
                //always set because of updates (dont want clients complaining about this and having to manually uninstall and re-install)
                $catArgs = array('taxonomy'=> 'product_cat','orderby'=> 'date','show_count'=>1,
                            'pad_counts'=>0,'hierarchical'=>0,'title_li'=>'','hide_empty'=>1);
                
                
            $productCatogories = get_categories($catArgs);
            $this->options['streamingPlatforms'] = $streamingPlatforms;
            if(isset( $this->options['livestreams'] ))
            {
                $array_value = $this->options['livestreams'];
            }
            else
            {
                $this->options['livestreams'] = $array_value;
                $array_value = $this->options['livestreams'];
            }
            
            
            ?>
            <script>
             var array_value = <?php echo wp_json_encode($array_value)?>;
             var streamingPlatforms = <?php echo wp_json_encode($streamingPlatforms)?>;
             var productCategories = <?php echo wp_json_encode($productCatogories)?>;
             
            </script>
            <script src = '<?php echo plugin_dir_url( __FILE__ ) . 'js/tutorshop-admin-frontend.js'; ?>'></script>
            <input type = 'text' id = 'tableCleaner' name = 'tutorshop_options[livestreams]' value = '' style = 'visibility: hidden;'/>
            <table id = 'LIVE_TABLE'>
                <tr>
                    <th>Stream title</th>
                    <th> Stream Platform </th>
                    <th>Live Url</th>
                    <th> Product Category</th>
                    <th>Live date</th>
                </tr>
            
            </table>
            <input type="button" value = "add new line" onclick="addNewLine()"/>
            <script> for(var i =0; i < array_value.length;i++){ addNewLine(array_value[i],i)} </script>
            <?php

        }
    


    }

}