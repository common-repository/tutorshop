<?php
    class Tutorshop
    {
        function sortByStartDate($a, $b) {
            return strtotime($a['start_date']) - strtotime($b['start_date']);
        }

        function check_in_range($start_date, $end_date, $date_from_user)
        {
        // Convert to timestamp
        $start_ts = strtotime($start_date);
        $end_ts = strtotime($end_date);
        $user_ts = strtotime($date_from_user);

        // Check that user date is between start & end
        return (($user_ts >= $start_ts) && ($user_ts <= $end_ts));
        }
        
        public function getCurrentLive()
        {
            //date_default_timezone_set("America/Sao_Paulo");
            $livestreamArray = get_option("tutorshop_options")['livestreams'];
            usort($livestreamArray,array($this,'sortByStartDate'));
            $firstDateRange = '';
            $secondDateRange = '2100-01-01 10:00:00';
            $currentLive = '';
            for ($i = 0; $i <count($livestreamArray); $i++) {
                $firstDateRange = $livestreamArray[$i]['start_date'];
                if(!is_null($livestreamArray[$i+1]['start_date']))
                {
                    $secondDateRange = $livestreamArray[$i+1]['start_date'];
                }
                else
                {
                    $secondDateRange = '2100-01-01 10:00:00';
                }

                if($this->check_in_range($firstDateRange,$secondDateRange,date("Y-m-d H:i:s")))
                {
                    $currentLive = $livestreamArray[$i];
                }

            }
            return $currentLive;
        }

        public function getLiveShortcode()
        {
            return do_shortcode("[tutorshop]");
        }

         

        function __construct()
        {
            add_action( 'rest_api_init', function () {
            register_rest_route( 'tutorshop/v1', '/getCurrentLive', array(
              'methods' => 'GET',
              'callback' => array($this, 'getCurrentLive'),
              'permission_callback' => '__return_true',
            ) );
          } );

          add_action( 'rest_api_init', function () {
            register_rest_route( 'tutorshop/v1', '/getCurrentShortCode', array(
              'methods' => 'GET',
              'callback' => array($this, 'getLiveShortcode'),
              'permission_callback' => '__return_true',
            ) );
          } );
        }


        

       
    }
?>