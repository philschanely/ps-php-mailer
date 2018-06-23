<?php


# ini_set('display_errors', 1);

require_once('env.php');

// $params = $_POST;
$params = array(
  'from' => 'schanelyphotography@gmail.com',
  'to' => 'philschanely@gmail.com',
  'subject' => 'Hello!',
  'message' => '<p>Hey dude.</p><p>sincerely, your friend</p>'
);
$requred_params = array('to', 'from', 'subject', 'message');
$missing_params = array();


// Make sure params exist
if (empty($params)) {
  echo 'ERROR: No params provided.';
  die;
}


// Make sure all required params are present
foreach ($requred_params as $key) {
  if (!array_key_exists($key, $params)) {
    $missing_params[] = $key;
  }
}
if (!empty($missing_params)) {
  echo 'ERROR: Missing required params: ' . implode(', ', $missing_params);
  die;
}

$params = (object) $params;


// Compose the message
$message = "
<html>
<head>
  <title>{$params->subject}</title>
</head>
<body>
  {$params->message}
</body>
</html>
";


// Set up headers
$headers[] = 'MIME-Version: 1.0';
$headers[] = 'Content-type: text/html; charset=iso-8859-1';
$headers[] = 'To: ' . $params->to;
$headers[] = 'From: ' . $params->from;


// Mail it
mail($params->to, $params->subject, $message, implode("\r\n", $headers));


// Show a response to the API caller
$response = (object) array(
  'params_provided' => $params,
  'headers' => $headers,
  'message' => $message
);
echo json_encode($response);
