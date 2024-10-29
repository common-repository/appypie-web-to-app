<?php 
include_once('GeoIP/vendor/autoload.php');
use GeoIp2\Database\Reader; 
  function countryCodeOld(){
		$countryCode='';
		if(strpos($_SERVER["HTTP_HOST"], 'host.angular')){ 
			$countryCode = getenv("GEOIP_COUNTRY_CODE");
		} else {
			$ipAddress = getip_index1();
			include_once('geoip.inc');
			if((strpos($ipAddress, ":") === false))
			{
				$gi = @geoip_open("/usr/share/GeoIP/GeoIP.dat",GEOIP_STANDARD);  //ipv4
				$countryCode = @geoip_country_code_by_addr($gi, $ipAddress);
			}
			else
			{
				$gi = @geoip_open("/usr/share/GeoIP/GeoIPv6.dat",GEOIP_STANDARD); //ipv6
				$countryCode = @geoip_country_code_by_addr_v6($gi, $ipAddress);
			}
			if($countryCode==''){
				$countryCode = getenv("GEOIP_COUNTRY_CODE");
			}
		}
		if($countryCode==''){
			$countryCode='US';
		}
		return $countryCode;
	}
	function countryCode($ipAddr=''){
		$countryCode='';
		if(strpos($_SERVER["HTTP_HOST"], 'host.angular') || strpos($_SERVER["HTTP_HOST"], 'pbodev.info')){ 
			$countryCode = getenv("GEOIP_COUNTRY_CODE");
		} else {
			$ipAddress = $ipAddr==''?getip_index1():$ipAddr;
			//$ipAddress = $ipAddr==''?getUserIP():$ipAddr;
			//$ipAddress = $ipAddr;
			try{
				$reader = new Reader('/usr/local/share/GeoIP/GeoLite2-Country.mmdb');
				$record = $reader->country($ipAddress);
				$countryCode = $record->country->isoCode;
			}catch(Exception $e){
				$countryCode='US';	
			}
		}
		if($countryCode==''){
			$countryCode='US';
		}
		return $countryCode;
	}
	function countryName(){
                $countryCode='';
                if(strpos($_SERVER["HTTP_HOST"], 'host.angular')){ 
                        $countryName = getenv("GEOIP_COUNTRY_NAME");
                } else {
                        $ipAddress = getip_index1();
                        include_once('geoip.inc');
                        if((strpos($ipAddress, ":") === false))
                        {
                                $gi = @geoip_open("/usr/share/GeoIP/GeoIP.dat",GEOIP_STANDARD);  //ipv4
                                $countryName = @geoip_country_name_by_addr($gi, $ipAddress);
                        }
                        else
                        {
                                $gi = @geoip_open("/usr/share/GeoIP/GeoIPv6.dat",GEOIP_STANDARD); //ipv6
                                $countryName = @geoip_country_name_by_addr_v6($gi, $ipAddress);
                        }
                        if($countryName==''){
                                $countryName = getenv("GEOIP_COUNTRY_NAME");
                        }
                }
                if($countryName==''){
                        $countryName='US';
                }
                return $countryName;
        }
	function getip_index1() {
		if(validip_index2(@$_SERVER["HTTP_CLIENT_IP"])) {
			return @$_SERVER["HTTP_CLIENT_IP"];
		}
		$ippArray=explode(",",@$_SERVER["HTTP_X_FORWARDED_FOR"]);
		//print_r($ippArray);
		//foreach(explode(",",@$_SERVER["HTTP_X_FORWARDED_FOR"]) as $ip) {
		//echo $ippArray[0];
			//if(validip_index2(trim($ippArray[0]))) {
				return $ippArray[0];
		//	}
		//}

		if(validip_index2(@$_SERVER["HTTP_X_FORWARDED"])) {
			return @$_SERVER["HTTP_X_FORWARDED"];
		}
		elseif(validip_index2(@$_SERVER["HTTP_FORWARDED_FOR"])) {
			return @$_SERVER["HTTP_FORWARDED_FOR"];
		}
		elseif(validip_index2(@$_SERVER["HTTP_FORWARDED"])) {
			return @$_SERVER["HTTP_FORWARDED"];
		}
		else {
			echo $_SERVER["REMOTE_ADDR"];
			return @$_SERVER["REMOTE_ADDR"];
		}
	}
	function validip_index2($ip) {
		//$ipArray=explode(',',$ip);
		//$ip=$ipArray[0];
		if(!empty($ip) && ip2long($ip)!=-1) {
			$reserved_ips = array(
				array('0.0.0.0','2.255.255.255'),
				array('10.0.0.0','10.255.255.255'),
				array('127.0.0.0','127.255.255.255'),
				array('169.254.0.0','169.254.255.255'),
				array('172.16.0.0','172.31.255.255'),
				array('192.0.2.0','192.0.2.255'),
				array('192.168.0.0','192.168.255.255'),
				array('255.255.255.0','255.255.255.255')
			);
			foreach($reserved_ips as $r) {
				$min = ip2long($r[0]);
				$max = ip2long($r[1]);
				if((ip2long($ip) >= $min) && (ip2long($ip) <= $max))
				return false;
			}
			return true;
		}
		else {
			return false;
		}
	}
	function getUserIP() {
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
?>
