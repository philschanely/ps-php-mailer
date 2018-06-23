<?php

// Allow calls from elsewhere :)
header("Access-Control-Allow-Origin: *");

// Toggle displaying errors while testing implementation
ini_set('display_errors', 1);

// Get the AUTH constant
include('env.php');

// Indicate whether or not an authorization code is required
$require_auth = false;

// Get the values sent via POST request.
// $params = $_POST;
$params = array(
  'from' => 'schanelyphotography@gmail.com',
  'to' => 'philschanely@gmail.com',
  'subject' => 'Hello!',
  'message' => '<p>Hey dude.</p><p>sincerely, your friend</p>'
);

// List of required parameters for validation and feedback
$requred_params = array('to', 'from', 'subject', 'message');

// List of missing params to use in validation and feedback
$missing_params = array();

// Response object to modify in this script and deliver feedback to client
$response = (object) array(
  'response' => 'ERROR',
  'params' => $params,
  'headers' => null,
  'message' => ''
);


// Validate request and send message

// 1.
// Make sure params exist
if (empty($params)) {
  $response->message = 'ERROR: No params provided.';
  echo json_encode($response);
  die;
}

// 2.
// Ensure requrest has come with correct AUTH code if one is required
if ($require_auth && AUTH != $params['auth']) {
  $response->message = 'ERROR: Invalid authorization code';
  echo json_encode($response);
  die;
}

// 3.
// Make sure all required params are present
foreach ($requred_params as $key) {
  if (!array_key_exists($key, $params)) {
    $missing_params[] = $key;
  }
}
if (!empty($missing_params)) {
  $response->message = 'ERROR: Missing required params: ' . implode(', ', $missing_params);
  echo json_encode($response);
  die;
}

// Change params to an object
$params = (object) $params;

// 4.
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
$headers[] = "To: Recipient <{$params->to}>";
$headers[] = "From: Sender <{$params->from}>";

// 5.
// Mail it
$sent_mail = mail($params->to, $params->subject, $message, implode("\r\n", $headers));
if (!$sent_mail) {
  $response->message = 'ERROR: Mail did not send properly. ';
  echo json_encode($response);
  die;
}

// Show a success response to the client
$response->response = 'SUCCESS';
$response->headers = $headers;
$response->message = $message;
echo '<pre>';
echo json_encode($response);
echo '</pre>';
