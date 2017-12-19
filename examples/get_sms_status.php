<?php

require_once __DIR__ . '/../vendor/autoload.php';

$comilio_username = 'your_username_here'; // Please register on https://www.comilio.it
$comilio_password = 'your_password_here';
$sms_id = 'sms_id_here';

$sms = new Comilio\SmsMessage();
$sms->authenticate($comilio_username, $comilio_password);

foreach ($sms->getStatus($sms_id) as $status) {
    echo "Message to {$status->phone_number} is in status {$status->status}\n";
}
