<?php

/**
 * Contact Form -> Constant Contact -> Contact Form contact list
 */

add_action('af/form/submission/key=form_contact_form', function ($form, $fields, $args) {

  if (isset($_COOKIE['utm_data'])) {
    $utm_data = $_COOKIE['utm_data'];

    $utm_data_unescaped = stripslashes($utm_data);
    $decoded_utm = json_decode($utm_data_unescaped);

    update_field('utm_source', $decoded_utm->utm_source);
    update_field('utm_medium', $decoded_utm->utm_medium);
    update_field('utm_campaign', $decoded_utm->utm_campaign);
  }

  // Fetch data from the form fields
  $firstName = af_get_field('first_name');
  $lastName = af_get_field('last_name');
  $phone = af_get_field('mobile_number');
  $email = af_get_field('email');
  $city = af_get_field('city');
  $state = af_get_field('state');
  $requestType = af_get_field('please_type_your_request');
  $learnAbout = get_the_title(af_get_field('what_system_do_you_want_to_learn_more_about'));

  $constant_contact_data = [
    'email_address' =>
    ['address' => $email],
    'first_name' => $firstName,
    'last_name' => $lastName,
    'phone_numbers' => [
      [
        'phone_number' => $phone,
        'kind' => 'mobile',
      ],
    ],
    'street_addresses' => [
      [
        'kind' => 'home',
        'city' => $city,
        'state' => $state,
      ]
    ],
    'custom_fields' => [
      [
        'custom_field_id' => '93f964e6-95bf-11ee-8e75-fa163e64fc3f',
        'value' => $requestType
      ],
      [
        'custom_field_id' => 'a46282f4-95bf-11ee-b1d7-fa163e4dd890',
        'value' => $learnAbout
      ],
      [
        'custom_field_id' => '3d177bdc-9db6-11ee-83f8-fa163ed82b2c',
        'value' => isset($decoded_utm->utm_source) ? $decoded_utm->utm_source : ''
      ],
      [
        'custom_field_id' => '818013ae-9dbd-11ee-9b51-fa163e8d7f7f',
        'value' => isset($decoded_utm->utm_medium) ? $decoded_utm->utm_medium : ''
      ],
      [
        'custom_field_id' => '89e750b6-9dbd-11ee-9f58-fa163e64fc3f',
        'value' => isset($decoded_utm->utm_campaign) ? $decoded_utm->utm_campaign : ''
      ],
    ],
    'create_source' => 'Contact',
    'list_memberships' => [
      'a9e451b0-98e6-11ee-b1d7-fa163e4dd890'
    ]
  ];

  $api_url = 'https://api.cc.email/v3/contacts';
  // 
  $api_token = CONSTANT_CONTACT_TOKEN;
  $api_refreshtoken = CONSTANT_REFRESH_TOKEN;

  $headers = [
    'Content-Type' => 'application/json',
    'Authorization' => 'Bearer ' . $api_token,
  ];

  $body = wp_json_encode($constant_contact_data);

  $response = wp_remote_post($api_url, [
    'headers' => $headers,
    'body'    => $body,
  ]);

  if (is_wp_error($response)) {
    dump($response);
    error_log('Constant Contact API Request Error: ' . $response->get_error_message());
    wp_send_json_error('Error sending data to Constant Contact.');
  } else {
    $response_code = wp_remote_retrieve_response_code($response);
    if ($response_code === 200 || $response_code === 201 || $response_code === 202) {
      // wp_send_json_success('Data sent to Constant Contact successfully.');
    } else {
      dump($response_code, $response);
      error_log('Constant Contact API Request Error: Unexpected response code ' . $response_code);
      wp_send_json_error('Unexpected response from Constant Contact.');
    }
  }
}, 10, 3);

/**
 * Get In Touch Form -> Constant Contact -> Get In Touch contact list
 */
add_action('af/form/submission/key=form_get_in_touch', function ($form, $fields, $args) {

  if (isset($_COOKIE['utm_data'])) {
    $utm_data = $_COOKIE['utm_data'];

    $utm_data_unescaped = stripslashes($utm_data);
    $decoded_utm = json_decode($utm_data_unescaped);

    update_field('utm_source', $decoded_utm->utm_source);
    update_field('utm_medium', $decoded_utm->utm_medium);
    update_field('utm_campaign', $decoded_utm->utm_campaign);
  }

  // Fetch field data
  $firstName = af_get_field('first_name');
  $lastName = af_get_field('last_name');
  $phone = af_get_field('mobile_number');
  $email = af_get_field('email');
  $requestType = af_get_field('please_type_your_request');
  $termAgreement = af_get_field('terms_agreement');
  $doctorName = af_get_field('doctor_name');

  $constant_contact_data = [
    'email_address' =>
    ['address' => $email],
    'first_name' => $firstName,
    'last_name' => $lastName,
    'phone_numbers' => [
      [
        'phone_number' => $phone,
        'kind' => 'mobile',
      ],
    ],
    'custom_fields' => [
      [
        'custom_field_id' => '93f964e6-95bf-11ee-8e75-fa163e64fc3f',
        'value' => $requestType
      ],
      [
        'custom_field_id' => 'a6bf754c-98db-11ee-a0b9-fa163e78e228',
        'value' => $termAgreement,
      ],
      [
        'custom_field_id' => '29cfcce4-9903-11ee-99cf-fa163e75fbca',
        'value' => $doctorName,
      ],
      [
        'custom_field_id' => '3d177bdc-9db6-11ee-83f8-fa163ed82b2c',
        'value' => isset($decoded_utm->utm_source) ? $decoded_utm->utm_source : ''
      ],
      [
        'custom_field_id' => '818013ae-9dbd-11ee-9b51-fa163e8d7f7f',
        'value' => isset($decoded_utm->utm_medium) ? $decoded_utm->utm_medium : ''
      ],
      [
        'custom_field_id' => '89e750b6-9dbd-11ee-9f58-fa163e64fc3f',
        'value' => isset($decoded_utm->utm_campaign) ? $decoded_utm->utm_campaign : ''
      ],
    ],
    'create_source' => 'Contact',
    'list_memberships' => [
      'f0e48b4c-98d8-11ee-9f6c-fa163e78e228'
    ]
  ];

  $api_url = 'https://api.cc.email/v3/contacts';
  $api_token = CONSTANT_CONTACT_TOKEN;

  $headers = [
    'Content-Type' => 'application/json',
    'Authorization' => 'Bearer ' . $api_token,
  ];

  $body = wp_json_encode($constant_contact_data);

  $response = wp_remote_post($api_url, [
    'headers' => $headers,
    'body'    => $body,
  ]);

  if (is_wp_error($response)) {
    dump($response);
    error_log('Constant Contact API Request Error: ' . $response->get_error_message());
    wp_send_json_error('Error sending data to Constant Contact.');
  } else {
    $response_code = wp_remote_retrieve_response_code($response);
    if ($response_code === 200 || $response_code === 201 || $response_code === 202) {
      // wp_send_json_success('Data sent to Constant Contact successfully.');
    } else {
      dump($response_code, $response);
      error_log('Constant Contact API Request Error: Unexpected response code ' . $response_code);
      wp_send_json_error('Unexpected response from Constant Contact.');
    }
  }
}, 10, 3);

