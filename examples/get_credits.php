<?php

require_once __DIR__ . '/../vendor/autoload.php';

$comilio_username = 'your_username_here'; // Please register on https://www.comilio.it
$comilio_password = 'your_password_here';

$credit = new Comilio\Credit();
$credit->authenticate($comilio_username, $comilio_password);

var_dump($credit->get());