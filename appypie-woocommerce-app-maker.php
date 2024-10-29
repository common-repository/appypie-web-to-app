<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.appypie.com/
 * @since             1.2.0
 * @package           Appypie_Web_To_App
 *
 * @wordpress-plugin
 * Plugin Name:       Appypie Web to App
 * Plugin URI:        http://www.appypie.com/
 * Description:       Upgrade Your Website into Android App and Get Published in App Store.
 * Version:           1.2.0
 * Author:            Av
 * Author URI:        http://www.appypie.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       appypie-web-to-app
 * Domain Path:       /languages
 */

      if (!defined( 'ABSPATH' ) ) {
        exit;
      }
	     // get countrycode
		 function getcountrycodeip() {
			$country_array = array('US','IN','GB','HK','IE','ZA','FR','ES','DE','PT','MX','BR','AE','QA','RU','KW','OM','JP','CO','AR','MY','AU','CA','NZ','IT');
	       function getIpAddrtoAll(){
     
				$ipaddress = '';
				if (isset($_SERVER['HTTP_CLIENT_IP']))
					$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
				else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
					$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
				else if(isset($_SERVER['HTTP_X_FORWARDED']))
					$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
				else if(isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
					$ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
				else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
					$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
				else if(isset($_SERVER['HTTP_FORWARDED']))
					$ipaddress = $_SERVER['HTTP_FORWARDED'];
				else if(isset($_SERVER['REMOTE_ADDR']))
					$ipaddress = $_SERVER['REMOTE_ADDR'];
				else
					$ipaddress = 'UNKNOWN';
				return $ipaddress;
		  }
		 $ip = getIpAddrtoAll(); 
		 $countryArray = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));  
		 $Allcountrycode = $countryArray->geoplugin_countryCode;
		//echo "<pre>";
		//print_r($countryArray);
		return $Allcountrycode;
	  }
       error_reporting(E_ALL & ~E_NOTICE);
       class AndroidWoocommerceAPI {
       
        public function __construct() {
			global $wpdb;
			register_activation_hook(__FILE__, array($this, 'android_plugin_activate'));
			register_deactivation_hook(__FILE__, array($this, 'android_plugin_deactivate'));
			add_action('admin_menu', array($this, 'add_androidapp_menu'));
			add_action('admin_init', array($this,'wpapp_addcss'));
			add_action('admin_footer', array($this, 'validater_js'));
			add_action('wp_ajax_verify_token', array($this, 'get_state_ajax_callback'));
			add_action('wp_ajax_nopriv_verify_token', array($this, 'get_state_ajax_callback'));
			add_action('wp_ajax_create_app', array($this, 'create_app_ajax_callback'));
			add_action('wp_ajax_nopriv_create_app', array($this, 'create_app_ajax_callback'));
			add_action('wp_ajax_payment_app', array($this, 'payment_app_ajax_callback'));
			add_action('wp_ajax_nopriv_payment_app', array($this, 'payment_app_ajax_callback'));
			update_option('mobilepay','https://snappy.appypie.com/mobileapp/mobile-pay');
			//add_filter('woocommerce_rest_check_permissions',array($this,'my_woocommerce_rest_check_permissions', 90, 4));
			apply_filters( 'woocommerce_rest_check_permissions', $permission, $context, $object_id, $post_type ); // $post_type = 'product', 'shop_order, 'shop_coupon' and so on.
			apply_filters( 'woocommerce_rest_check_permissions', $permission, $context, $object_id, $post_type);
			apply_filters( 'woocommerce_rest_check_permissions', $permission, $context, $object_id, $taxonomy ); // $taxonomy - 'product_cat, 'product_tag', and more attributes.
			apply_filters( 'woocommerce_rest_check_permissions', $permission, $context, 0, $object ); // $object = 'reports', 'settings', 'system_status', 'attributes', 'shipping_methods', 'payment_gateways', and 'webhooks'. */
			apply_filters( 'woocommerce_rest_check_permissions', $permission, $context, $object_id, 'product_review' );
			add_action('admin_enqueue_scripts', array($this,'ds_admin_theme_style'));
            add_action('login_enqueue_scripts', array($this,'ds_admin_theme_style'));
	    }
		
	    /* function my_woocommerce_rest_check_permissions($permission, $context, $object_id, $post_type){
		 return true;
		} */
	   
	    function android_plugin_activate() {
			// Plugin activation hook
		}

		function ds_admin_theme_style() {
			if (!current_user_can( 'manage_options' )) {
				echo '<style>.update-nag, .updated, .error, .is-dismissible { display: none; }</style>';
			}
		}
		
	    /* * Adding css file */
	    function wpapp_addcss() { 
		  wp_enqueue_style('wpapp_css', '/' . PLUGINDIR . '/appypie-web-to-app/list/css/style.css' );
          wp_register_style('prefix_font', '/' . PLUGINDIR . '/appypie-web-to-app/list/css/font.css');	
          wp_enqueue_style('prefix_font');	
          wp_register_style('prefix_payment', '/' . PLUGINDIR . '/appypie-web-to-app/list/css/payment.css');	
          wp_enqueue_style('prefix_payment');
          wp_register_style('prefix_fontello', '/' . PLUGINDIR . '/appypie-web-to-app/list/css/fontello.css');	
          wp_enqueue_style('prefix_fontello');	
          wp_register_style('prefix_appyslim', '/' . PLUGINDIR . '/appypie-web-to-app/list/css/appyslim.css');	
          wp_enqueue_style('prefix_appyslim');	
          wp_register_style('prefix_font-awesome.min', '/' . PLUGINDIR . '/appypie-web-to-app/list/css/font-awesome.min.css');	
          wp_enqueue_style('prefix_font-awesome.min');	 
		  wp_enqueue_script('bootstrap_js', '/' . PLUGINDIR . '/appypie-web-to-app/list/js/bootstrap.min.js');
		}
	
	   function validater_js(){
		  wp_enqueue_script('jquery.validate','/'.PLUGINDIR. '/appypie-web-to-app/list/js/jquery.validate.min.js', '', true);
	   }
		
       function add_androidapp_menu() {
	      add_menu_page('Mobile App', 'Mobile App', 'manage_options', 'android-app', array($this, 'android_app_main_page'),'');
	   }
	
   	function android_app_main_page(){
		
        if($_GET['action']=="checkout"){
			update_option('payment_created','enabled');
		}
		if($_GET['action']=="success"){
			update_option('paymentdone','enabled');
			echo $this->thankyouMsg();
			exit;
		}
		
		
		if($_GET['action']=="clearfix"){
			delete_option('app_created');
			delete_option('app_created');
			delete_option('paymentdone');
			delete_option('register');
			delete_option('app_created');
			delete_option('payment_created');
			delete_option('userEmail');
			delete_option('paymentdone');
		}
		if($_GET['action']=="created"){
			$loginString = $_GET['loginString'];
			update_option('register','enabled');
			$userData = $this->createSnappyUser($loginString);
			update_option('userInfo',$userData);
			?>
			<script> 
			  window.opener.location.reload(true);
              window.close();
			</script>
    		<?php
		}
       
		if(!empty(get_option('paymentdone'))){
			 echo $this->thankyouMsg();
		}
	    else if(!empty(get_option('payment_created'))){
			echo $this->paymentMethodApp();
		}
	    else if(!empty(get_option('app_created'))){
			echo $this->selectTrial();
		}
		else if(!empty(get_option('register'))){
		   echo $this->androidApp_newform();
		  
		}else{
			echo $this->appypieSignupForm();
		}

	}
	
	 /* function countryCode(){
		$service_url = 'https://snappy.appypie.com/webservices/ClientDetails.php';
		$curl = curl_init($service_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		$curl_response = curl_exec($curl);
		curl_close($curl);
		$pricingArr = json_decode($curl_response,true);
		$country_code = $pricingArr['CountryCode'];
		return $country_code;
	}  */
	//hit pricing api
	function planPrice(){
		$country_array = array('US','IN','GB','HK','IE','ZA','FR','ES','DE','PT','MX','BR','AE','QA','RU','KW','OM','JP','CO','AR','MY','AU','CA','NZ','IT');
		if($_GET['appcountry']!='') {
			$country_code = $_GET['appcountry'];
			if(!in_array($country_code,$country_array)) {
				$country_code = 'US';
			}
		}
		else {
	
		    $country_code = getcountrycodeip();

			if($country_code=='') {
				$country_code = 'US';
			}
			if(!in_array($country_code,$country_array)) {
				$country_code = 'US';
			}
		}

    }
	
	function selectTrial(){
		$pluginURL = plugins_url();
	    $iconFolderPath = $pluginURL . '/appypie-web-to-app/list/images';
		$country_array = array('US','IN','GB','HK','IE','ZA','FR','ES','DE','PT','MX','BR','AE','QA','RU','KW','OM','JP','CO','AR','MY','AU','CA','NZ','IT');
		if($_GET['appcountry']!='') {
			$country_code = $_GET['appcountry'];
			if(!in_array($country_code,$country_array)) {
				$country_code = 'US';
			}
		}
		else {
		
			$country_code = getcountrycodeip();

			if($country_code=='') {
				$country_code = 'US';
			}
			if(!in_array($country_code,$country_array)) {
				$country_code = 'US';
			}
		}
		$html = '<div class="wrap main_wrapper">
		<div class="qr_wrapper">
		<div id="previewtllp"></div>
		<div id="spanSC" class="ft-wrapper">
		   <div class="container">
			  <div class="row">
				 <div class="col-md-12">
					<div class="full_width">
					   <div class="frd-wrapper">
						  <div class="row">
							 <div class="sec-head">
								<h2 class="text-center">Buy Now to edit and test your app</h2>
								
							 </div>
							 <div class="col-sm-4 col-md-4 plan-tab yellow-tab ">
								<div class="price-header">
								   <a href="?page=android-app&action=checkout" class="btn btn-success trial-btn">
								   <span>Buy Now<span class="icon-right_ions"><img src="'.$iconFolderPath.'/arrow-right.svg"></span></span>
								   30 Days Money Back Guarantee</a>
								</div>
								<div class="plan-inner-content">
								   <div class="selected">
									  <ul class="plan-fec clearfix">
										 <li class="tollpreview">
											<small>Unlimited App Editing</small>
											<div class="custom-tooltip" style="display:none;">
											   <div class="tolltitle">App editing</div>
											   <p ng-if="premium_page!=1" class="tollsubtitle">Our Basic plan places no limits on app editing.</p>
										 </li>
										 <li class="tollpreview">	<small>Basic Features</small>
										 <div class="custom-tooltip" style="display:none;"><div class="tolltitle">Basic features</div><p class="tollsubtitle">Our basic plan only supports basic features. Please note that Premium features aren&apos;t supported with this plan.</p></div></li>
										 <li class="tollpreview"><small>Google Analytics</small>
										 <div class="custom-tooltip" style="display:none;"><div class="tolltitle">Platforms supported</div>
										 <p ng-if="premium_page!=1" class="tollsubtitle">Grow your business beyond the local boundaries. Our Basic plan supports PWA and Android.</p></li>	
										 <li class="tollpreview">
										 <small>Remove Appy Pie Ads</small>
										 <div class="custom-tooltip" style="display:none;"><div class="tolltitle">Free from Appy Pie Ads</div><p class="tollsubtitle">Enjoy an app &amp; website that’s completely your own, free from Appy Pie ads.</p></div>
										 </li>	
										 <li class="tollpreview">
										 <small>Unlimited Bandwidth &amp; Hosting</small>
										 <div class="custom-tooltip" style="display:none;"><div class="tolltitle">Unlimited Bandwidth and Storage</div><p class="tollsubtitle">Scale with confidence. There are no caps on your bandwidth or storage usage.</p></div>
										 </li>	
										 <li class="tollpreview">	<small>24*7 Support</small>
										 <div class="custom-tooltip" style="display:none;"><div class="tolltitle">24/7 Customer Support</div><p class="tollsubtitle">Appy Pie’s Award-Winning customer care team is ready to help you 24/7.</p></div></li>				
									  </ul>
									  <div id="previewtllp"></div>
									  </div>
									  </div>
								   </div>
								   <div class="col-sm-12 col-md-8">
									<div class="subs-why-choose-us mob-app">
									<div class="contentText" tabindex="0">
									<h2>Why choose us ?</h2><h3>30-day Satisfaction Guarantee with Money Back</h3>
									<p>If you re not satisfied with your products we will issue a full refund, no questions asked.</p>
									</div>
									<div class="image"> <img src="https://d2wuvg8krwnvon.cloudfront.net/newui/images/save-money.svg"> 
									</div>
									</div>
									  <div class="fd-items">
										 <ul>
											<li>
											   <span class="appyslimcustom"><img src="'.$iconFolderPath.'/bandwidth.svg"></span>
											   <p>Unlimited Bandwidth &amp; Hosting</p>
											</li>
											<li>
											   <span class="appyslimcustom"><img src="'.$iconFolderPath.'/basic-features.svg"></span>
											   <p>Basic Features</p>
											</li>
											<li>
											   <span class="appyslimcustom"><img src="'.$iconFolderPath.'/push-notifications.svg"></span>
											   <p>10000/mo Push Notifications</p>
											</li>
											<li>
											   <span class="appyslimcustom"><img src="'.$iconFolderPath.'/ads1-free.svg"></span>
											   <p>Ads Free</p>
											</li>
											
											<li>
											   <span class="appyslimcustom"><img src="'.$iconFolderPath.'/support.svg"></span>
											   <p>24*7 Support</p>
											</li>
											<li>
											   <span class="appyslimcustom"><img src="'.$iconFolderPath.'/google-analytics.svg"></span>
											   <p>Google Analytics</p>
											</li>
										 </ul>
									  </div>
								   </div>
								   <div class="partner-strip">
									  <div class="col-md-20"><img  src="'.$iconFolderPath.'/mc-fee.png"></div>
									  <div class="col-md-20"><img  src="'.$iconFolderPath.'/pci.png"></div>
									  <div class="col-md-20"><img  src="'.$iconFolderPath.'/mc-fee.png"></div>
									  <div class="col-md-20"><img  src="'.$iconFolderPath.'/visa.png"></div>
									  <div class="col-md-20"><img  src="'.$iconFolderPath.'/master-card.png"></div>
								   </div>
								</div>
								<div class="user-footer-container">
								   <p class="fs10 text-center offsetbottom15 tctext offsettop10">By adding/updating info, you agreed to our <a id="termsAndAgreementR" class="cursor-pointer termCondie" ">T&amp;C</a> and <a class="cursor-pointer termCondie" id="privacyPolicyAgreementR"">Privacy Policy</a></p>
								</div>
							 </div>
						  </div>
					   </div>
					</div>
				 </div>
			  </div>
		   </div>
		</div>';
     ?>
	 <link rel="stylesheet" href="<?php echo $pluginURL; ?>/appypie-web-to-app/list/css/bootstrap.min.css">
	<script src="<?php echo $pluginURL; ?>/appypie-web-to-app/list/js/jquery.min.js"></script>
	<script>
	function imagePreview (){
	$(".tollpreview > small").hover(function(e){
	var par = $(this).parent();
	var  text = $( ".tolltitle", par ).html();
	var subtitle = $( ".tollsubtitle", par ).html();
	$("#previewtllp").append("<h4 class='tolltitle'>"+text +"</h4><p class='tollsubtitle'>"+ subtitle +"</p>");
	$("#previewtllp").addClass("animate-zoom").css("top",(e.pageY) + "px").css("left",(e.pageX) + 0 + "px");
	},
	function(){ $("#previewtllp").removeClass("animate-zoom").empty(); });
	$( ".tollpreview > small" ).scroll(function() {
	$( "#previewtllp" ).hide();
	});
	$(".tollpreview").mousemove(function(e){
	var mousex = e.pageX - 150;
	var mousey = e.pageY - 50;
	var tipVisX = $(window).width() - (mousex);
	var tipVisY = $(window).height() - (mousey);
	if ( tipVisX < 10 ) {
	mousex = e.pageX - 10;
	} if ( tipVisY < 10 ) {
	mousey = e.pageY - 10;
	}
	$("#previewtllp").css({top: mousey, left: mousex });
	});
	};
	setTimeout(function(){
	  imagePreview();
	 }, 3000);
    </script>
	<?php 
	 return $html;
}
function get_state_ajax_callback(){
	global $wpdb;
	$plugins_url = plugin_dir_url( __FILE__ ).'/list/countries';
	$filename = $_POST['fileName'];
	$statejson = $filename.'.json';
	$str = file_get_contents($plugins_url.'/'.$statejson);
    $json = json_decode($str, true);
	if($json){
		echo'<select class="form-control margin-top-pay-20" name="state" id="state">
		<option value="">State</option>';
		foreach($json as $option){?>
		  <option value="<?php echo $option['name']; ?>"><?php echo $option['name']; ?></option>
		<?php
		}
		echo'</select>';
	}else{
		echo'<input type="text" name="state" id="state" class="form-control margin-top-pay-20" placeholder="State">';
	}
   die();
}
function payment_app_ajax_callback(){
        global $wpdb;
		$country_array = array('US','IN','GB','HK','IE','ZA','FR','ES','DE','PT','MX','BR','AE','QA','RU','KW','OM','JP','CO','AR','MY','AU','CA','NZ','IT');
		if($_GET['appcountry']!='') {
			$country_code = $_GET['appcountry'];
			if(!in_array($country_code,$country_array)) {
				$country_code = 'US';
			}
		}
		else {
		
			$country_code = getcountrycodeip();
			if($country_code=='') {
				$country_code = 'US';
			}
			if(!in_array($country_code,$country_array)) {
				$country_code = 'US';
			}
		}
		$appId = get_option('appId');
		$paymentUrl = get_option('mobilepay');
		$userInfo =  get_option('userInfo');
		//$userEmail = get_option('userEmail');
        $fname = $_POST['fname'];
		$lname = $_POST['lname'];
		$fullName = $fname.' '.$lname;
		$userEmail = "murli@appypie.com";
		$address = $_POST['address'];
		$city = $_POST['city'];
		$phone = $_POST['phone'];
		$state = $_POST['state'];
		$country = $_POST['country'];
		//$countryCode = $country_code;
		$zip = $_POST['zip'];
		$agreeForContact = $_POST['agreeForContact'];
		$planPeriod = $_POST['planPeriod'];
		$planId = $_POST['planId'];
		$countrycodes = "IN";
		if($countrycodes == "IN"){
		$paymentMethod = $_POST['paymentMethod'];
		} else {
		$paymentMethod = $_POST['paymentMethod-ccavenue'];
		}
		$appType = $_POST['appType'];
		$trialButton = $_POST['trialButton'];
		$url = site_url();
		$obj = array(
			'appId'=>$appId,
			'userInfo'=>$userInfo,
			'fname'=>$fname,
			'lname'=>$lname,
			'fullName'=>$fullName,
			'userEmail'=>$userEmail,
			'address'=>$address,
			'city'=>$city,
			'phone'=>$phone,
			'state'=>$state,
			'country'=>$country,
			'countryCode'=>$country_code,
			'zip'=>$zip,
			'agreeForContact'=>$agreeForContact,
			'planPeriod'=>$planPeriod,
			'planId'=>$planId,
			'paymentMethod'=>$paymentMethod,
			'appType'=>$appType,
			'paymentFrom'=>"wordpressplugin",
			'successUrl'=>$url."/wp-admin/admin.php?page=android-app&action=success",
			'cancelUrl'=>$url."/wp-admin/admin.php?page=android-app&action=paymentfail",
			'trialButton'=>$trialButton
		);
		$jsonObj = json_encode($obj); 
		//print_r($jsonObj);
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => $paymentUrl,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST =>'POST',
		  CURLOPT_POSTFIELDS =>$jsonObj,
		  CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json',
		  ),
		));
		$response = curl_exec($curl);
		$result = json_decode($response, true);
		$res = array();
		$ccavenueUrl = $result['fileId'];
		if($result['status'] == 'success'){
		   $res['url'] = $result['fileId'];
		   $res['status'] = 200;
		  } else if($result['code']=='success'){
		   $res['url'] = $result['url'];
		   $res['status'] = 200;
		  } else {
		   $res['status'] = 400;
		  }
		echo json_encode($res);
	die;
}
function create_app_ajax_callback(){
	global $wpdb;
	$appnameErr = $emailErr =  "";
	
	 if(isset($_POST)){

		 if(!empty($_FILES['app_icon']['name'])){
			 $imageFileType = explode('.',$_FILES['app_icon']['name']);
			 $imageFileType = str_replace(' ','-',$imageFileType);
			 $image_base64 = base64_encode(file_get_contents($_FILES["app_icon"]["tmp_name"]));
			 $app_icon = 'data:image/'.$imageFileType[1].';base64,'.$image_base64;
		 } 
		 if(!empty($_FILES['app_splash']['name'])){
			 $imageFileType_splash = explode('.',$_FILES['app_splash']['name']);
			 $imageFileType_splash = str_replace(' ','-',$imageFileType_splash);
			 $image_splash_base64 = base64_encode(file_get_contents($_FILES["app_splash"]["tmp_name"]));
			  $app_splash_icon = 'data:image/'.$imageFileType_splash[1].';base64,'.$image_splash_base64;
		 } 
		 if(!empty($_FILES['app_bgc']['name'])){
			 $imageFileType_bgc = explode('.',$_FILES['app_bgc']['name']);
			 $imageFileType_bgc = str_replace(' ','-',$imageFileType_bgc);
			 $image_bgc_base64 = base64_encode(file_get_contents($_FILES["app_bgc"]["tmp_name"]));
			 $app_bgc_icon = 'data:image/'.$imageFileType_bgc[1].';base64,'.$image_bgc_base64;
		 } 
		 if(empty($_POST['app_name'])){
			 $appnameErr = "App Name is required";
		 }else {
               $appname = $_POST['app_name'];
         }
		 
          wp_get_current_user();
          $email = $current_user->user_email;
		  $status = 2 ;
		  $user_id = $current_user->ID;
		  $description = "AcessTokenKey";
		  $permissions = "read_write";	
		  if (is_plugin_active( 'woocommerce/woocommerce.php')) {		  
			  $consumer_key     = 'ck_' . wc_rand_hash ();
			  $consumer_secret = 'cs_' . wc_rand_hash ();
			  $status           = 2 ;
			  $truncated_key = substr($consumer_key , -7);
			  $key = $wpdb->get_row( $wpdb->prepare("
				SELECT consumer_key, consumer_secret, permissions
				FROM {$wpdb->prefix}woocommerce_api_keys
				WHERE user_id = %d
			  ", $user_id), ARRAY_A);
			  if(!$key['consumer_key'] && !$key['consumer_secret']){
					$tablename = $wpdb->prefix . "woocommerce_api_keys";
					$sql = $wpdb->prepare("INSERT INTO `$tablename` (`user_id`, `description`, `permissions`, `consumer_key`, `consumer_secret`, `truncated_key`) values (%d, %s, %s, %s, %s, %s)", $user_id, $description, $permissions, $consumer_key, $consumer_secret, $truncated_key);
					$wpdb->query($sql);
			  }
			}
			$userInfo = get_option('userInfo');
			$sessionId = "session_".time();
			$appData = array(
			'intendType' => array(
			 'name' => 'Online Store',
			 'slug' => 'ecommerce',
			 'innerServices' => array(
			  'name'=> 'Store',
			  'slug' => 'ecommerce'
			   ),
			 ),
			'apAndroidLocale'=>'en',
			'appName'=>$_POST['app_name'],
			'app_layout'=>'new',
			'category'=>'6',
			'devicetype'=>'Android',
			'icon' => array(
			 'image' =>$app_icon
			),
			'background' => array(
			 'type' => 'custom',
			 'image' =>$app_bgc_icon
			),
			'splash' => array(
			 'type' => 'custom',
			 'image' =>$app_splash_icon
			),
			'layout'=>'matrix',
			'sessionId'=>$sessionId,
			"themeId"=> "1"
           );
		   $siteUrl = site_url();
		   $apiUrl = site_url().'/wp-json/wp/v2';
		   $blog_title = get_bloginfo( 'name' );
		   if (is_plugin_active( 'woocommerce/woocommerce.php')) {
		      $appData['consumer_key'] = $consumer_key;
			  $appData['consumer_secret'] = $consumer_secret;
			  $appData['appPages'] = array("woocommerce");
			  $appData['clientSuggestedPage'] = $blog_title;
			  $appData['siteUrl'] = $siteUrl;
			  $apiUrl = site_url().'/wp-json/wp/v2/product';
			  $appData['apiUrl'] = $apiUrl;
			} else {
			  $appData['appPages'] = array("wordpress");
			  $appData['clientSuggestedPage'] = $blog_title;;
			  $appData['siteUrl'] = $siteUrl;
			  $apiUrl = site_url().'/wp-json/wp/v2/pages';
			  $appData['apiUrl'] = $apiUrl;
			}
		   $msg = $this->addRecommendedPages($appData);
		   $appDetail = $this->storeVariableTodb($userInfo,$sessionId);
		   $store = json_decode($appDetail,true);
		   $appUrl = $store['testAreaLink'];
		   $appId = $store['appId'];
		   if($appUrl){
			  update_option('appId',$appId);
			  update_option('app_url',$appUrl);
		   }
		   if($msg=='success'){
			 update_option('app_created','app_created');
			 echo $status = 200;
		   }else{
			 echo $status = 400;
		   }
	 }
	die;
}


 function paymentMethodApp(){
     $country_array = array('US','IN','GB','HK','IE','ZA','FR','ES','DE','PT','MX','BR','AE','QA','RU','KW','OM','JP','CO','AR','MY','AU','CA','NZ','IT');
		if($_GET['appcountry']!='') {
			$country_code = $_GET['appcountry'];
			if(!in_array($country_code,$country_array)) {
				$country_code = 'US';
			}
		}
		else {
           // echo "surendra";
			 
			$country_code = getcountrycodeip();

			if($country_code=='') {
				$country_code = 'US';
			}
			if(!in_array($country_code,$country_array)) {
				$country_code = 'US';
			}
		}
 
	 $service_url = 'https://snappy.appypie.com/mobileapp/appypie-pricing/language/en/countryCode/'.$country_code;
	$curl = curl_init($service_url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_POST, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	$curl_response = curl_exec($curl);
	
	curl_close($curl);
	$pricingArr = json_decode($curl_response,true);
	
	 $vatPercentage1 = $pricingArr['vatPercentage'];
	 $currencySymbol = $pricingArr['currencySign'];
	 //US
	 $apponlyMonthly = $pricingArr['lifetimePricingPageArrDetail']['yearly']['oneMonthOfyearly'];
	 $appyearlyPrice = $pricingArr['lifetimePricingPageArrDetail']['Gold']['yearly'];
	 //IN
	 $appMonthlyPrice = $pricingArr['lifetimePricingPageArrDetail']['Gold']['yearly'];
	 $vatPercentage = $appMonthlyPrice * $vatPercentage1 / 100;
	 $subtotal = $vatPercentage + $appMonthlyPrice;
	 
	 //US
	 $monthData = $appyearlyPrice;
	 $monthlyPrice = round($monthData,2); 
	 $vatPercentageGst = $monthlyPrice * $vatPercentage1 / 100;
	 $subtotals = $vatPercentageGst + $monthlyPrice;
	 $originalCountryCode = $pricingArr['countryCode'];

	 if($_GET['action']=="paymentfail"){
		 $msg = "
		 <div class='alert alert-danger text-center' role='alert'>Your payment has been Declined!,
		     <p>An error occurred during this purchase. Please try again later. If this problem persists contact <a href='mailto:support@appypie.com'>support@appypie.com.</a>.</p>
		 </div>";
	 }else{
		 $msg = "";
	 }
	 $pluginURL = plugins_url();
	 $iconFolderPath = plugin_dir_url( __FILE__ ). '/list/images';
	 $countryfile = plugin_dir_url( __FILE__ ).'/list/countries.json';
	 $str = file_get_contents($countryfile);
     $json = json_decode($str, true);
	 $i=0;
	 
	$appId = get_option('appId');
	$userId = get_option('userId');
	 // get IP Addres
	function getIpAddresswoocommerces(){
		$ipaddress = '';
		if (isset($_SERVER['HTTP_CLIENT_IP']))
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_X_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		else if(isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
			$ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
		else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		else if(isset($_SERVER['REMOTE_ADDR']))
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
    }
	  $IP = getIpAddresswoocommerces();
	  
	$countryArray = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $IP));
	//print_r($countryArrays);
	    //$geoplugin_status = $countryArrays->geoplugin_status;
		$geoplugin_regionName = $countryArrays->geoplugin_regionName;
		$countryCode = $originalCountryCode;
	 $options = "";
	 if($json){
		 foreach($json as $option){
		 	 $selected = $option['code'] == $countryCode ? "selected='selected'" : "";
		 	 if($option['code']==$countryCode){
		 	 	$stateName = $option['filename'];
		 	 }
			 $options .="<option value=".$option['code']." ".$selected."  filename=".$option['filename']."> ".$option['name'].'('.$option['code'].')'." </option>";
		 }
	 }
   
	$plugins_url = plugin_dir_url( __FILE__ ).'/list/countries';
	$filename = $stateName;
	$statejson = $filename.'.json';
	$stData = file_get_contents($plugins_url.'/'.$statejson);
    $jsonSt = json_decode($stData, true);

    $stOption = "";
    if($jsonSt){
    	foreach($jsonSt as $stoption){
    	 $selected = $stoption['name'] == $geoplugin_regionName ? "selected='selected'" : "";
    	 $stOption .="<option value=".$stoption['name']." ".$selected.">".$stoption['name']."</option>";
     }
    }
	//echo $countryCode2 = "IN";
	 echo"<div id='paymenterror' style='diplay:none; color:red; text-align:center;'></div>"; 
	  if($country_code == "IN"){
	 $html = '<div class="wrap main_wrapper">
	<div class="blackoverlay" id="overlay" style="display: none;"> </div>
	<img src="'.$iconFolderPath.'/loading.gif" class="loaderjob" id="loaderjob" style="display: none;">
	<div class="qr_wrapper payment_wrp">
	<div id="container_trail" class="container">
	<div class="row" >
	<div class="col-md-12">
	  <div class="payment-main-container">
		 <div class="maincontainer">
			<div class="outer-container">
			   <div class="wrapper_width">
				  <h1>Payment Information</h1>
				  <div class="row second-option">
					 <div class="col-xs-12">
						<div class="white-strip pay-wrapper trial-app">
						   <div class="tab-container">
							  <div class="tab">
								 <div class="col-xs-12">
									<div class="full-width">
									   <div class="int-white-container">
										  <div class="row">
											 <div class="col-md-4 col-xs-12 custom-aside-payment pull-right">
												<h3 class="pltinume___plane"> Gold Plan Benefits</h3>
												<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
												   <div class="row">
													  <div class="col-md-3 col-sm-4 col-xs-5 dollar-blackbtn">Plan</div>
													  <div class="col-md-9 col-sm-8 col-xs-7 white-striptxt"> <span class="ng-binding" style="text-transform: capitalize;">Gold</span></div>
												   </div>
												</div>
												<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
												   <div class="row">
													  <div class="col-md-3 col-sm-4 col-xs-5 dollar-blackbtn">Billed</div>
													  <div class="col-md-9 col-sm-8 col-xs-7 white-striptxt">
														 <span style="display:none">
														 <label class="radio-inline">
														 <input type="radio" value="monthly" name="planPeriod">
														 Monthly 
														 </label>
														 <label class="radio-inline">
														 <input type="radio" value="yearly" name="planPeriod">
														 Yearly 
														 </label>
														 </span> 
														 <span style="text-transform: capitalize;">Yearly</span> 
													  </div>
												   </div>
												</div>
												<div class="col-xs-12">
												   <div class="row">
													  <div class="col-md-8 col-sm-9 col-xs-8 dollar-blackbtn">Mobile App</div>
													  <div class="col-md-4 col-sm-3 col-xs-4 white-striptxt"> <span>'.$currencySymbol.''.$appMonthlyPrice.'</span> </div>
												   </div>
												</div>
												<!--<div class="summaryList">
												   <label class="col-sm-4 col-xs-5 dollar-blackbtn col-md-8">Add-On Package </label>
												   <span id="summaryPlan" class="col-sm-8 col-xs-7 white-striptxt col-md-4">
													  <div><span>'.$currencySymbol.''.$appMonthlyPrice.'</span> </div>
												   </span>
												</div>-->
												<!--<div class="summaryList">
												   <div class="dollar-blackbtn col-xs-12">
													  <small style="font-size:10px;display:block;color: #676767;">
														 <p class="offsetbottom5 text_wrp">We’ve added Add-On Package. Here’s why!</p>
														 <ul class="addonsinfolist">
															<li class="offsetbottom5" style="font-weight: normal;">White-label your app by opting for our Add-On package and removing Appy Pie branding from the entire app.</li>
															<li class="offsetbottom5" style="font-weight: normal;">Google and Apple keep coming up with updates periodically. Every time there is an update, you would have to re-submit your app and each resubmission is chargeable. With Add-On Package you get unlimited resubmissions for no extra cost.</li>
															<li class="offsetbottom5" style="font-weight: normal;">It is natural to run into unexpected trouble at some point in time. With Add-On Package you get a dedicated account manager to help you through every step of the way.</li>
														 </ul>
														 <p class="text_wrp" style="font-weight: normal;">We highly recommend our Add-On Package, but it’s optional for you.</p>
													  </small>
												   </div>
												</div>-->
												<div class="row">
												   <div class="col-xs-12">
													  <div class="lightgrey-strip">
														 <div class="col-md-4 col-sm-5 col-xs-8 dollar-blackbtn"><span class="text-left">IGST: (18%)</span> </div>
														 <div class="col-md-8 col-sm-7 col-xs-4 white-striptxt"> <span>'.$currencySymbol.''.$vatPercentage.'</span> </div>
													  </div>
												   </div>
												   <div class="col-xs-12">
													  <div class="grey-strip">
														 <div class="col-md-8 col-sm-9 col-xs-8 whitetxt">Amount to be paid</div>
														 <div class="col-md-4 col-sm-3 col-xs-4 dollar-btn">'.$currencySymbol.''.$subtotal.'</div>
													  </div>
													  <div class="clearfix"></div>
												   </div>
												</div>
												<div class="pos-rel monay-back-container">
												   <div class="moneyBackpayment coup_30day hidden-sm hidden-xs">
													  <div class="moneyBackbg priceBackbg"> <big>30</big><br>
														 <span class="days">DAYS</span><br>
														 <span class="moneyBack-txt">GUARANTEE</span> 
													  </div>
													  <p class="fs11 text-center offsettop10"><strong>30-Day Money Back Guarantee</strong></p>
													  <p></p>
												   </div>
												</div>
											 </div>
											 <div class="col-md-8 col-xs-12 paymentborder pos-rel">
												
												<form class="form-horizontal" id="paymentmode" method="post" action="admin.php?page=android-app&action=payment" >
												<div class="form-group">
												  <label for="fnameError" class="col-md-3 col-sm-3 col-xs-12 control-label">Name</label>
												  <div class="col-md-4 col-sm-4 col-xs-12">
													<input type="text" name="fname" id="fname" class="form-control" placeholder="First Name" />
												  </div>
												  <div class="col-md-4 col-sm-4 col-xs-12 padding-left0">
													<input type="text" name="lname" id="lname" class="form-control lastname-style" placeholder="Last Name" />
												  </div>
												</div>
												<div class="form-group">
												  <label for="" class="col-md-3 col-sm-3 col-xs-12 control-label"> Billing Address</label>
												  <div class="col-md-4 col-sm-4 col-xs-12">
													<textarea name="address" id="address" style="resize: none;" class="height90 form-control align-left" placeholder="Address"></textarea>
												  </div>
												  <div class="col-md-4 padding-left0 col-sm-4 col-xs-12">
													<div class="payment-row">
													  <input type="text" name="city" id="city" class="form-control" placeholder="City" />
													  <span id="statelist">
													  <select class="form-control margin-top-pay-20" name="state" id="state">
														<option value="">State</option>
														'.$stOption.'
													  </select>
													  </span>
													</div>
												  </div>
												</div>
												<div class="form-group">
												  <label for="" class="col-md-3 col-sm-3 col-xs-3 disnone control-label"> </label>
												  <div class="col-md-4 col-sm-4 col-xs-12">
													<input type="text" name="zip" id="zip" class="form-control" placeholder="Zip Code" />
												  </div>
												  <div class="col-md-4 col-sm-4 col-xs-12 phone padding-left0">
													<select name="country" id="country" class="form-control">
													  <option value="" >Country</option>
													  '.$options.'
													</select>
												  </div>
												</div>
												<div class="form-group">
												  <label for="" class="col-md-3 col-sm-3 col-xs-3 disnone control-label"> Phone</label>
												  <div class="col-md-4 col-sm-4 col-xs-12">
													<input type="text" name="phone" id="phone" class="form-control" placeholder="Phone" />
												  </div>
												  <div class="col-md-4 col-sm-4 col-xs-12"></div>
												</div>
												<div class="form-group">
												  <div class="col-md-3"></div>
												  <div class="col-md-9 col-xs-12 grayInput" style="font-size: 12px !important;">
													<input type="checkbox" name="agreeForContact" id="agreeForContact" value="1" checked /> <span class="providingPayment"> By providing your phone number you are giving us the permission to contact you.</span>
												  </div>
												</div>
											  
											  <div class="row">
												<div class="newCheckout col-md-12 col-sm-12 col-xs-12">
												  <div class="pos-rel btn-wrap" style="width: 50%; margin: 0 auto;">
													<input type="submit" class="continue-btn purchaseBtn" name="pay" id="pay" value="Complete Secure Payment">
												  </div>
												  <div class="offsettop5 left-text p-left">
													<div class="securePayment"><span class="appyslimsecurity"><img src="'.$iconFolderPath.'/safe-and-secure.svg"></span>Safe &amp; Secure</div>
												  </div>
												  <input type="hidden" name="appId" id="appId" value="'.$appId.'">
												  <input type="hidden" name="countryCode" id="countryCode" value="'.$country_code.'">
												  <input type="hidden" name="planPeriod" id="planPeriod" value="yearly">
												  <input type="hidden" name="planId" id="planId" value="2">
												  <input type="hidden" name="paymentMethod" id="paymentMethod" value="ccavenue">
												  <input type="hidden" name="appType" id="appType" value="upgrade">
												  <input type="hidden" name="trialButton" id="trialButton" value="0">
												   <input type="hidden" name="action" value="payment_app">
												  </form>
												<!--<div class="gstin-section">
												   <div class="gstin-section">
													  <div class="row fs14">
														 <div class="col-xs-12"> <span><a href="javascript:void(0)">Buying for your business?</a></span> </div>
													  </div>
												   </div>
												   <div class="gst_main_wrp" style="display:none">
													  <div class="form-group">
														 <div class="col-xs-12">
															<h3 class="buying_busness">Buying for your business <a class="fs11" href="javascript:void(0)">(<i>Cancel</i>)</a></h3>
														 </div>
													  </div>
													  <div class="form-group">
														 <label for="" class="col-md-3 col-sm-3 col-xs-12 control-label">Company</label>
														 <div class="col-md-9 col-sm-9 col-xs-12" style="margin-bottom: 0;">
															<input type="text" id="companyError" class="form-control ng-pristine ng-untouched ng-valid" placeholder="Company name">
														 </div>
													  </div>
													  <div class="form-group">
														 <label for="" class="col-md-3 col-sm-3 col-xs-12 control-label">GST</label>
														 <div class="col-md-9 col-sm-9 col-xs-12" style="margin-bottom: 0;">
															<input type="text" id="gstNumberError" ng-model="billing.gstNumber" class="form-control ng-pristine ng-untouched ng-valid" placeholder="GST Number (optional)">
														 </div>
													  </div>
												   </div>
												</div>-->
												<!--<div class="row">
												   <div class="newCheckout col-md-12 col-sm-12 col-xs-12">
													  <div class="pos-rel btn-wrap" style="width: 50%;margin: 0 auto;">
														 <button type="button" class="continue-btn purchaseBtn"><big><b>Complete Secure Payment</b></big></button>
														 <div class="continue-btn payment_paypal_loading_dots" align="center">
															<span></span><span></span><span></span>
														 </div>
													  </div>
													  <div class="offsettop5 left-text p-left">
														 <div class="securePayment"> <span class="appyslim-security-confirm-shield"></span>Safe &amp; Secure</div>
													  </div>
												   </div>
												</div>-->
												<div class="partner-strip" ng-show="trialButton==1">
												   <div class="col-md-20"><img src="https://d2wuvg8krwnvon.cloudfront.net/newui/images/secured.png" /></div>
												   <div class="col-md-20"><img src="https://d2wuvg8krwnvon.cloudfront.net/newui/images/pci.png" /></div>
												   <div class="col-md-20"><img src="https://d2wuvg8krwnvon.cloudfront.net/newui/images/mc-fee.png" /></div>
												   <div class="col-md-20"><img src="https://d2wuvg8krwnvon.cloudfront.net/newui/images/visa.png" /></div>
												   <div class="col-md-20"><img src="https://d2wuvg8krwnvon.cloudfront.net/newui/images/master-card.png" /></div>
												</div>
											 </div>
										  </div>
									   </div>
									</div>
								 </div>
							  </div>
						   </div>
						</div>
					 </div>
				  </div>
			   </div>
			</div>
		 </div>
	  </div>
	</div>
	</div>';
	  } else {
	$html = '<div class="wrap main_wrapper">
	<div class="blackoverlay" id="overlay" style="display: none;"> </div>
	<img src="'.$iconFolderPath.'/loading.gif" class="loaderjob" id="loaderjob" style="display: none;">
	<div class="qr_wrapper payment_wrp">
	<div id="container_trail" class="container">
	<div class="row" >
	<div class="col-md-12">
	  <div class="payment-main-container">
		 <div class="maincontainer">
			<div class="outer-container">
			   <div class="wrapper_width">
				  <h1>Payment Information</h1>
				  <div class="row second-option">
					 <div class="col-xs-12">
						<div class="white-strip pay-wrapper trial-app">
						   <div class="tab-container">
							  <div class="tab">
								 <div class="col-xs-12">
									<div class="full-width">
									   <div class="int-white-container">
										  <div class="row">
											 <div class="col-md-4 col-xs-12 custom-aside-payment pull-right">
												<h3 class="pltinume___plane"> Gold Plan Benefits</h3>
												<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
												   <div class="row">
													  <div class="col-md-3 col-sm-4 col-xs-5 dollar-blackbtn">Plan</div>
													  <div class="col-md-9 col-sm-8 col-xs-7 white-striptxt"> <span class="ng-binding" style="text-transform: capitalize;">Gold</span></div>
												   </div>
												</div>
												<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
												   <div class="row">
													  <div class="col-md-3 col-sm-4 col-xs-5 dollar-blackbtn">Billed</div>
													  <div class="col-md-9 col-sm-8 col-xs-7 white-striptxt">
														 <span style="display:none">
														 <label class="radio-inline">
														 <input type="radio" value="monthly" name="planPeriod">
														 Monthly 
														 </label>
														 <label class="radio-inline">
														 <input type="radio" value="yearly" name="planPeriod">
														 Yearly 
														 </label>
														 </span> 
														 <span style="text-transform: capitalize;">Yearly</span> 
													  </div>
												   </div>
												</div>
												<div class="col-xs-12">
												   <div class="row">
													  <div class="col-md-8 col-sm-9 col-xs-8 dollar-blackbtn">Mobile App</div>
													  <div class="col-md-4 col-sm-3 col-xs-4 white-striptxt"> <span>'.$currencySymbol.''.$monthlyPrice.'</span> </div>
												   </div>
												</div>
												<!--<div class="summaryList">
												   <label class="col-sm-4 col-xs-5 dollar-blackbtn col-md-8">Add-On Package </label>
												   <span id="summaryPlan" class="col-sm-8 col-xs-7 white-striptxt col-md-4">
													  <div><span>'.$currencySymbol.''.$monthlyPrice.'</span> </div>
												   </span>
												</div>-->
												<!--<div class="summaryList">
												   <div class="dollar-blackbtn col-xs-12">
													  <small style="font-size:10px;display:block;color: #676767;">
														 <p class="offsetbottom5 text_wrp">We’ve added Add-On Package. Here’s why!</p>
														 <ul class="addonsinfolist">
															<li class="offsetbottom5" style="font-weight: normal;">White-label your app by opting for our Add-On package and removing Appy Pie branding from the entire app.</li>
															<li class="offsetbottom5" style="font-weight: normal;">Google and Apple keep coming up with updates periodically. Every time there is an update, you would have to re-submit your app and each resubmission is chargeable. With Add-On Package you get unlimited resubmissions for no extra cost.</li>
															<li class="offsetbottom5" style="font-weight: normal;">It is natural to run into unexpected trouble at some point in time. With Add-On Package you get a dedicated account manager to help you through every step of the way.</li>
														 </ul>
														 <p class="text_wrp" style="font-weight: normal;">We highly recommend our Add-On Package, but it’s optional for you.</p>
													  </small>
												   </div>
												</div>-->
												<div class="row">
												   <div class="col-xs-12">
													  <div class="lightgrey-strip">
														 <div class="col-md-4 col-sm-5 col-xs-8 dollar-blackbtn"><span class="text-left">IGST: (18%)</span> </div>
														 <div class="col-md-8 col-sm-7 col-xs-4 white-striptxt"> <span>'.$currencySymbol.''.$vatPercentageGst.'</span> </div>
													  </div>
												   </div>
												   <div class="col-xs-12">
													  <div class="grey-strip">
														 <div class="col-md-8 col-sm-9 col-xs-8 whitetxt">Amount to be paid</div>
														 <div class="col-md-4 col-sm-3 col-xs-4 dollar-btn">'.$currencySymbol.''.$subtotals.'</div>
													  </div>
													  <div class="clearfix"></div>
												   </div>
												</div>
												<div class="pos-rel monay-back-container">
												   <div class="moneyBackpayment coup_30day hidden-sm hidden-xs">
													  <div class="moneyBackbg priceBackbg"> <big>30</big><br>
														 <span class="days">DAYS</span><br>
														 <span class="moneyBack-txt">GUARANTEE</span> 
													  </div>
													  <p class="fs11 text-center offsettop10"><strong>30-Day Money Back Guarantee</strong></p>
													  <p></p>
												   </div>
												</div>
											 </div>
											 <div class="col-md-8 col-xs-12 paymentborder pos-rel">
												
												<form class="form-horizontal" id="paymentmode" method="post" action="admin.php?page=android-app&action=payment" >
												<div class="form-group">
												  <label for="fnameError" class="col-md-3 col-sm-3 col-xs-12 control-label">Name</label>
												  <div class="col-md-4 col-sm-4 col-xs-12">
													<input type="text" name="fname" id="fname" class="form-control" placeholder="First Name" />
												  </div>
												  <div class="col-md-4 col-sm-4 col-xs-12 padding-left0">
													<input type="text" name="lname" id="lname" class="form-control lastname-style" placeholder="Last Name" />
												  </div>
												</div>
												<div class="form-group">
												  <label for="" class="col-md-3 col-sm-3 col-xs-12 control-label"> Billing Address</label>
												  <div class="col-md-4 col-sm-4 col-xs-12">
													<textarea name="address" id="address" style="resize: none;" class="height90 form-control align-left" placeholder="Address"></textarea>
												  </div>
												  <div class="col-md-4 padding-left0 col-sm-4 col-xs-12">
													<div class="payment-row">
													  <input type="text" name="city" id="city" class="form-control" placeholder="City" />
													  <span id="statelist">
													  <select class="form-control margin-top-pay-20" name="state" id="state">
														<option value="">State</option>
														'.$stOption.'
													  </select>
													  </span>
													</div>
												  </div>
												</div>
												<div class="form-group">
												  <label for="" class="col-md-3 col-sm-3 col-xs-3 disnone control-label"> </label>
												  <div class="col-md-4 col-sm-4 col-xs-12">
													<input type="text" name="zip" id="zip" class="form-control" placeholder="Zip Code" />
												  </div>
												  <div class="col-md-4 col-sm-4 col-xs-12 phone padding-left0">
													<select name="country" id="country" class="form-control">
													  <option value="" >Country</option>
													  '.$options.'
													</select>
												  </div>
												</div>
												<div class="form-group">
												  <label for="" class="col-md-3 col-sm-3 col-xs-3 disnone control-label"> Phone</label>
												  <div class="col-md-4 col-sm-4 col-xs-12">
													<input type="text" name="phone" id="phone" class="form-control" placeholder="Phone" />
												  </div>
												  <div class="col-md-4 col-sm-4 col-xs-12"></div>
												</div>
												<div class="form-group">
												  <div class="col-md-3"></div>
												  <div class="col-md-9 col-xs-12 grayInput" style="font-size: 12px !important;">
													<input type="checkbox" name="agreeForContact" id="agreeForContact" value="1" checked /> <span class="providingPayment"> By providing your phone number you are giving us the permission to contact you.</span>
												  </div>
												</div>
											  
											  <div class="row">
												<div class="newCheckout col-md-12 col-sm-12 col-xs-12">
												  <div class="pos-rel btn-wrap" style="width: 50%; margin: 0 auto;">
													<input type="submit" class="continue-btn purchaseBtn" name="pay" id="pay" value="Complete Secure Payment">
												  </div>
												  <div class="offsettop5 left-text p-left">
													<div class="securePayment"><span class="appyslimsecurity"><img src="'.$iconFolderPath.'/safe-and-secure.svg"></span>Safe &amp; Secure</div>
												  </div>
												  <input type="hidden" name="appId" id="appId" value="'.$appId.'">
												  <input type="hidden" name="countryCode" id="countryCode" value="'.$country_code.'">
												  <input type="hidden" name="planPeriod" id="planPeriod" value="yearly">
												  <input type="hidden" name="planId" id="planId" value="2">
												  <input type="hidden" name="paymentMethod" id="paymentMethod" value="paypal">
												  <input type="hidden" name="appType" id="appType" value="upgrade">
												   <input type="hidden" name="action" value="payment_app">
												  </form>
												<!--<div class="gstin-section">
												   <div class="gstin-section">
													  <div class="row fs14">
														 <div class="col-xs-12"> <span><a href="javascript:void(0)">Buying for your business?</a></span> </div>
													  </div>
												   </div>
												   <div class="gst_main_wrp" style="display:none">
													  <div class="form-group">
														 <div class="col-xs-12">
															<h3 class="buying_busness">Buying for your business <a class="fs11" href="javascript:void(0)">(<i>Cancel</i>)</a></h3>
														 </div>
													  </div>
													  <div class="form-group">
														 <label for="" class="col-md-3 col-sm-3 col-xs-12 control-label">Company</label>
														 <div class="col-md-9 col-sm-9 col-xs-12" style="margin-bottom: 0;">
															<input type="text" id="companyError" class="form-control ng-pristine ng-untouched ng-valid" placeholder="Company name">
														 </div>
													  </div>
													  <div class="form-group">
														 <label for="" class="col-md-3 col-sm-3 col-xs-12 control-label">GST</label>
														 <div class="col-md-9 col-sm-9 col-xs-12" style="margin-bottom: 0;">
															<input type="text" id="gstNumberError" ng-model="billing.gstNumber" class="form-control ng-pristine ng-untouched ng-valid" placeholder="GST Number (optional)">
														 </div>
													  </div>
												   </div>
												</div>-->
												<!--<div class="row">
												   <div class="newCheckout col-md-12 col-sm-12 col-xs-12">
													  <div class="pos-rel btn-wrap" style="width: 50%;margin: 0 auto;">
														 <button type="button" class="continue-btn purchaseBtn"><big><b>Complete Secure Payment</b></big></button>
														 <div class="continue-btn payment_paypal_loading_dots" align="center">
															<span></span><span></span><span></span>
														 </div>
													  </div>
													  <div class="offsettop5 left-text p-left">
														 <div class="securePayment"> <span class="appyslim-security-confirm-shield"></span>Safe &amp; Secure</div>
													  </div>
												   </div>
												</div>-->
												<div class="partner-strip" ng-show="trialButton==1">
												   <div class="col-md-20"><img src="https://d2wuvg8krwnvon.cloudfront.net/newui/images/secured.png" /></div>
												   <div class="col-md-20"><img src="https://d2wuvg8krwnvon.cloudfront.net/newui/images/pci.png" /></div>
												   <div class="col-md-20"><img src="https://d2wuvg8krwnvon.cloudfront.net/newui/images/mc-fee.png" /></div>
												   <div class="col-md-20"><img src="https://d2wuvg8krwnvon.cloudfront.net/newui/images/visa.png" /></div>
												   <div class="col-md-20"><img src="https://d2wuvg8krwnvon.cloudfront.net/newui/images/master-card.png" /></div>
												</div>
											 </div>
										  </div>
									   </div>
									</div>
								 </div>
							  </div>
						   </div>
						</div>
					 </div>
				  </div>
			   </div>
			</div>
		 </div>
	  </div>
	</div>
	</div>'; }
		?>
	<link rel="stylesheet" href="<?php echo $pluginURL; ?>/appypie-web-to-app/list/css/bootstrap.min.css">
	<script src="<?php echo $pluginURL; ?>/appypie-web-to-app/list/js/jquery.min.js"></script>
	<script>
	var ajax_url = "<?php echo admin_url( 'admin-ajax.php' ); ?>";
	jQuery(function () {
		$("#country").change(function () {
			var country = $("#country" ).val();
			var fileName = $(this).find('option:selected').attr("fileName");
			  $.ajax({
				method : 'POST',
				dataType: 'html',
				url    : ajax_url,
				data: {
					action:'verify_token', 
					country:country,
					fileName:fileName
				},
				})
			  .done( function( response ) {
				  $('#statelist').html(response);
			})
			.fail( function() {
				return false;
			})
		});
	});

	jQuery(function($) {
	  $("#paymentmode").validate({
		// Specify validation rules
		rules: {
		  fname: "required",
		  lname: "required",
		  address: "required",
		  city: "required",
		  zip: "required",
		  state: "required",
		  country: "required",
		  phone: "required",
		  agreeForContact: "required",
		},
		// Specify validation error messages 
		messages: {
		  fname: "This field is required.",
		  lname: "This field is required.",
		  address: "This field is required.",
		  city: "This field is required.",
		  zip: "This field is required.",
		  state: "This field is required.",
		  country: "This field is required.",
		  phone: "This field is required.",
		  agreeForContact: "This field is required.",
		},
		submitHandler: function(form) {
           $('#overlay').show();
		   $('#loaderjob').show();
		   var formData = $('#paymentmode').serialize();
		    $.ajax({
				method : 'POST',
				dataType: 'html',
				url    : ajax_url,
				data:formData,
				})
			  .done( function(data) {
				  $('#overlay').hide();
		          $('#loaderjob').hide();
				  console.log(data.url);
				  var json = $.parseJSON(data);
				  console.log('json',json.url);
				  if(json.status==200){
					  document.location.href = json.url;
				  }else{
					  $('#paymenterror').html("<p>Opps, Something went wrong please try again!</p>");
				  }
				  
			})
			.fail( function() {
				return false;
			})
    	 }
	  });
	});
   </script>
  <?php 
  return $html;
 }
	function appypieSignupForm(){
     global $wpdb; // wordpress connection variable
	 global $current_user;
	 $pluginURL = plugins_url();
	 $iconFolderPath = $pluginURL . '/appypie-web-to-app/list/images';
	 wp_get_current_user();
     $email = $current_user->user_email;
	 $email_id = $_POST['app_username'] ? $_POST['app_username'] : $email ;
	 $adminUrl = admin_url().'admin.php';
	 $parstring = '&page=android-app&action=created';
	 $appUrl = $adminUrl.$parstring;
	 ?>
	 <div class="wrap main_wrapper">
	  <div class="qr_wrapper">
	  <div class="mobile_head accounts_setting">	
	   </div>
		  <div class="jumbotron qrfullwrapper text-center">
			 <div class="account_setupwrp">
			 <div class="row">
			 <div class="col-md-6">
			  <div class="auth_wrp">
			  <h1><strong>Authenticate your Appy Pie Account</strong></h1>
			  <p>Authenticate your Appy Pie account and start creating your native mobile app. If you are visiting Appy Pie for the first time or if you haven’t created an account yet, then Sign up and create an account. You will be required to insert your credentials to authenticate your account.</p>
              <a class="button bt_setup_account" onclick="window.open('https://accounts.appypie.com/thirdparty/login?frompage=<?php echo $appUrl; ?>','popup','width=600,height=600'); return false;">Authenticate your Account</a>
			  </div>
			   </div>
			   <div class="col-md-6">

			   <img src="<?php echo $iconFolderPath; ?>/account_setup.png" alt=""/>
			   </div>
				</div>
			   </div>
			 </div>
			 </div>
		      </div>
		     </div>
		   </div>

 <link rel="stylesheet" href="<?php echo $pluginURL; ?>/appypie-web-to-app/list/css/bootstrap.min.css">
<script src="<?php echo $pluginURL; ?>/appypie-web-to-app/list/js/jquery.min.js"></script>
 <script>

 jQuery(function($) {
   var ajax_url = "<?php echo admin_url('admin-ajax.php'); ?>";
  $("#register").validate({
	// Specify validation rules
	rules: {
	  app_username: "required",
	  app_password: "required", 
	  customCheck:"required",
	},
	// Specify validation error messages 
	messages: {
	  app_username: "This field is required.",
	  app_password: "This field is required.",
	  customCheck: "This field is required.",
   },
	submitHandler: function(form) {
	 $('#overlay').show();
	 $('#loaderjob').show();
	 var app_username = $('#app_username').val();
	 var app_password = $('#app_password').val();
		  $.ajax({
			method : 'POST',
			dataType: 'html',
			url    : ajax_url,
			data: {
				action:'register_app', 
				app_username:app_username,
				app_password:app_password,
			},
			})
		  .done(function(response) {
		   $('#overlay').hide();
	       $('#loaderjob').hide();
		   if(response==200){
			   document.location.href = '?page=android-app&action=register';
		   }else{
			   $("#siguperror").html(response);
		   }
		})
		.fail( function() {
			return false;
		})
      }
  });
 });

 </script>
<?php 
 return $html;
}
 
function thankyouMsg(){
		 $testAreaLink = get_option('app_url');
		 $pluginURL = plugins_url();
	     $iconFolderPath = $pluginURL . '/appypie-web-to-app/list/images';
		 $appId = get_option('appId');
		 $jsonObj = array(
            'appId' => $appId
        );
		$json = json_encode($jsonObj);
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://snappy.appypie.com/mobileapp/app-trail-details',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POSTFIELDS => $json,
		  CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json',
		  ),
		));
		$response = curl_exec($curl);
		$apiData = json_decode($response,true);
		curl_close($curl);
		if($apiData['status']=='success'){
			$timeStart = $apiData['appTrialLog']['trialStart'];
			$timeEnd = $apiData['appTrialLog']['trialEnd'];
			$upgradedstatus = $apiData['appTrialLog']['upgraded'];
            if (is_plugin_active( 'woocommerce/woocommerce.php')) {
				$appText = "Store";
			}else{
				$appText = "App";
			}


		  if(!empty($apiData['appTrialLog']['activeFlag'])  && $apiData['appTrialLog']['activeFlag']==1 && !empty($apiData['appTrialLog']['trialEndDate'])){?>
				<div id="container_trail" class="container" >
					 <div class="inner_container text-center">
						  <img src="<?php echo $iconFolderPath; ?>/trial.png" alt=""/>
						  <h2>Welcome to your 7 days trial of App Pie App Builder.</h2>
						  <p class="app_text">Your 7-days trial starts today. Now, go to your Appy Pie dashboard and customize your <?php echo $appText; ?> or start<br/> testing your app to see the changes in real-time on your device.<p>
						   <a class="button_dasboard" target="_blank" href="<?php echo $apiData['appTrialLog']['dashboardUrl']; ?>">Go to Appy Pie Dashboard</a>
					   </div>
				</div>
			<?php }
			else if($apiData['appTrialLog']['activeFlag']==3){?>
			<div id="container_trail" class="container">
		      <div class="inner_container expire-screen text-center">
			      <img src="<?php echo $iconFolderPath; ?>/expired.png" alt=""/>
			      <h2>Your free trial has expired.</h2>
			      <p class="app_text">We hope you’ve enjoyed the free trial. To continue creating your app, please upgrade to a paid plan.<p>
			       <a class="button_dasboard" target="_blank" href="<?php echo $apiData['appTrialLog']['upgradeAppUrl']; ?>">Upgrade Plan</a>
			   </div>
		   </div>
			<?php 
			}
			else if($apiData['appTrialLog']['activeFlag']==0){?>
			<div id="container_trail" class="container">
		      <div class="inner_container expire-screen text-center">
			      <img src="<?php echo $iconFolderPath; ?>/expired.png" alt=""/>
			      <h2>Your free trial has been cancelled.</h2>
			      <p class="app_text">We hope you’ve enjoyed the free trial. To continue creating your app, please upgrade to a paid plan.<p>
			       <a class="button_dasboard" target="_blank" href="<?php echo $apiData['appTrialLog']['upgradeAppUrl']; ?>">Upgrade Plan</a>
			   </div>
		   </div>
			
			<?php 	
			} else if($apiData['appTrialLog']['activeFlag']==2){?>
			 <div id="container_trail" class="container" >
				 <div class="inner_container upgrade-screen text-center">
					  <img src="<?php echo $iconFolderPath; ?>/trial.png" alt=""/>
					  <h2>Congratulations!</h2>
					  <p class="app_text">You’ve successfully upgraded to Appy Pie’s <?php echo $apiData['appTrialLog']['planName']; ?>.<p>
					  <p class="app_text">Your app is ready, and you can now customize your app or start testing your App to see the changes in real-time on your device.<p>
					   <a class="button_dasboard" target="_blank" href="<?php echo $apiData['appTrialLog']['dashboardUrl']; ?>">Customize your app</a>
				   </div>
			   </div>
			 <?php   // paid trial
            }
		}
		 ?>
		 <div id="container_trail" class="container" style="display:none;">
		 <div class="inner_container upgrade-screen text-center">
			  <img src="<?php echo $iconFolderPath; ?>/expired.png" alt=""/>
			  <h2>Your free trial has expired.</h2>
			  <p class="app_text">Your trial period is over in just 2 days. Now, go to your Appy Pie dashboard and customize your store or start testing your app to see the changes in real-time on your device.<p>
			   <a class="button_dasboard" target="_blank" href="<?php echo $apiData['appTrialLog']['dashboardUrl']; ?>">Customize your app</a>
		   </div>
		</div>
		<style>
		#wpbody {
			margin-right: 20px;
		}
		</style>
	   <?php
       return $html;
	}

	
	function createSnappyUser($loginString){
		$jsonObj = array(
		 'loginString' => $loginString 
		);
		$json = json_encode($jsonObj);
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://snappy.appypie.com/mobileapp/create-snappy-user',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POSTFIELDS => $json,
		  CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json',
		  ),
		));
		$response = curl_exec($curl);
		curl_close($curl);
		return $response;
	}

	function addRecommendedPages($data){
		$appJson = json_encode($data);
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://snappy.appypie.com/utility/add-recommended-pages',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POSTFIELDS =>$appJson,
		  CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json',
		  ),
		));
		$response = curl_exec($curl);
		curl_close($curl);
		return $response;
	}
	function storeVariableTodb($userInfo,$sessionId){
		
		$curl = curl_init();
		$sessionData = array(
		  'sessionId'=>$sessionId,
		  'userInfo'=>$userInfo,
		  'appCreateFrom'=>'ocommercePlugins'
		);
		
		$statusJson = json_encode($sessionData);
		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://snappy.appypie.com/app/store-variable-to-db',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POSTFIELDS =>$statusJson,
		  CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json',
		  ),
		));

		$response = curl_exec($curl);
		//print_r($response);
		curl_close($curl);
	    return $response;
	}
	
	function androidApp_newform(){
	global $wpdb; // wordpress connection variable
	global $current_user;
	$pluginURL = plugins_url();
	$iconFolderPath = $pluginURL . '/appypie-web-to-app/list/images';
	?>
	<div class="blackoverlay" id="overlay" style="display: none;"> </div>
	<img src="<?php echo $iconFolderPath; ?>/loading.gif" class="loaderjob" id="loaderjob" style="display: none;">
	<div class="wrap main_wrapper">
	   <div class="mobile_head">	
	   <h1> Add your App Elements</h1>
      <div id="apperror" style="text-align: center; color:red; font-size:18px;"></div>
	   <form id="appmaker" method="post" action="admin.php?page=android-app&action=appcreated" enctype="multipart/form-data" >
	   	 <div class="top_section clearfix">
		  <table class="form-table" role="presentation">
		  <input type="hidden" name="action" value="create_app">
			 <tbody>
			  <tr>
				   <th scope="row"><label class="label_input" for="blogname">App Name *</label></th>
				   <td>
				     <input type="text" name="app_name" id="app_name" placeholder="Enter App Name" class="regular-text app_name_input" >
					</td>
				</tr>
				<tr>
				   <!--<th scope="row"><label class="label_input" for="blogname">App Type*</label></th>-->
				   <td>
					<select name="app_tye" id="app_tye" class="regular-text app_name_input" hidden="hidden" >
						<option value="android">Android</option>
					</select>
					</td>
				</tr>
				<tr>
				    <th scope="row"><label class="label_input" for="blogdescription">Theme Type *</label></th>
				   	<td>
				    <div class="app_theme_wrp">		
				    <div class="light_app">		
				   	<input name="theme_type" type="radio" value="light" id="theme_type" class="regular-text theme-type" required checked>
				   	<label for="blogdescription">Light</label>
				   </div>
                    <div class="dark_app">
                      <input name="theme_type" type="radio" value="dark" id="theme_type" class="regular-text theme-type" required>
                      <label for="blogdescription">Dark</label>
                  </div>
              </div>
				</td>
				</tr>
				<tr>
				   <th scope="row"><label class="label_input" for="blogdescription">Color Theme</label></th>
				   <input type="hidden" name="themeId" id="themeId">
				</tr>
				</tbody>
		       </table>
		       <div class="app_box_wrp">
			   <?php
				$curl = curl_init();
				curl_setopt_array($curl, array(
				  CURLOPT_URL => 'https://snappy.appypie.com/mobileapp/app-builder-theme/',
				  CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_ENCODING => '',
				  CURLOPT_MAXREDIRS => 10,
				  CURLOPT_TIMEOUT => 0,
				  CURLOPT_FOLLOWLOCATION => true,
				  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				  CURLOPT_CUSTOMREQUEST => 'GET',
				  CURLOPT_HTTPHEADER => array(
					'Cookie: lang=en'
				  ),
				));

				$themeData = curl_exec($curl);
				curl_close($curl);
				$imageData = json_decode($themeData,true);
				$light_theme = $imageData['response']['themeType']['light_theme'];
				$dark_theme = $imageData['response']['themeType']['dark_theme'];
				?>
               
               <div class="main-container light" id="textbox">
			    <?php 
				 if($light_theme){
					 foreach($light_theme as $light){?>
					 <a href="javascript:void(0);" class="theme-id" data-themeId="<?php echo $light['themeId']; ?>">
						 <div class="box">
							 <img class="img" src="<?php echo $light['imgURL']; ?>" alt="<?php echo $light['name1'] ?>" style="width:100%;">
							 <!--<div class="tick_iocn"></div>-->
							 <div class="centered" style="color:#fff;"><?php echo $light['name1'] ?> <?php echo $light['name2'] ?></div>
						</div>
					</a>
					<?php }
				 }
				?>
			  </div>

			  <div class="main-container dark" id="themecolor">
			 <?php 
				 if($dark_theme){
					 foreach($dark_theme as $dark){?>
					 <a href="javascript:void(0);" class="theme-id" data-themeId="<?php echo $dark['themeId']; ?>">
					 <div class="box">
					   <img class="img" src="<?php echo $dark['imgURL']; ?>" alt="<?php echo $dark['name1']; ?>" style="width:100%;">
					   <div class="centered" style="color:#fff;"><?php echo $dark['name1'] ?> <?php echo $dark['name2'] ?></div>
					</div>
					</a>
			      <?php }
				 }
				?>
			  </div>
			 </div>
			 </div>
			 <div class="bottom_section"> 
             <span style="color:red;" id="imgerror"></span>			 
			 <table class="form-table" role="presentation">
			  <tbody>
				<tr>
				   <th scope="row"><label class="label_input" for="blogdescription">App Icon (Max Size 400x400 px)</label></th>
				   <td>
				   	<div class="upload_frame">
					 <input type="file" name="app_icon" id="app_icon" onchange="preview();" hidden  class="app_icons" accept="image/png, image/gif, image/jpeg , image/jpg"/>
                     <label class="upload_file" for="app_icon">Upload File</label>
                       <div class="upload_preview" style="display:none;">
                         <img id="frame" src=""   />
                     </div>
                     </div>
				    </td>
				    <td>
				   </td>
     			</tr>
				<tr>
				   <th scope="row"><label class="label_input" for="blogdescription">App Splash (Max Size 800x800 px</label></th>
				   <td>
				   	<div class="upload_frame">
					 <input type="file" name="app_splash" id="app_splash" onchange="splashpreview();" hidden class="app_splashs"accept="image/png, image/gif, image/jpeg , image/jpg" />
					   <label class="upload_file" for="app_splash">Upload File</label>
					    <div class="upload_splash" style="display:none;">
					    <img id="splash" src=""   />
					  </div>
					</div>
				   </td>
				   <td>
				   </td>
				</tr>
				<tr>
				   <th scope="row"><label class="label_input" for="blogdescription">App Background (Max Size 1200x1200 px</label></th>
				   <td>
				   	<div class="upload_frame">
					  <input type="file" name="app_bgc" id="app_bgc" onchange="appbackground();"  hidden class="app_bgcs" accept="image/png, image/gif, image/jpeg , image/jpg"/>
					     <label class="upload_file" for="app_bgc">Upload File</label>
					     <div class="upload_background" style="display:none;">
					     <img id="background" src=""  />
					  </div>
					</div>
				   </td>
				</tr>
			 </tbody>
		  </table>
		  
		  <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary save_bt" value="Create App"></p>
		</div>
	   </form>
	</div>
<script src="<?php echo $pluginURL; ?>/appypie-web-to-app/list/js/jquery.min.js"></script>
<script>
var ajax_url = "<?php echo admin_url( 'admin-ajax.php' ); ?>";
function preview() {
	$('.upload_preview').show();
    frame.src=URL.createObjectURL(event.target.files[0]);
}
function splashpreview(){
	$('.upload_splash').show();
    splash.src=URL.createObjectURL(event.target.files[0]);
}
function appbackground(){
	$('.upload_background').show();
    background.src=URL.createObjectURL(event.target.files[0]);
}

jQuery(function($) {
  $("#appmaker").validate({
    // Specify validation rules
    rules: {
      app_name: "required",
	  app_tye: "required",
	  theme_type: "required",
	  app_icon: "required",
	  app_splash: "required",
	  
    },
    // Specify validation error messages 
    messages: {
      app_name: "This field is required.",
	  app_tye: "This field is required.",
	  theme_type: "This field is required.",
	  app_icon: "This field is required.",
	  app_splash: "This field is required.",
    },
    submitHandler:function(form) {
		var themId = $('#themeId').val();
		var app_icon =  document.getElementById('app_icon');
		var filePath = app_icon.value;
		var app_splash =  document.getElementById('app_splash');
		var splashfilePath = app_splash.value;
		var app_bgc =  document.getElementById('app_bgc');
		var app_bgcfilePath = app_bgc.value;
		$('#app_icon').on('change', function() {
		  console.log('This file size is: ' + this.files[0].size / 1024 / 1024 + "MiB");
		});
		if(themId==""){
			alert("Please select theme color.");
			return false;
		}
		if(filePath == "" && splashfilePath==""){
		  $('#imgerror').text('Sorry, Choose your App Icons !');
		  return false;
		}
		var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;
        if (!allowedExtensions.exec(filePath)) {
			$('#imgerror').text('Invalid icon file type selected for App Icon, only jpg, png allowed!');
			filePath.value = '';
			return false;
        } 
		
		var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;
		if (!allowedExtensions.exec(splashfilePath)) {
			$('#imgerror').text('Invalid icon file type selected for splash Icon, only jpg, png allowed!');
			splashfilePath.value = '';
			return false;
		}  		
               
		if(themId==""){
			alert("Please select theme color.");
		}else{
			$('#imgerror').hide();
			$('#overlay').show();
			$('#loaderjob').show();
			var app_name = $('#app_name').val();
			var app_tye = $('#app_tye').val();
			var app_icon = jQuery('#app_icon').prop('files')[0];
			var splash_icon = jQuery('#app_splash').prop('files')[0];
			var app_bgc = jQuery('#app_bgc').prop('files')[0];
			form_data = new FormData();
			if(app_icon){
			  form_data.append('app_icon', app_icon);
			}
			if(splash_icon){
			  form_data.append('app_splash', splash_icon);
			}
			if(splash_icon){
			  form_data.append('app_bgc', app_bgc);
			}
			form_data.append('app_name', app_name);
			form_data.append('app_tye', app_tye);
			form_data.append('themId', themId);
			form_data.append('action', 'create_app');
		    $.ajax({
			method : 'POST',
			dataType: 'html',
			url: ajax_url,
			data:form_data,
		    contentType: false,
            processData: false,
			})
		  .done(function(response) {
		   $('#overlay').hide();
	       $('#loaderjob').hide();
		   console.log(response);
		   if(response==200){
			   document.location.href = '?page=android-app&action=appcreated';
		   }else{
			   $("#apperror").html('<p>Error something went wrong. Please try again!</p>');
		   }
		})
		.fail( function() {
			return false;
		})
	  }
    }
  });
});
$(document).ready(function() {
    $(".theme-id").click(function() {
		var theme_id = $(this).attr("data-themeId");
		$('#themeId').val(theme_id);
    });
    $('input[type="radio"]').click(function() {
        var inputValue = $(this).attr("value");
        if (inputValue == "light") {
            $('.light').show();
            $('.dark').hide();
        }
        if (inputValue == "dark") {
            $('.dark').show();
            $('.light').hide();
        }
    });
	$('.box').on('click', function(){
        $('.box').removeClass('themactive');
        $(this).addClass('themactive');
    });
  });
  
  $(document).ready(function() {
   $(document).on("change", ".app_icons", function() {
      let fileSize = this.files[0].size / 500 / 500;
            if (fileSize > 1) {
			  $('#imgerror').text('Uploaded image has valid Height and Width. 400x400px.');
			setTimeout(function() {
                    $('#imgerror').hide();
                }, 5000);
			return true;
			}
	});
  });
  $(document).ready(function() {
    $(document).on("change", ".app_splashs", function() {
	let files = $(this)[0].files;
      let fileSize = this.files[0].size / 800 / 800;
      let fileExt = this.files[0].name.split('.').pop();
            if (fileSize > 2) {
			  $('#imgerror').text('Uploaded image has valid Height and Width. 800x800px.');
			  setTimeout(function() {
                    $('#imgerror').hide();
                }, 5000);
			//filePath.value = '';
			return false;
			}
	});
  });
  $(document).ready(function() {
    $(document).on("change", ".app_bgcs", function() {
	let files = $(this)[0].files;
      let fileSize = this.files[0].size / 1200 / 1200;
      let fileExt = this.files[0].name.split('.').pop();
            if (fileSize > 2) {
			  $('#imgerror').text('Uploaded image has valid Height and Width. 1000x1000px.');
			  setTimeout(function() {
                    $('#imgerror').hide();
                }, 5000);
			//filePath.value = '';
			return false;
			}
	});
  });
</script>
<?php
  }
 }
new AndroidWoocommerceAPI();
?>