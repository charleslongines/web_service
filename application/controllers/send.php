<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);
require_once('push-notification-class.php');

$devices = array();
$unseen = array();
$unseen[0] = 2;
$devices[0] = 'e9affa3bce13f54ddfbf39f3713cdc35a55e53a4b8c16c3415cbb43e78f1cbb4';


$push_notification = new PushNotification();
$push_notification->set_prod_file('push2015.pem');
$push_notification->set_message('Updates available.');
//$push_notification->set_badge(3); 
$push_notification->set_sound('default');
$push_notification->set_devices(array_unique($devices));
$push_notification->set_unseen($unseen);
$push_notification->Send();

/*$push_notification_new = new PushNotification();
$push_notification_new->set_prod_file('2014_dr_push_final.pem');
$push_notification_new->set_message('Updates available.');
//$push_notification->set_badge(3); 
$push_notification_new->set_sound('default');
$push_notification_new->set_devices(array_unique($devices));
$push_notification_new->set_unseen($unseen);
$push_notification_new->Send();*/

/* 2015 pem file */
//$push_notification->set_prod_file('drprod_2014.pem');
//$push_notification->Send();
die();
?>