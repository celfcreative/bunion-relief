<?php

/**
 * Check if email exists
 */
add_action('rest_api_init', function () {
  register_rest_route('v1', '/check-email-exists', [
    'methods' => 'POST',
    'callback' => 'check_email_existence_callback',
    'permission_callback' => '__return_true',
  ]);
});

function check_email_existence_callback($request)
{
  $email = $request->get_param('email');

  $email_exists = checkEmailExist($email);

  return rest_ensure_response(['exists' => $email_exists]);
}

function checkEmailExist($email)
{
  $api_url = 'https://api.cc.email/v3/contacts';
  $accessToken = CONSTANT_CONTACT_TOKEN;

  // Set up query parameters
  $query_params = [
    'email' => $email,
    'status' => 'all'
  ];

  $url = $api_url . '?' . http_build_query($query_params);

  $headers = [
    'Authorization' => 'Bearer ' . $accessToken,
    'Content-Type' => 'application/json',
    'Accept' => 'application/json'
  ];

  $response = wp_remote_get($url, [
    'headers' => $headers,
  ]);

  if (!is_wp_error($response)) {
    $response_code = wp_remote_retrieve_response_code($response);
    $body = wp_remote_retrieve_body($response);

    if ($response_code === 200) {
      $data = json_decode($body, true);

      // Check if the email exists in the response
      if (!empty($data['contacts'])) {
        // Email exists
        return true;
      } else {
        // Email does not exist
        return false;
      }
    } else {
      // Handle other response codes if needed
      error_log('Constant Contact API Request Error: Unexpected response code ' . $response_code);
      return false;
    }
  } else {
    // Handle WP error
    error_log('Constant Contact API Request Error: ' . $response->get_error_message());
    return false;
  }
}

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
        'custom_field_id' => '3c2a290c-a952-11ee-a205-fa163e0f14ae',
        'value' => $requestType
      ],
      [
        'custom_field_id' => '2ad28f0a-a952-11ee-87d4-fa163e28e109',
        'value' => $learnAbout
      ],
      [
        'custom_field_id' => '30d160ec-a953-11ee-b8ff-fa163ef3b06c',
        'value' => isset($decoded_utm->utm_source) ? $decoded_utm->utm_source : ''
      ],
      [
        'custom_field_id' => '20bf3152-a953-11ee-9955-fa163e0f14ae',
        'value' => isset($decoded_utm->utm_medium) ? $decoded_utm->utm_medium : ''
      ],
      [
        'custom_field_id' => '0d8837b4-a953-11ee-bf39-fa163e0b03e8',
        'value' => isset($decoded_utm->utm_campaign) ? $decoded_utm->utm_campaign : ''
      ],
    ],
    'create_source' => 'Contact',
    'list_memberships' => [
      'f52bba58-a950-11ee-b090-fa163ef3b06c'
    ]
  ];

  $emailExists = checkEmailExist($email);

  if ($emailExists) {
    // error_log('Email already exists in Constant Contact for: ' . $email);
    // wp_send_json_error('Email already exists in Constant Contact.');
  } else {

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
        'custom_field_id' => '3c2a290c-a952-11ee-a205-fa163e0f14ae',
        'value' => $requestType
      ],
      [
        'custom_field_id' => 'da376218-a952-11ee-8b61-fa163e6a92d8',
        'value' => $termAgreement,
      ],
      [
        'custom_field_id' => 'bdb7d794-a952-11ee-adfb-fa163e5bf31a',
        'value' => $doctorName,
      ],
      [
        'custom_field_id' => '30d160ec-a953-11ee-b8ff-fa163ef3b06c',
        'value' => isset($decoded_utm->utm_source) ? $decoded_utm->utm_source : ''
      ],
      [
        'custom_field_id' => '20bf3152-a953-11ee-9955-fa163e0f14ae',
        'value' => isset($decoded_utm->utm_medium) ? $decoded_utm->utm_medium : ''
      ],
      [
        'custom_field_id' => '0d8837b4-a953-11ee-bf39-fa163e0b03e8',
        'value' => isset($decoded_utm->utm_campaign) ? $decoded_utm->utm_campaign : ''
      ],
    ],
    'create_source' => 'Contact',
    'list_memberships' => [
      'fcf295a4-a950-11ee-a5c9-fa163e5bf31a'
    ]
  ];

  $emailExists = checkEmailExist($email);

  if ($emailExists) {
    //   error_log('Email already exists in Constant Contact for: ' . $email);
    //   wp_send_json_error('Email already exists in Constant Contact.');
  } else {

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
        'custom_field_id' => 'a61e3ca4-a952-11ee-b87d-fa163e28e109',
        'value' => $resourceDownload,
      ],
      [
        'custom_field_id' => '30d160ec-a953-11ee-b8ff-fa163ef3b06c',
        'value' => isset($decoded_utm->utm_source) ? $decoded_utm->utm_source : ''
      ],
      [
        'custom_field_id' => '20bf3152-a953-11ee-9955-fa163e0f14ae',
        'value' => isset($decoded_utm->utm_medium) ? $decoded_utm->utm_medium : ''
      ],
      [
        'custom_field_id' => '0d8837b4-a953-11ee-bf39-fa163e0b03e8',
        'value' => isset($decoded_utm->utm_campaign) ? $decoded_utm->utm_campaign : ''
      ],
    ],
    'create_source' => 'Contact',
    'list_memberships' => [
      '046e02f0-a951-11ee-833d-fa163e6a92d8'
    ]
  ];

  $emailExists = checkEmailExist($email);

  if ($emailExists) {
    //   error_log('Email already exists in Constant Contact for: ' . $email);
    //   wp_send_json_error('Email already exists in Constant Contact.');
  } else {

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
// echo getAccessToken('https://localhost', '854738ac-84d3-4446-b736-b840e9a951d9', 'zV8gE6VtrNj3h3MFUYFmiQ', 'p6FWFIjUbFV-KXZTTgAXv170NKios5iqIVu-CeN8VLQ&state=235o250eddsdff');