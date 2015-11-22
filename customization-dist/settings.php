<?php

$customization = array(
  // Give your instance a name
  'instance_name' => '',
  
  // (Optional)  Copy your favicon.ico to `customization/assets` and set the
  // following setting to `'customAsset.php?file=favicon.ico'`
  'favicon_url' => null,
  
  // (Optional)  Copy your logo to `customization/assets` and set the following
  // setting to `'customAsset.php?file=YOUR_FILENAME'`
  'dashboard_logo' => null,

  // (Optional) Set a (external) link for your dashboard logo (works only if
  // you have configured a logo)
  'dashboard_link' => null,
  
  // add your imprint as HTML file: `customization/imprint.html`
  'imprint_content' => file_get_contents(__DIR__ . '/imprint.html'),
  
  // add your privacy terms as HTML file: `customization/privacy.html`
  'privacy_content' => file_get_contents(__DIR__ . '/privacy.html'),
  
  // (Optional) Add a custom text to the introcution mail sent to users, after
  // they confirmed their eMail-address (You might use an extra file
  // and `file_get_contents`)
  'introduction_mail_content' => null,
  
  // (Optional) Add a custom analytics code (You might use an extra file
  // and `file_get_contents`)
  'analytics_code' => null,
);
