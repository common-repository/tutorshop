<?php 

if ( ! class_exists( 'Tutorshop_Shortcode' ) ) {

    class Tutorshop_Shortcode {


        public function __construct() {
            add_shortcode('tutorshop', array( $this, 'shortcode' ) );
            add_shortcode( 'playerScriptFront', array( $this,'playerScriptShortcode') );
        }

        
        
        public function shortcode( $args, $content=null ) {
            extract( $args );           

            $shortcode_unique_id = 'tutorshop_shortcode_' . wp_rand( 1, 1000 );

            $content    = "<div id = 'tts_div'>";
            $domain = parse_url(get_site_url())['host'];

            $currentLive = $GLOBALS['tutorshop']->$tutorshop->getCurrentLive();

            if($currentLive != '')
            {
                $channelURL = $currentLive['url'];
                $streamingPlatform = $currentLive['platform'];
                $categoryId = $currentLive['cat_ID'];
                switch($streamingPlatform)
                {
                    case 'twitch':
                        
                        $content = $content.
                        '
                        <style>
                        .twitch .twitch-video {
                            padding-top: 56.25%;
                            position: relative;
                            height: 0;
                          }
                          
                          .twitch .twitch-video iframe {
                            position: absolute;
                            width: 100%;
                            height: 100%;
                            top: 0;
                          }
                          .twitch .twitch-chat {
                            height: 400px;
                          }
                          
                          .twitch .twitch-chat iframe {
                            width: 100%;
                            height: 100%;
                          }
                          @media screen and (min-width: 850px) {
                            .twitch {
                              position: relative;
                            }
                          
                            .twitch .twitch-video {
                              width: 75%;
                              padding-top: 42.1875%;
                            }
                          
                            .twitch .twitch-chat {
                              width: 25%;
                              height: auto;
                              position: absolute;
                              top: 0;
                              right: 0;
                              bottom: 0;
                            }
                          }
                                                                      
                        </style>
                        <div class="twitch">
                        <div class="twitch-video">
                          <iframe
                            src="https://player.twitch.tv/?channel='. esc_js($channelURL).'&parent='.esc_js($domain).'&autoplay=true"
                            frameborder="0"
                            scrolling="no"
                            allowfullscreen="true"
                            height="100%"
                            width="100%">
                          </iframe>
                        </div>
                        <div class="twitch-chat">
                          <iframe
                            frameborder="0"
                            scrolling="no"
                            src="https://www.twitch.tv/embed/'.esc_js($channelURL).'/chat?parent='.($domain).'"
                            height="100%"
                            width="100%">
                          </iframe>
                        </div>
                      </div>
                    ';
                      
                    break;
                    case 'youtube':
                       $content = $content. '<div style = " display: flex;justify-content: center;position: relative;padding-bottom: 56.25%;height: 0;">';
                       $content = $content .'<iframe style = "position: absolute; top: 0;left: 0;width: 100%;height: 100%;" src="https://www.youtube.com/embed/'.esc_js($channelURL).'" title="YouTube video player"></iframe>';
                       $content = $content.'</div>';
                        break;
                    case 'fb':
                        $content = $content . '<div id="fb-root"></div>';
                        $content = $content. 
                        '
                            <script>
                            let box = document.querySelector("#tts_div");
                            let width_tts = box.offsetWidth;
                            </script>
                        ';
                        $content = $content.'<script async defer src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.2"></script>';
                        $content = $content . '<div class="fb-video" data-href="https://www.facebook.com/'.esc_js($channelURL).'" data-width= 2000 data-show-text="false">
                        <div class="fb-xfbml-parse-ignore">
                          <blockquote cite="https://www.facebook.com/facebook/videos/10153231379946729/">
                            <a href="https://www.facebook.com/facebook/videos/10153231379946729/">How to Share With Just Friends</a>
                            <p>How to share with just friends.</p>
                            Posted by <a href="https://www.facebook.com/facebook/">Facebook</a> on Friday, December 5, 2014
                          </blockquote>
                        </div>
                      </div>';
                        break;
                }
                
                $content = $content.do_shortcode("[products limit='10' columns='10' category='".esc_js($categoryId)."']").'</div>';
                //$content = $content.do_shortcode("[playerScriptFront]");
                
            }
            else
            {
                $content = 'There are no scheduled lives :(';
            } 
            return $content;
        }

       function playerScriptShortcode()
       {
        extract( $args );           

        $shortcode_unique_id = 'tutorshop_shortcode_front' . wp_rand( 1, 1000 );
                $content = 
                '
                    <script>
                    var siteUrl = '.json_encode(get_site_url()).'
                    var apiAdress = "/index.php?rest_route=/tutorshop/v1/";
                    var liveEndPoint = "getCurrentLive";
                    var shortcodeEndPoint = "getCurrentShortCode";

                    var currentLive = '.json_encode($currentLive).'
                    const checkCurrentLive = function()
                    {
                        console.log("checking live...");
                        var xhttp = new XMLHttpRequest();
                        xhttp.onreadystatechange = function() {
                            
                            if (this.readyState == 4 && this.status == 200) {
                                var returnLive = JSON.parse(this.responseText)
                                if(returnLive.url != currentLive.url || returnLive.cat_ID != currentLive.cat_ID)
                                {
                                    console.log(currentLive);
                                    console.log(this.responseText);
                                    changeShortCode();
                                }
                                else
                                {
                                    console.log("LIVE IGUAL");
                                }
                            }
                        };
                        xhttp.open("GET", siteUrl+apiAdress+liveEndPoint, true);
                        xhttp.setRequestHeader("Content-type", "application/json");
                        xhttp.send("");
                        
                    }
                    '.'
                    const changeShortCode = function()
                    {
                        var xhttp = new XMLHttpRequest();
                        xhttp.onreadystatechange = function() {
                            if (this.readyState == 4 && this.status == 200) {
                                var tbody = document.getElementById("tts_div");
                                tbody.innerHTML = "";
                                tbody.append(this.responseText.toDOM());
                            }
                        };
                        xhttp.open("GET", siteUrl+apiAdress+shortcodeEndPoint, true);
                        xhttp.setRequestHeader("Content-type", "application/json");
                        xhttp.send("");
                    }
                    </script>';
                
            return $content;

       }
        

    }

} // !class_exists