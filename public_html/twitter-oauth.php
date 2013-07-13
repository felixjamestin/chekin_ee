<?php
function buildBaseString($baseURI, $method, $params)
{
    $r = array(); 
    ksort($params); 
    foreach($params as $key=>$value){
        $r[] = "$key=" . rawurlencode($value); 
    }            

    return $method."&" . rawurlencode($baseURI) . '&' . rawurlencode(implode('&', $r)); //return complete base string
}

function buildAuthorizationHeader($oauth)
{
    $r = 'Authorization: OAuth ';
    $values = array();
    foreach($oauth as $key=>$value)
        $values[] = "$key=\"" . rawurlencode($value) . "\""; 

    $r .= implode(', ', $values); 
    return $r; 
}

$url = "https://api.twitter.com/1.1/statuses/user_timeline/ChekinTeam.json";

$oauth_access_token = "534037133-BjVeJkrjNV3kl4L9wK6n6vxL1vRD1c9Hbf6WtFg";
$oauth_access_token_secret = "6EZlEPmOLwVQPx0Ko8WbQtVT5RNcfTDryKzzg0aL3TU";
$consumer_key = "HG3WEYN3iLeFh43uOhDdKg";
$consumer_secret = "0MA2L758heSQ5OYVNh70jct32ks5NMblUFxxs97sVM";

$oauth = array( 'oauth_consumer_key' => $consumer_key,
                'oauth_nonce' => time(),
                'oauth_signature_method' => 'HMAC-SHA1',
                'oauth_token' => $oauth_access_token,
                'oauth_timestamp' => time(),
                'oauth_version' => '1.0');

$base_info = buildBaseString($url, 'GET', $oauth);
$composite_key = rawurlencode($consumer_secret) . '&' . rawurlencode($oauth_access_token_secret);
$oauth_signature = base64_encode(hash_hmac('sha1', $base_info, $composite_key, true));
$oauth['oauth_signature'] = $oauth_signature;


$header = array(buildAuthorizationHeader($oauth), 'Expect:');
$options = array( CURLOPT_HTTPHEADER => $header,
                  CURLOPT_HEADER => false,
                  CURLOPT_URL => $url,
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_SSL_VERIFYPEER => false);

$feed = curl_init();
curl_setopt_array($feed, $options);
$json = curl_exec($feed);
curl_close($feed);

echo $json;
?>