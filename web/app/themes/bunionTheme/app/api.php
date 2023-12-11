<?php

// Define API Routes
add_action('rest_api_init', function () {

  register_rest_route('v1', '/results', [
    'methods' => 'GET',
    'callback' => 'refreshTokenWrapper',
    'permission_callback' => '__return_true',
  ]);
});

/**
 * @param $refreshToken - The refresh token provided with the previous access token
 * @param $clientId - API Key
 * @param $clientSecret - API Secret
 * @return string - JSON String of results
 */
function refreshToken($refreshToken, $clientId, $clientSecret)
{
  // Use cURL to get a new access token and refresh token
  $ch = curl_init();

  // Define base URL
  $base = 'https://authz.constantcontact.com/oauth2/default/v1/token';

  // Create full request URL
  $url = $base . '?refresh_token=' . $refreshToken . '&grant_type=refresh_token';
  curl_setopt($ch, CURLOPT_URL, $url);

  // Set authorization header
  // Make string of "API_KEY:SECRET"
  $auth = $clientId . ':' . $clientSecret;
  // Base64 encode it
  $credentials = base64_encode($auth);
  // Create and set the Authorization header to use the encoded credentials, and set the Content-Type header
  $authorization = 'Authorization: Basic ' . $credentials;
  curl_setopt($ch, CURLOPT_HTTPHEADER, array($authorization, 'Content-Type: application/x-www-form-urlencoded'));

  // Set method and to expect response
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  // Make the call
  $result = curl_exec($ch);
  curl_close($ch);

  return json_decode($result)->access_token;
}

function refreshTokenWrapper()
{
  $token = refreshToken(CONSTANT_REFRESH_TOKEN, '338566b6-fd20-4178-97f8-c5247baca73a', 'l_DiByiWMgsJ6sNJzufnWg');

  updateRefreshToken($token);

  echo 'test update';
}
function updateRefreshToken($token)
{
  update_field('constant_contact_token', $token, 'option');
}