/**
 * Resource Download Form -> Constant Contact -> Resource Download contact list
 */
add_action('af/form/submission/key=form_resource_download', function ($form, $fields, $args) {

  if (isset($_COOKIE['utm_data'])) {
    $utm_data = $_COOKIE['utm_data'];

    $utm_data_unescaped = stripslashes($utm_data);
    $decoded_utm = json_decode($utm_data_unescaped);

    update_field('utm_source', $decoded_utm->utm_source);
    update_field('utm_medium', $decoded_utm->utm_medium);
    update_field('utm_campaign', $decoded_utm->utm_campaign);
  }

  // Fetch field data
  $firstName = af_get_field('first_name');
  $lastName = af_get_field('last_name');
  $phone = af_get_field('mobile_number');
  $email = af_get_field('email');
  $resourceDownload = af_get_field('resource_name');

  $constant_contact_data = [
    'email_address' =>
    ['address' => $email],
    'first_name' => $firstName,
    'last_name' => $lastName,
    'phone_numbers' => [
      [
        'phone_number' => $phone,
        'kind' => 'mobile',
      ],
    ],
    'custom_fields' => [
      [
        'custom_field_id' => 'e243b548-98e4-11ee-83ab-fa163e75fbca',
        'value' => $resourceDownload,
      ],
      [
        'custom_field_id' => '3d177bdc-9db6-11ee-83f8-fa163ed82b2c',
        'value' => isset($decoded_utm->utm_source) ? $decoded_utm->utm_source : ''
      ],
      [
        'custom_field_id' => '818013ae-9dbd-11ee-9b51-fa163e8d7f7f',
        'value' => isset($decoded_utm->utm_medium) ? $decoded_utm->utm_medium : ''
      ],
      [
        'custom_field_id' => '89e750b6-9dbd-11ee-9f58-fa163e64fc3f',
        'value' => isset($decoded_utm->utm_campaign) ? $decoded_utm->utm_campaign : ''
      ],
    ],
    'create_source' => 'Contact',
    'list_memberships' => [
      '89679a34-98e4-11ee-83ab-fa163e75fbca'
    ]
  ];

  $api_url = 'https://api.cc.email/v3/contacts';
  $api_token = CONSTANT_CONTACT_TOKEN;

  $headers = [
    'Content-Type' => 'application/json',
    'Authorization' => 'Bearer ' . $api_token,
  ];

  $body = wp_json_encode($constant_contact_data);

  $response = wp_remote_post($api_url, [
    'headers' => $headers,
    'body'    => $body,
  ]);

  if (is_wp_error($response)) {
    dump($response);
    error_log('Constant Contact API Request Error: ' . $response->get_error_message());
    wp_send_json_error('Error sending data to Constant Contact.');
  } else {
    $response_code = wp_remote_retrieve_response_code($response);
    if ($response_code === 200 || $response_code === 201 || $response_code === 202) {
      // wp_send_json_success('Data sent to Constant Contact successfully.');
    } else {
      dump($response_code, $response);
      error_log('Constant Contact API Request Error: Unexpected response code ' . $response_code);
      wp_send_json_error('Unexpected response from Constant Contact.');
    }
  }
}, 10, 3);


function getAccessToken($redirectURI, $clientId, $clientSecret, $code)
{
  // Use cURL to get access token and refresh token
  $ch = curl_init();

  // Define base URL
  $base = 'https://authz.constantcontact.com/oauth2/default/v1/token';

  // Create full request URL
  $url = $base . '?code=' . $code . '&redirect_uri=' . $redirectURI . '&grant_type=authorization_code';
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
  return $result;
}
// API Key > Secret Key > Authorization Code - To get Tokens
// echo getAccessToken('https://localhost', '338566b6-fd20-4178-97f8-c5247baca73a', 'l_DiByiWMgsJ6sNJzufnWg', 'J1t7IhpH3uz-iAJlJdOG1EIkMCRN_jM_0RyCtYfGJKA&state=235o250eddsdff');