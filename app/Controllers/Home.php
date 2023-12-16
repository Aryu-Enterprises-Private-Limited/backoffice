<?php namespace App\Controllers;

use App\Controllers\BaseController;

class Home extends BaseController{
	
	// public function __construct(){
	// 	parent::__construct();
	// }
	public function index()
	{
		echo 'Site is Comming Soon'; die;
	}
	// public function sendPN()
	// {
	// 	$optionsArr = [
	// 		'action' => 'property_verified',
	// 		'title' => 'Title',
	// 		'body' => 'Propery MIK20 has been verified successfully',
	// 		'property_id' => 'MIK20'
	// 	];
	// 	$this->sendPushNotification('android','fjIUaR6IS4yUG1Jlc9mC_I:APA91bGtDwZ4AAO2dznNbTq62WEzM51bLRD79as4XyDiPBSKy5JUMR5Ipn3cHioJ-C1nWTvGaec-ZftpIphoRi2l1aICtZJyjfsYzVcoXZmVJ5-9ffXHGo9HaQ6hH__SQoaIror0JlZC', $optionsArr);
	// }
	
	// function info(){
	// 	#echo phpinfo();
	// 	$smsInit = new \App\Libraries\Twilio();
	// 	$smsRes = $smsInit->sendMessage('+919566399141','Test SMS - '.date("Y-m-d H:i:s"));
	// 	echo '<pre>'; var_dump((array)$smsRes);
	// }
	
}