<?php ob_start(); if ( !session_id()) { session_start(); }

  /**
  * @package sys
  * @version 1.0
  */

  /**
  * Plugin Name: Show your Support
  * Plugin URI: http://bornforlogic.com/
  * Description: Plugin to show your Support for an Event by updating your profile picture on Facebook and Twitter adding a badge.
  * Version: 1.0
  * Author: Harshad Mane
  * Author URI: http://bornforlogic.com/
  * License: GPLv3 or later
  */

  /**
  * Copyright (C) 2016  Harshad Mane (email : harshadmane@gmail.com)
  * This program is free software: you can redistribute it and/or modify
  * it under the terms of the GNU General Public License as published by
  * the Free Software Foundation, either version 3 of the License, or
  * (at your option) any later version.

  * This program is distributed in the hope that it will be useful,
  * but WITHOUT ANY WARRANTY; without even the implied warranty of
  * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  * GNU General Public License for more details.

  * Please check <http://www.gnu.org/licenses/>.
  */

  class WPSys {

  /**
  * Function name: __construct()
  * Contructor to initialize code
  * Parameters: none
  */

  public function __construct(){

    // create custom plugin settings menu
    add_action('admin_menu', array($this, 'plugin_sys_create_menu'));
    add_shortcode('ft_sys', array($this, 'sys_ft_function'));
    add_action('admin_head', array($this, 'sys_custom_admin_styles'));

    // define constants for abosolute and url path
    define('SYS_DIR_PATH', plugin_dir_path(__FILE__));
    define('SYS_URL_PATH', plugin_dir_url(__FILE__));
  }

  /**
  * Function name: plugin_sys_create_menu()
  * Function to create Show your Support in wp-admin
  * Parameters: none
  */

  public function plugin_sys_create_menu() {

  	//create new top-level menu
  	add_menu_page('SYS Settings', 'SYS Settings', 'manage_options', 'sys_settings', array($this,'plugin_sys_settings_page'));

  	//call register settings function
  	add_action( 'admin_init', array($this, 'register_plugin_sys_create_menu'));
  }

  /**
  * Function name: sys_custom_admin_styles()
  * Function to include custom styles to SYS Settings page
  * Parameters: none
  */

  public function sys_custom_admin_styles() {

    echo '<style type="text/css">
      span.placeholder{
        margin: 5px !important;
        font-size: .8em !important;
        word-wrap: normal !important;
        word-break: keep-all !important;
      }</style>';
  }

  /**
  * Function name: register_plugin_sys_create_menu()
  * Function to register settings
  * Parameters: none
  */

  public function register_plugin_sys_create_menu() {

    //register our settings
    register_setting( 'plugin-sys-settings-group', 'sys_app_id' );
    register_setting( 'plugin-sys-settings-group', 'sys_app_secret' );
    register_setting( 'plugin-sys-settings-group', 'sys_api_version' );
    register_setting( 'plugin-sys-settings-group', 'sys_callback_url' );
    register_setting( 'plugin-sys-settings-group', 'sys_privacy_page_url' );
    register_setting( 'plugin-sys-settings-group', 'sys_share_phrase' );
    register_setting( 'plugin-sys-settings-group', 'sys_default_image' );
    register_setting( 'plugin-sys-settings-group', 'sys_overlay_image' );
    register_setting( 'plugin-sys-settings-group', 'sys_twitter_app_secret' );
    register_setting( 'plugin-sys-settings-group', 'sys_twitter_api_secret' );
    register_setting( 'plugin-sys-settings-group', 'sys_twitter_callback_url' );
    register_setting( 'plugin-sys-settings-group', 'sys_facebook_album_name' );
  }

  /**
  * Function name: plugin_sys_settings_page()
  * Function to update/display values on Show your Support settings admin page
  * Parameters: none
  */

  public function plugin_sys_settings_page() {

  if (isset($_POST["action"]) && $_POST["action"] == 'update') {
    update_option( 'sys_app_id', $_POST['sys_app_id'] );
    update_option( 'sys_app_secret', $_POST['sys_app_secret'] );
    update_option( 'sys_api_version', $_POST['sys_api_version'] );
    update_option( 'sys_callback_url', $_POST['sys_callback_url'] );
    update_option( 'sys_privacy_page_url', $_POST['sys_privacy_page_url'] );
    update_option( 'sys_heading', stripslashes($_POST['sys_heading'] ));
    update_option( 'sys_share_phrase', stripslashes($_POST['sys_share_phrase']) );
    update_option( 'sys_para', stripslashes($_POST['sys_para']) );
    update_option( 'sys_twitter_app_secret', stripslashes($_POST['sys_twitter_app_secret']) );
    update_option( 'sys_twitter_api_secret', stripslashes($_POST['sys_twitter_api_secret']) );
    update_option( 'sys_twitter_callback_url', stripslashes($_POST['sys_twitter_callback_url']) );
    update_option( 'sys_facebook_album_name', stripslashes($_POST['sys_facebook_album_name']) );
      
  if(!empty($_FILES['sys_overlay_image']['tmp_name'])){
		move_uploaded_file($_FILES['sys_overlay_image']['tmp_name'], SYS_DIR_PATH.'images/' . $_FILES['sys_overlay_image']['name']);
    $path = SYS_URL_PATH.'images/'.$_FILES['sys_overlay_image']['name'];
    update_option( 'sys_overlay_image', $path );
    }
      
  if(!empty($_FILES['sys_default_image']['tmp_name'])){
    move_uploaded_file($_FILES['sys_default_image']['tmp_name'], SYS_DIR_PATH.'images/' . $_FILES['sys_default_image']['name']);
    $path = SYS_URL_PATH.'images/'.$_FILES['sys_default_image']['name'];
    update_option( 'sys_default_image', $path );
    }
?>
    
    <div class="updated notice">
      <p><?php _e('Settings Updated!','sys');?></p>
    </div>
<?php
    }
?>
    <div class="wrap">
      <h2><?php _e("Show your Support (Facebook/Twitter)","sys"); ?></h2>
      <p><strong><?php _e("* Use [ft_sys] shortcode in post/page","sys"); ?></strong></p>

      <form method="post" action="<?php echo esc_attr($_SERVER['REQUEST_URI']); ?>" enctype="multipart/form-data">
      <?php settings_fields( 'plugin-sys-settings-group' ); ?>
      <?php do_settings_sections( 'plugin-sys-settings-group' ); ?>
    
      <table class="form-table">
        
        <tr valign="top">
        <th scope="row"><h1><?php _e('Facebook Settings','sys');?></h1></th>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e('Facebook App ID:','sys');?></th>
        <td><input type="text" name="sys_app_id" value="<?php echo esc_attr( get_option('sys_app_id') ); ?>" style="width:400px;"/><br/><span class="placeholder"><?php _e('Enter Facebook App ID','sys');?></span></td>
        </tr>

        <tr valign="top">
        <th scope="row"><?php _e('Facebook App Secret:','sys');?></th>
        <td><input type="text" name="sys_app_secret" value="<?php echo esc_attr( get_option('sys_app_secret') ); ?>" style="width:400px;"/><br/><span class="placeholder"><?php _e('Enter Facebook App Secret','sys');?></span></td>
        </tr>

        <tr valign="top">
        <th scope="row"><?php _e('Facebook API Version:','sys');?></th>
        <td><input type="text" name="sys_api_version" value="<?php echo esc_attr( get_option('sys_api_version') ); ?>" style="width:400px;"/><br/><span class="placeholder"><?php _e('Enter Facebook API Version','sys');?></span></td>
        </tr>

        <tr valign="top">
        <th scope="row"><?php _e('Facebook Callback URL:','sys');?></th>
        <td><input type="text" name="sys_callback_url" value="<?php echo esc_attr( get_option('sys_callback_url') ); ?>" style="width:400px;"/><br/><span class="placeholder"><?php _e('Enter Facebook Callback URL','sys');?></span></td>
        </tr>

        <tr valign="top">
        <th scope="row"><?php _e('Facebook Privacy Page URL:','sys');?></th>
        <td><input type="text" name="sys_privacy_page_url" value="<?php echo esc_attr( get_option('sys_privacy_page_url') ); ?>" style="width:400px;"/><br/><span class="placeholder"><?php _e('Enter Privacy Page URL','sys');?></span></td>
        </tr>

        <tr valign="top">
        <th scope="row"><?php _e('Facebook Album Name:','sys');?></th>
        <td><input type="text" name="sys_facebook_album_name" value="<?php echo esc_attr( get_option('sys_facebook_album_name') ); ?>" style="width:400px;"/><br/><span class="placeholder"><?php _e('Facebook Creates Album for your updated picture, Please Enter Facebook Album Name','sys');?></span></td>
        </tr>

        <tr valign="top">
         <th scope="row"><h1><?php _e('Twitter Settings','sys');?></h1></th>
        </tr>

        <tr valign="top">
        <th scope="row"><?php _e('Twitter Consumer Key (API Key):','sys');?></th>
        <td><input type="text" name="sys_twitter_app_secret" value="<?php echo esc_attr( get_option('sys_twitter_app_secret') ); ?>" style="width:400px;"/><br/><span class="placeholder"><?php _e('Enter Twitter Consumer Key (API Key)','sys');?></span></td>
        </tr>

        <tr valign="top">
        <th scope="row"><?php _e('Twitter Consumer Secret (API Secret):','sys');?></th>
        <td><input type="text" name="sys_twitter_api_secret" value="<?php echo esc_attr( get_option('sys_twitter_api_secret') ); ?>" style="width:400px;"/><br/><span class="placeholder"><?php _e('Enter Twitter API Secret','sys');?></td>
        </tr>

        <tr valign="top">
        <th scope="row"><?php _e('Twitter Callback URL:','sys');?></th>
        <td><input type="text" name="sys_twitter_callback_url" value="<?php echo esc_attr( get_option('sys_twitter_callback_url') ); ?>" style="width:400px;"/><br/><span class="placeholder"><?php _e('Enter Twitter Callback URL','sys');?></td>
        </tr>

        <tr valign="top">
         <th scope="row"><h1><?php _e('General Settings','sys');?></h1></th>
        </tr>

        <tr valign="top">
        <th scope="row"><?php _e('Heading:','sys');?></th>
        <td><input type="text" name="sys_heading" value="<?php echo esc_attr( get_option('sys_heading') ); ?>" style="width:400px;"/><br/><span class="placeholder"><?php _e('Enter Header title to appear on Screen 1. Ex: Show your support for WordCamp Event','sys');?></td>
        </tr>

        <tr valign="top">
        <th scope="row"><?php _e('Excerpt:','sys');?></th>
        <td><textarea style="width:400px;height:200px;" name="sys_para"><?php echo esc_attr( get_option('sys_para') ); ?></textarea><br/><span class="placeholder"><?php _e('Enter Excerpt to appear on Screen 1. Ex. Show your support for WordCamp Event','sys');?></td>
        </tr>

        <tr valign="top">
        <th scope="row"><?php _e('Default Share message:','sys');?></th>
        <td><textarea style="width:400px;height:200px;" name="sys_share_phrase"><?php echo esc_attr( get_option('sys_share_phrase') ); ?></textarea><br/><span class="placeholder"><?php _e('Enter default text to appear on users timeline.','sys');?></td>
        </tr>

        <tr valign="top">
        <th scope="row"><?php _e('Default Image (480 X 480):','sys');?><br/>
        <?php _e("This is default image that will appear on Screen 1.","sys");?>
        </th>
        <td>
        
        <input type="file" name="sys_default_image" />
        <?php if(!empty(get_option('sys_default_image'))){ ?>
        <br/>
        <img src="<?php echo esc_attr( get_option('sys_default_image')); ?>" height="480" width="480" style="border:1px solid black;"/>
<?php 
      } 
?>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row"><?php _e('Overlay Image (480 X 480):','sys');?><br/>
        <?php _e("This is overlay image that will appear on your Facebook Profile pic.","sys");?>
        </th>
        <td>
        <input type="file" name="sys_overlay_image" />
        <?php if(!empty(get_option('sys_overlay_image'))){ ?>
        <br/>
        <img src="<?php echo esc_attr( get_option('sys_overlay_image')); ?>" height="480" width="480" style="border:1px solid black;"/>
<?php   
      } 
?>
        </td>
        </tr>

    </table>

<?php 
    submit_button(); 
?>
    </form>
    </div>
<?php 
}

  /**
  * Function name: sys_ft_function()
  * Function to execute shortcode
  * Parameters: $atts (To get shortcode paramenters if passed any)
  */

  public function sys_ft_function( $atts ){
    global $post;
    $pid = $post->ID;
    $url = get_permalink($pid);

    wp_enqueue_style('sys_skeleton',SYS_URL_PATH.'css/skeleton.css"');
    wp_enqueue_style('sys_custom',SYS_URL_PATH.'css/custom.css"');

    require(SYS_DIR_PATH.'oauth/twitteroauth.php');

    //Twitter App Settings
    define('TWITTER_CONSUMER_KEY', esc_attr( get_option('sys_twitter_app_secret') ));
    define('TWITTER_CONSUMER_SECRET', esc_attr( get_option('sys_twitter_api_secret') ));
    define('TWITTER_OAUTH_CALLBACK', esc_attr( get_option('sys_twitter_callback_url') ));

    $method = isset($_REQUEST['method']);
    if( $method  == 'tauth'){
      $this->twitter_auth();
      die();
    }

    if (isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
      $_SESSION['oauth_status'] = 'oldtoken';
      session_destroy();
      $message = __('This authorization code has been used. Click <a href="'.$url.'">here</a> to Try again!','sys');
      echo $message;
      die();
    }

    if (isset($_REQUEST['oauth_token'])) {
      $connection = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

      $access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);

      //save new access tocken array in session
      $_SESSION['access_token'] = $access_token;
      unset($_SESSION['oauth_token']);
      unset($_SESSION['oauth_token_secret']);

    if (200 == $connection->http_code) {
      $_SESSION['status'] = 'verified';  
      } else {
      session_destroy();
      }

    if($_SESSION['status'] == 'verified'){
      $data = $this->manage_twitter_post();
      return $data;
      die();
      }
    
    }

    if (isset($_POST["update_twitter"]) && $_POST["update_twitter"] == '1'){
      $text = htmlspecialchars($_POST['text']);
      $base64 = htmlspecialchars($_POST['base64']);
      $url_path = htmlspecialchars($_POST['url_path']);
      $data = $this->update_twitter($text, $base64, $url_path);
      return $data;
      die();
      }else{
      require( SYS_DIR_PATH.'Facebook/autoload.php' );

      //Facebook App Settings
      $_APP_ID      = esc_attr( get_option('sys_app_id'));
      $_APP_SECRET  = esc_attr( get_option('sys_app_secret'));
      $_API_VERSION = esc_attr( get_option('sys_api_version'));
      $callback_url = esc_attr( get_option('sys_callback_url') );

      $fb = new Facebook\Facebook(array(
      'app_id'                => $_APP_ID,
      'app_secret'            => $_APP_SECRET,
      'default_graph_version' => $_API_VERSION,
      ));

      $helper = $fb->getRedirectLoginHelper();

    if (isset($_POST["update_fb"]) && $_POST["update_fb"] == '1') {
      $text = htmlspecialchars($_POST['text']);
      $token = htmlspecialchars($_POST['token']);
      $url_path = htmlspecialchars($_POST['path']);
      $link_array = explode('/',$url_path);
      $image = end($link_array);
      $path = SYS_DIR_PATH."cache/".$image;
      $data = $this->update_fb($text, $token, $path, $fb);
      return $data;
      die();
    }else{
      try {
      $accessToken = $helper->getAccessToken();
      }catch(Facebook\Exceptions\FacebookResponseException $e) {

      // When Graph returns an error
      _e($e->getMessage().' Click <a href="'.$url.'">here</a> to Try again!','sys');
      exit;
      }catch(Facebook\Exceptions\FacebookSDKException $e) {

      // When validation fails or other local issues
      _e($e->getMessage().' Click <a href="'.$url.'">here</a> to Try again!','sys');
      exit;
    }// End if Update FB

    if (isset($accessToken)) {
      // Logged in!
      $_SESSION['facebook_access_token'] = (string) $accessToken;
      $data = $this->overlay();
      return $data;
      die();                      
      } elseif ($helper->getError()) {
      // The user denied the request
      exit;
    }// End If AccessToken

    $permissions          = ['email', 'user_posts','publish_actions'];
    $loginUrl             = $helper->getLoginUrl($callback_url, $permissions);
    $_SESSION['loginUrl'] = $loginUrl;
    $twitter_url          = get_permalink( $post->ID ).'?method=tauth';
    $default_image        = esc_attr( get_option('sys_default_image') );

    $data  = '<div class="container">';
    $data .= '<div class="row">';
    $data .= '<div class="header">';
    $data .= '<h1>'.esc_attr( get_option('sys_heading') ).'</h1>';
    $data .= '<img class="profile" src="'.$default_image.'"/>';
    $data .= '</div>';
    $data .= '<div class="content">';
    $data .= '<br/>';
    $data .= '<p>'.esc_attr( get_option('sys_para') ).'</p>';
    $data .= '<a class="button button-primary" href="'.htmlspecialchars($loginUrl).'">'. __("Log in to Facebook","sys").'</a><br/>';
    $data .= '<a class="button button-twitter" href="'.htmlspecialchars($twitter_url).'">'. __("Sign in with Twitter","sys").'</a>';
    $data .= '</div>';
    $data .= '<footer class="footer">';
    $data .= '</footer>';
    $data .= '</div>';
    $data .= '</div>';
    return $data;
  }
  }
  }

  /**
  * Function name: update_twitter()
  * Function to tweet updated profile picture and message on Twitter
  * Parameters: $text (text to tweet), $base64 (base64 image for twitter), $url_path (url_path of image to display)
  */

  public function update_twitter($text, $base64, $url_path){
    $access_token = $_SESSION['access_token'];
    $connection = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
    $info = @getimagesize($url_path);
    $extension = @image_type_to_extension($info[2]);
    $loginUrl = $_SESSION['loginUrl'];

    $extension = str_replace('.','',$extension);

    $result = $connection->post('account/update_profile_image', array('image' => $base64.';type=image/'.$extension.';filename='.$id));

    // post a tweet
    $status = $connection->post(
    "statuses/update", [
        "status" => "$text"
    ]
    );

    $data  = '<div class="container">';
    $data .= '<div class="row">';
    $data .= '<div class="header">';
    $data .= '<h1>'.__("Thank you for your support!","sys").'</h1>';
    $data .= '<img class="profile" src="'.$url_path.'" alt="">';
    $data .= '</div>';
    $data .= '<div class="content">';
    $data .= '<br/>';
    $data .= __("Your new profile picture has been added to your timeline on Twitter, Would you like to also change your Facebook profile picture?<br/><br/>","sys");
    $data .= '</div>';
    $data .= '<a class="button button-primary" href="'.htmlspecialchars($loginUrl).'" style="color:#fff !important;">'. __("Log in to Facebook","sys").'</a>';
    $data .= '</div>';
    $data .= '</div>';
    return $data;
  }

  /**
  * Function name: manage_twitter_post()
  * Function for pre post display of Twitter Profile picture and message
  * Parameters: none
  */

  public function manage_twitter_post(){

    global $post;
    $pid = $post->ID;
    
    $access_token = $_SESSION['access_token'];
    $connection = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

    /* If method is set change API call made. Test is called by default. */
    $result = $connection->get('account/verify_credentials');

    $screen_name = $result->screen_name;
    $id = $result->id;

    // Access the profile_image_url element in the array
    $photo = str_replace('_normal','',$result->profile_image_url);

    $info = @getimagesize($photo);
    $extension = @image_type_to_extension($info[2]);

    copy($photo, SYS_DIR_PATH."cache/".$id.$extension);
    $path = SYS_DIR_PATH."cache/".$id.$extension;
    $service = 'twitter';

    $this->create_temp_image($id, $path, $service);
    
    $url_path =  SYS_URL_PATH."cache/".$id.$extension;

    $base64 = $this->base64_encode_image ($path);
    
    $sys_share_phrase = esc_attr( get_option('sys_share_phrase') );
    $data  = '<div class="container">';
    $data .= '<div class="row">';
    $data .= '<div class="header">';
    $data .= '<h1>'. __("You new profile picture is ready !","sys").'</h1>';
    $data .= '<img class="profile" src="'. $url_path.'" alt="">';
    $data .= '</div>';
    $data .= '<div class="content" style="margin-bottom:15px;">';
    $data .= '<form action="'.esc_attr(get_permalink($pid)).'" method="post" name="update_twitter">';
    $data .= '<label for="update"><h2>'.__("Add a supporting phrase to your timeline:","sys").'</h2></label>';
    $data .= '<textarea class="u-full-width" name="text" style="height: 120px !important;">'.$sys_share_phrase.'</textarea>';
    $data .= '<a class="button button-twitter" onclick="document.update_twitter.submit();">'.__("Tweet","sys").'</a>';
    $data .= '<input name="update_twitter" value="1" type="hidden">';
    $data .= '<input name="base64" value="'.$base64.'" type="hidden">';
    $data .= '<input name="url_path" value="'.$url_path.'" type="hidden">';
    $data .= '</form>';
    $data .= '</div>';
    $data .= '</div>';
    $data .= '</div>';

    return $data;
  }

  /**
  * Function name: base64_encode_image()
  * Function to convert image to base64 for Twitter
  * Parameters: $filename, $filetype
  */

  public function base64_encode_image ($filename=string,$filetype=string) {
    if ($filename) {
      $imgbinary = fread(fopen($filename, "r"), filesize($filename));
      return @base64_encode($imgbinary);
    }// End if
  }

  /**
  * Function name: twitter_auth()
  * Function to for Twitter login authorization
  * Parameters: none
  */

  public function twitter_auth(){
    global $post;
    $pid = $post->ID;
    $url = get_permalink($pid);

    $connection = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET);
    $request_token = $connection->getRequestToken(TWITTER_OAUTH_CALLBACK);
    $_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
    $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
    
    switch ($connection->http_code) {
      case 200:
        $url = $connection->getAuthorizeURL($token);
        header('Location: ' . $url); 
        break;
    
      default:
        $message = __('There was a problem connecting to Twitter, Click <a href="'.$url.'">here</a> to Try again!','sys');
        return $message;
    }// End Switch Case
  }

  /**
  * Function name: update_fb()
  * Function for post profile picture displayed on Facebook with message
  * Parameters: $text, $token, $path, $fb
  */

  public function update_fb($text, $token, $path, $fb){

    //Upload image
    $url = $this->upload_image_to_fb($path,$token,$fb,$text);

    $link_array = explode('/',$path);
    $image = end($link_array);
    $url_path = SYS_URL_PATH."cache/".$image;
    $twitter_url   = get_permalink( $post->ID ).'?method=tauth';
    $make_profile_pic = SYS_URL_PATH."images/make_profile_pic.png";

    $data  = '<div class="container">';
    $data .= '<div class="row">';
    $data .= '<div class="header">';
    $data .= '<h1>'.__("Thank you for your support!","sys").'</h1>';
    $data .= '<img class="profile" src="'.$url_path.'" alt="">';
    $data .= '</div>';
    $data .= '<div class="content">';
    $data .= '<br/>';
    $data .= __("Your new profile picture has been added to an album in your Facebook photos called <strong>'".esc_attr( get_option('sys_facebook_album_name'))."'</strong>. Click the button below to set the image as your profile picture (Facebook doesn't allow us to do this for you).","sys");
    $data .= '<br/>';
    $data .= __("<em>example:</em>&nbsp;","sys");
    $data .= '<img src="'.$make_profile_pic.'" alt="Make your Profile Picture"/><br/><br/>';
    $data .= '</div>';
    $data .= '<a class="button button-primary" href="'.htmlspecialchars($url).'" target="_blank" style="color:#fff !important;">'. __("Make your profile picture!","sys").'</a>';
    $data .= '<br/>'.__("OR","sys").'<br/>';
    $data .= '<a class="button button-twitter" href="'.htmlspecialchars($twitter_url).'">'. __("Sign in with Twitter","sys").'</a>';
    $data .= '</div>';
    $data .= '</div>';
    
    return $data;
    session_write_close();
  }

  /**
  * Function name: upload_image_to_fb()
  * Function to upload profile picture on Facebook with message
  * Parameters: $text, $token, $path, $fb
  */

  public function upload_image_to_fb($path,$token,$fb,$text)
  {

    $image = [
      'caption' => $text,
      'source' => $fb->fileToUpload($path),
    ];
    try {
      // Returns a `Facebook\FacebookResponse` object
      $response = $fb->post('/me/photos', $image, $token);
    } catch(Facebook\Exceptions\FacebookResponseException $e) {
      echo 'Update FB Graph returned an error: ' . $e->getMessage();
      exit;
    } catch(Facebook\Exceptions\FacebookSDKException $e) {
      echo 'Facebook SDK returned an error: ' . $e->getMessage();
      exit;
    }

    $graphNode = $response->getGraphNode();

    if(isset($graphNode["id"]) && is_numeric($graphNode["id"]))
      {
        $url = "https://www.facebook.com/photo.php?fbid=".$graphNode['id']."&type=3&makeprofile=1";
      }

    return $url;
  }

  /**
  * Function name: overlay()
  * Function to add overlay image
  * Parameters: none
  */

  public function overlay(){

    global $post;
    $pid = $post->ID;
    $token = $_SESSION['facebook_access_token'];
  	$output = $this->curl_access_fb_data($token);
  	$r = @json_decode($output, true);
    $id = $r['id'];
  	$path = SYS_DIR_PATH."cache/".$id.".jpg";
    $url_path = SYS_URL_PATH."cache/".$id.".jpg";
  	$_SESSION['path'] = $path;
    $_SESSION['id'] = $id;

  	//create temp image
    $service = 'fb';
    $this->create_temp_image($id, $path, $service);
  	
    $sys_share_phrase = esc_attr( get_option('sys_share_phrase') );
    $data  = '<div class="container">';
	  $data .= '<div class="row">';
    $data .= '<div class="header">';
	  $data .= '<h1>'. __("You new profile picture is ready !","sys").'</h1>';
	  $data .= '<img class="profile" src="'. $url_path.'" alt="">';
	  $data .= '</div>';
	  $data .= '<div class="content" style="margin-bottom:15px;">';
	  $data .= '<form action="'.esc_attr(get_permalink($pid)).'" method="post" name="update_facebook">';
	  $data .= '<label for="update"><h2>'.__("Add a supporting phrase to your timeline:","sys").'</h2></label>';
    $data .= '<textarea class="u-full-width" name="text" style="height: 120px !important;">'.$sys_share_phrase.'</textarea>';
		$data .= '<a class="button button-primary" onclick="document.update_facebook.submit();">'.__("Share on Facebook","sys").'</a>';
    $data .= '<input name="update_fb" value="1" type="hidden">';
    $data .= '<input name="token" value="'.$token.'" type="hidden">';
    $data .= '<input name="path" value="'.$url_path.'" type="hidden">';
		$data .= '</form>';
    $data .= '</div>';
    $data .= '</div>';
    $data .= '</div>';

    return $data;
  }

  /**
  * Function name: curl_access_fb_data();
  * Function to connect to FB using curl
  * Parameters: $token
  */

  public function curl_access_fb_data($token){

    $ch = @curl_init();
    @curl_setopt($ch, CURLOPT_URL, "https://graph.facebook.com/me?access_token=".$token);
    @curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = @curl_exec($ch);
    @curl_close($ch);

    return $output;
  }

  /**
  * Function name: create_temp_image();
  * Function to create tempory image
  * Parameters: $id, $path, $service
  */

  public function create_temp_image($id, $path, $service){

    $base_image = @imagecreatefrompng(SYS_DIR_PATH."images/template480.png");

    if($service == 'fb'){
      $photo = "http://graph.facebook.com/".$id."/picture?width=480&height=480";
      @copy ($photo, $path);
    }

    $this->resizeImage($path, 480, 480 );
    
    $photo = @imagecreatefromjpeg($path);
    $overlay_image_link = @esc_attr( get_option('sys_overlay_image'));
    $link_array = @explode('/',$overlay_image_link);
    $overlay_image = @end($link_array);
    $overlay = @imagecreatefrompng(SYS_DIR_PATH."images/".$overlay_image);
    @imagesavealpha($base_image, true);
    @imagealphablending($base_image, true);

    if(file_exists($path)){
      @unlink($path);
    }

    @imagecopyresampled($base_image, $photo, 0, 0, 0, 0, 480, 480, 480, 480);
    @imagecopy($base_image, $overlay, 0, 0, 0, 0, 480, 480);
    @imagejpeg($base_image, $path);
  }

  /**
  * Function name: resizeImage();
  * Function to resizeImage
  * Parameters: $filename, $max_width, $max_height
  */

  public function resizeImage($filename, $max_width, $max_height){

    @list($orig_width, $orig_height) = @getimagesize($filename);

    $width = $orig_width;
    $height = $orig_height;

    # taller
    if ($height < 480) {
      $width = ($max_height / $height) * $width;
      $height = $max_height;
    }

    # wider
    if ($width < 480) {
      $height = ($max_width / $width) * $height;
      $width = $max_width;
    }

    $image_p = imagecreatetruecolor($width, $height);

    $info = @getimagesize($filename);
    $extension = @image_type_to_extension($info[2]);

    switch ($extension) {
      case '.gif':
        $image = @imagecreatefromgif($filename);
        break;
      
      case '.jpeg':
        $image = @imagecreatefromjpeg($filename);
        break;
      
      case '.jpg':
        $image = @imagecreatefromjpeg($filename);
        break;
      
      case '.png':
        $image = @imagecreatefrompng($filename);
        break;
    }//End Switch Case

    if(file_exists($filename)){
      unlink($filename);
    }

    @imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $orig_width, $orig_height);

    @imagejpeg($image_p, $filename);
  }

}// end of Class WPSys

//Create object for WPSys Class
$wpSys = new WPSys();