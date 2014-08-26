<?php
if (!INCLUDED) exit;

// Get data from input
$raw_data = file_get_contents('php://input');

// Decode it
$data = json_decode($raw_data, true);

// Get current URL
$signed_data = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];

// Get post data
$post_data = $_POST;

// Sort it
ksort( $post_data );

//append to string
foreach ($post_data as $key => $value) {
    $signed_data .= $key;
    $signed_data .= $value;
}

// Get settings from Wordpress database
$options = get_option('other-settings-page');
$webhook_key = $options['mandrill-settings-webhook-key'];

// Generate signature
$generatedsig = base64_encode(hash_hmac('sha1', $signed_data, $webhook_key, true));

// Get header signature
$headers = getallheaders();
$sentsig = $headers['X-Mandrill-Signature'];

if ( $generatedsig === $sentsig )
{
    http_response_code(200);
    
}
else 
{
    http_response_code(400);
}
exit();
