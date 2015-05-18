<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PushNotification extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->library(array('gcm','apn'));
	}

	function sendGCM()
	{
		$this->gcm->setData(array(
			'title'=>'Title',
			'message'=>'Sample Message'));
		// $this->gcm->setMessage('This is your sample Message'.date('d.m.Y H:s:i'));
		$this->gcm->addRecepient('APA91bHQwyst7NeAg7ZTH4YcKX9hr0ecxD771LP1oMq-XAdeSZ08yzb42IJ_rdFLlCMV4uF1w2PKtTNz-u7QLVE5C-ffXQQfpBgSo0nqgHVUOw4pGjyNdwtUxLBYtSn9Qg5FmHkuN8i8');
		$this->gcm->setTtl(false);
		$this->gcm->setGroup(false);

		if($this->gcm->send())
		{
			echo 'Success for all messages';
		}
		else
		{
			echo 'Some messages have errors';
		}
	}

	function sendAPN()
	{
		$this->apn->payloadMethod = "enhance";
		$this->apn->connectToPush();
		$send_result = $this->apn->sendMessage('1250c3b1f2dbfa9a47a92aad24a44dcb46f159c603e68c4114fc40a1bcd7bc66','Test notif #1 (TIME:'.date('H:i:s').')', /*badge*/ 2, /*sound*/ 'default'  );
	
		if($send_result)
		{
			echo 'Sending successful';
	        log_message('debug','Sending successful');
		}
	    else
	    {
	    	echo $this->apn->error;
	    	log_message('error',$this->apn->error);
	    }
		$this->apn->disconnectPush();
	}
}
?>