<?php
// MySQL-Connection Settings
$config = array(
    'host' => "",
    'user' => "",
    'pw' => "",
    'db' => ""
);

// STMP Mailserver Config
$mailConfig['smtp_host'] = '';
$mailConfig['smtp_port'] = '';
$mailConfig['smtp_transport'] = ''; //empty, 'tls' or 'ssl'
$mailConfig['smtp_user'] = '';
$mailConfig['smtp_password'] = '';
$mailConfig['sender_address'] = '';
$mailConfig['sender_name'] = '';

// signup process configuration
$signup = array(
  // file containing text to be send to double-optin users
  'doubleOptinMsg' => '' // example __DIR__.'/../locale/text.txt'
);