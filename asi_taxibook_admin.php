<?php
      global $wpdb;
      if (($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['addrate']) ) 
        {  
             $mile=sanitize_text_field($_POST['mile']);
             $mile=filter_var( $mile, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );           
             $stop=sanitize_text_field($_POST['stop']);
             $stop=filter_var( $stop, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
             $seat=sanitize_text_field($_POST['seat']);
             $seat=filter_var( $seat, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
             $minute=sanitize_text_field($_POST['minute']);
             $minute=filter_var( $minute, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
             $adul=sanitize_text_field($_POST['adul']);
             $adul=filter_var( $adul, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
             $inf=sanitize_text_field($_POST['inf']);
             $inf=filter_var( $inf, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
             $lugg=sanitize_text_field($_POST['lugg']);
             $lugg=filter_var( $lugg, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
             $curr=sanitize_text_field($_POST['curr']);
             $bcolor=sanitize_text_field($_POST['bcolor']);
             $table_name = $wpdb->prefix."fare";
             $wpdb->query($wpdb->prepare("UPDATE $table_name SET mile=%s,stop=%s,seat=%s,adul=%s,inf=%s,lugg=%s,minute=%s,curr=%s,color=%s WHERE fare_id=%d",$mile,$stop,$seat,$adul,$inf,$lugg,$minute,$curr,$bcolor,1));
             
        }
         if (($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['addtaxi']) ) 
        { 
             $cartype=sanitize_text_field($_POST['cartype']);
             $carfare=sanitize_text_field($_POST['carfare']);
             $carfare=filter_var( $carfare, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
             $table_name = $wpdb->prefix."cartypes";
             $wpdb->query($wpdb->prepare("INSERT INTO $table_name(name,fare) VALUES(%s,%s)",array($cartype,$carfare)));
             
        }
    class asi_taxibook_plugin_admin
    {
         /** verbingo_plugin father class */
            private $taxi_settings_key = 'taxi_setting';
            private $car_settings_key = 'asi_taxisetting';
            private $booking_settings_key = 'asi_booking';
            private $language_settings_key = 'asi_lang';
            private $taxi_options_key = 'asi_options';
            private $taxi_settings_tabs = array();
            private $localleft = 'left';
                
            function __construct() 
            {
                add_action( 'init', array( &$this, 'asi_taxibook_scripts_admin'));
                add_action( 'init', array( &$this, 'load_taxi_settings'));
                add_action( 'admin_init', array( &$this, 'register_asi_taxi_settings' ));
                add_action( 'admin_init', array( &$this, 'register_addtaxi_settings' ));
                add_action( 'admin_init', array( &$this, 'register_taxibooking_settings' ));
                add_action( 'admin_init', array( &$this, 'register_taxilang_settings' ));
                add_action( 'admin_init', array( &$this, 'taxi_allselected_car' ));
                add_action( 'admin_init', array( &$this, 'taxi_allselected_fare' ));
                add_action( 'admin_menu', array( &$this, 'add_admin_menus' ));
            } 
            function taxi_allselected_car()
            {
                global $wpdb;
                $table_name = $wpdb->prefix."cartypes";
                $cartypes = $wpdb->get_results("SELECT * FROM $table_name",ARRAY_A);
                return $cartypes;
        
            }
            function taxi_allselected_fare()
            {
                global $wpdb;
                $table_name = $wpdb->prefix."fare";
                $fares = $wpdb->get_results("SELECT * FROM $table_name",ARRAY_A);
                return $fares;
            }
            /** UTILITY FUNCTIONS * */
            private function section($head, $text = '') {
                echo '<h2>' . $head . '</h2>';
                echo '<div class="col-wrap">';
                if ($text) echo '<p>' . $text . '</p>';
            }
        
            private function topsection() {
                echo '</div>';
            }
                
             private function header($head) 
             {
                 echo '<h3>'.$head.' </h3>';
             }

            function asi_taxibook_scripts_admin()
            {
                $google_map_api = 'https://maps.google.com/maps/api/js?sensor=true&libraries=places&language=en-AU';
                wp_enqueue_script('google-places', $google_map_api);
                wp_register_style('asi_taxi_style', plugins_url('css/asi_taxibook_style.css',__FILE__));
                wp_enqueue_style('asi_taxi_style');
                wp_register_script('asi_taxi_script', plugins_url('js/asi_taxibook_script.js', __FILE__ ),array('jquery'));
                wp_enqueue_script('asi_taxi_script'); 	
            }
            // Load Settings
            function load_taxi_settings() 
            {
                $this->general_settings = (array) get_option( $this->taxi_settings_key );
                $this->advanced_settings = (array) get_option( $this->car_settings_key );            
            
                // Merge with defaults
                $this->general_settings = array_merge( array(
                    'general_option' => 'General value'
                ), $this->general_settings );
            
                $this->advanced_settings = array_merge( array(
                    'advanced_option' => 'Advanced value'
                ), $this->advanced_settings );
                
           }  
            
            function register_asi_taxi_settings() 
            {
                $this->taxi_settings_tabs[$this->taxi_settings_key] = 'Settings';
            
                register_setting( $this->taxi_settings_key, $this->taxi_settings_key );
                add_settings_section( 'section_fare', '', array( &$this, 'section_taxi_fare_desc' ), $this->taxi_settings_key );
            } 
            
            
            // Call Language Setting Page
            function section_taxi_fare_desc() 
            { 
                $fares=$this->taxi_allselected_fare();
                $this->section(__('Fare Settings','asi'));
                echo '<br><form method="POST" action="" name="addrate" enctype="multipart/form-data" ><table name="instfare">';
                echo '<tr><td>Fare per mile</td><td><input value="'.$fares[0]['mile'].'" type="text" name="mile" style="width:105px;" ></td></tr>';
                echo '<tr><td>Fare per stop</td><td><input value="'.$fares[0]['stop'].'" type="text" name="stop" style="width:105px;" ></td></tr>';
                
                echo '<tr><td>Fare per adult seat</td><td><input value="'.$fares[0]['adul'].'" type="text" name="adul" style="width:105px;" ></td></tr>';
                echo '<tr><td>Fare per baby seat</td><td><input value="'.$fares[0]['seat'].'" type="text" name="seat" style="width:105px;" ></td></tr>';
                echo '<tr><td>Fare per infant seat</td><td><input value="'.$fares[0]['inf'].'" type="text" name="inf" style="width:105px;" ></td></tr>';
                echo '<tr><td>Fare per luggage</td><td><input value="'.$fares[0]['lugg'].'" type="text" name="lugg" style="width:105px;" ></td></tr>';
                
                echo '<tr><td>Fare per minute</td><td><input value="'.$fares[0]['minute'].'" type="text" name="minute" style="width:105px;"></td></tr>';
                echo '<tr><td>Currency Type</td><td><input value="'.$fares[0]['curr'].'" type="text" name="curr" style="width:105px;" ></td></tr>';
                echo '<tr><td>Background color</td><td><input value="'.$fares[0]['color'].'" type="text" name="bcolor" style="width:105px;"></td>';
                echo '<tr><td colspan="4"><input type="submit" id="faresubmit" value="Save Changes" class="button-primary" name="addrate" style="margin-top:40px;width:105px;z-index:2147483647; padding: 0px;"/></td></tr>';
                echo '</table></form>';
                echo '<br>';
                $this->topsection();
            }
                // Register Advance Settings
                function register_addtaxi_settings() 
                {
                $this->taxi_settings_tabs[$this->car_settings_key] = 'Taxi Type';
                register_setting( $this->car_settings_key, $this->car_settings_key );
                add_settings_section( 'section_addcar', 'Add Taxi Type', array( &$this, 'section_addtaxi_desc' ), $this->car_settings_key );
        }
            
    function section_addtaxi_desc() 
    { 
        $cartypes=$this->taxi_allselected_car();
        $fares=$this->taxi_allselected_fare();
        echo '<br><table class="displayrecord">';
        $i=1;
        echo '<thead><tr class="home"><th>S.No</th><th>Car Type</th><th>Car Fee</th><th>Action</th></tr></thead><tbody>';
        foreach($cartypes as $car)
        {
           echo '<tr><td>'.$i.'</td><td>'.$car['name'].'</td><td>'.$car['fare'].' '.$fares[0]['curr'].'</td><td><div class="actions"><a href="" ><img  class="rem" title="Remove" alt="Delete" src="'.plugins_url("img/", __FILE__).'/delete.png" content="'.$car['c_id'].'">
           </a></div></td></tr>';
           $i++;
        }
        echo '<form name="addtaxi" method="POST" action="" enctype="multipart/form-data" ><table name="addcar" class="displayrecord">';
        echo '<tr><td>Car Type</td><td><input type="text" name="cartype" style="width:90%"></td>';
        echo '<td>Car Fee</td><td><input type="text" name="carfare" style="width:90%"></td></tr>';
        echo '<tr><td colspan="3" style="border:none !important;"><input type="submit" id="carsubmit" value="Save Changes" class="button-primary" name="addtaxi" style="margin-top:40px;width:105px;z-index:2147483647; padding: 0px;"/></td></tr>';
        echo '</tbody></table></form>';
                 
    }
    function register_taxilang_settings()
    {
        $this->taxi_settings_tabs[$this->language_settings_key] = 'Language';
        register_setting( $this->language_settings_key, $this->language_settings_key );
        add_settings_section( 'section_addlang', '', array( &$this, 'section_taxilang_desc' ), $this->language_settings_key);
        
    }
    function section_taxilang_desc()
    {
        $this->section(__('Language Setting','asi'));
        $this->header(__('For more options contact us at','asi'));
        echo '<br><b>info1@adaptivesolutionsinc.com</b>';
        echo '<br><form method="POST" action="" name="addlanguage" ><table style="width:80%;">';
        echo '</table></form>';
        echo '<br>';
        $this->topsection();
    }
     function register_taxibooking_settings() 
     {
                $this->taxi_settings_tabs[$this->booking_settings_key] = 'Booking';
                register_setting( $this->booking_settings_key, $this->booking_settings_key );
                add_settings_section( 'section_booking', 'Bookings', array( &$this, 'section_taxibooking_desc' ), $this->booking_settings_key );
     }
            
    function section_taxibooking_desc() 
    { 
        echo '<br><table class="displaybooking">';
        $i=1;
        echo '<thead><tr class="home"><th>S.No</th><th>Name</th><th>Email</th><th>Cell</th><th>PickUp</th><th>Drop Off</th><th>Fare</th><th>Distance</th><th>Time</th><th>Car Type</th><th>Adults</th><th>Baby</th><th>Infants</th><th>Luggage</th><th>Delete</th></tr></thead><tbody>';
        echo '<tr><td colspan="15">No Record Found</td></tr>';
        echo '</tbody></table></form>';
                      
    }
    // Add Menu Here
    function add_admin_menus() {
    
    add_menu_page('asi_dashboard', 'Asi Taxi Booking', 'manage_options', $this->taxi_options_key, array( &$this, 'plugin_options_page' ),''.plugins_url("img/", __FILE__).'asiimg.png');
    
    }
    
    function plugin_options_page() {
    $tab = isset( $_GET['tab'] ) ? $_GET['tab'] : $this->taxi_settings_key; ?>
    <div class="wrap">
        <?php $this->taxi_options_tabs(); ?>
        <form method="post" action="options.php">
            <?php wp_nonce_field( 'update-options' ); ?>
            <?php settings_fields( $tab ); ?>
            <?php do_settings_sections( $tab ); ?>
            <?php //submit_button(); ?>
        </form>
    </div>
    <?php
        }
        
    function taxi_options_tabs() {
    $current_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : $this->taxi_settings_key;

    $scren=screen_icon();
    echo '<h2 class="nav-tab-wrapper">';
    foreach ( $this->taxi_settings_tabs as $tab_key => $tab_caption ) {
        $active = $current_tab == $tab_key ? 'nav-tab-active' : '';
        echo '<a class="nav-tab ' . $active . '" href="?page=' . $this->taxi_options_key . '&tab=' . $tab_key . '">' . $tab_caption . '</a>';
    }
    echo '</h2>';
}
        
}
add_action( 'plugins_loaded', create_function( '', '$asi_admin_side = new asi_taxibook_plugin_admin;' ) );
?>