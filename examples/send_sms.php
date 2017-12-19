<?php

require_once __DIR__ . '/../vendor/autoload.php';

$comilio_username = 'your_username_here'; // Please register on https://www.comilio.it
$comilio_password = 'your_password_here';
$sender = 'ComilioTest';
$recipients = array('+393400000000', '+393499999999');
$text = 'Hello World!';

$sms = new Comilio\SmsMessage();

$sms->authenticate($comilio_username, $comilio_password)
    ->setSender($sender)
    ->setType(Comilio\SmsMessage::SMS_TYPE_SMARTPRO)
    ->setRecipients($recipients);

if ($sms->send($text) === true) {
    echo "Sent SMS Id: " . $sms->getId() . "\n";

    foreach ($sms->getStatus($sms_id) as $status) {
        echo "Message to {$status->phone_number} is in status {$status->status}\n";
    }

}
