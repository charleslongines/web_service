<?php

/************************************/
/***** Push Notification Object *****/
/************** by Mac **************/
/************************************/

class PushNotification {
	
	// fields
	private $_message;
	private $_badge;
	private $_sound;
	private $_isDevelopment;
	private $_payload;
	private $_apns_url;
	private $_apns_cert;
	private $_apns_port;
	private $_devices;
	private $_pem_filename;
	private $_unseen;
	
	//poperties
	public function set_prod_file($pem_filename) {
		$this->_pem_filename = $pem_filename;
	}
	
	public function set_message($message){
		$this->_message = $message;
	}
	
	public function set_badge($badge){
		$this->_badge = $badge;
	}

	public function set_sound($sound){
		$this->_sound = $sound;
	}
	
	public function set_isDevelopment($isDevelopment){
		$this->_isDevelopment = $isDevelopment;
	}
	
	public function set_payload($payload){
		$this->_payload = $payload;
	}
	
	public function set_apns_url($apns_url){
		$this->_apns_url = $apns_url;
	}
	
	public function set_apns_cert($apns_cert){
		$this->_apns_cert = $apns_cert;
	}
	
	public function set_port($apns_port){
		$this->_apns_port = $apns_port;
	}

	public function set_devices($devices){
		$this->_devices = $devices;
	}
	
	public function set_unseen($unseen){
		$this->_unseen = $unseen;
	}
	
	//constructor
	public function __construct(){
		
		$this->_messsage = '';
		$this->_badge = 1;
		$this->_sound = 'default';
		$this->_isDevelopment = false;
		$this->_payload = array();
		$this->_apns_url = null;
		$this->_apns_cert = null;
		$this->_apns_port = 2195;
		$this->_devices = array();
		$this->_pem_filename = null;
		$this->_unseen = array();
		
	}
	
	//methods
	public function Send(){
		
		
		
		if($this->_isDevelopment){	
			$this->_apns_url = 'gateway.sandbox.push.apple.com';
			$this->_apns_cert = 'dr_dev.pem';
			
		}
		else
		{
			$this->_apns_url = 'gateway.push.apple.com';
			$this->_apns_cert = $this->_pem_filename;
		}
		
		$streamContext = stream_context_create();
		stream_context_set_option($streamContext, 'ssl', 'local_cert', $this->_apns_cert);
		stream_context_set_option($streamContext, 'ssl', 'passphrase', 'SkyFall01');
		$apns = stream_socket_client('ssl://'.$this->_apns_url.':'.$this->_apns_port, $error, $errorString, 2, STREAM_CLIENT_CONNECT, $streamContext);
		
		$count = 0;
		foreach($this->_devices as $device){
			
			
			$this->_badge = intval($this->_unseen[$count]);
			
			$this->_payload['aps'] = array(
				'alert'=>$this->_message,
				'badge'=>$this->_badge,
				'sound'=>$this->_sound
			);
			$this->_payload = json_encode($this->_payload);
		
			$apns_message = chr(0).chr(0).chr(32).pack('H*', str_replace(' ', '', $device)).chr(0).chr(strlen($this->_payload)).$this->_payload;
			fwrite($apns, $apns_message);
			
			$filename = "sample.txt";
			$fh = fopen($filename, "a") or die("Could not open log file.");
			fwrite($fh, date("d-m-Y, H:i")." - t:".$count." of ".count($this->_devices)." seen=$this->_badge devToken=$device\n") or die("Could not write file!");
			fclose($fh);
			$count += 1;
			
		}
		
		@socket_close($apns);
		@fclose($apns);
		
	}
	
}




?>