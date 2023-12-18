<?php

/**
 * Contact Form -> Constant Contact -> Contact Form contact list
 */
add_action('af/form/submission/key=form_contact_form', function ($form, $fields, $args) {
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
    ],
    'create_source' => 'Contact',
    'list_memberships' => [
      'a9e451b0-98e6-11ee-b1d7-fa163e4dd890'
    ]
  ];

  $api_url = 'https://api.cc.email/v3/contacts';
  // 
  $api_token = 'eyJraWQiOiJzay1Ed2N2eU9uUm1yc21yVUI5bHNNSXFVcXl4MF8zcFQ4dkhDcG5uZmN3IiwiYWxnIjoiUlMyNTYifQ.eyJ2ZXIiOjEsImp0aSI6IkFULjJtYmJ1V1A3SVZjY2lzUlAwQmdxZ0R1N1ZPSUFqcGtSZE1YWmRzaHgyQUUub2FyMTRvMG8zbEwxeGNyRnYwaDciLCJpc3MiOiJodHRwczovL2lkZW50aXR5LmNvbnN0YW50Y29udGFjdC5jb20vb2F1dGgyL2F1czFsbTNyeTltRjd4MkphMGg4IiwiYXVkIjoiaHR0cHM6Ly9hcGkuY2MuZW1haWwvdjMiLCJpYXQiOjE3MDIzNzk4NTQsImV4cCI6MTcwMjQ2NjI1NCwiY2lkIjoiMzM4NTY2YjYtZmQyMC00MTc4LTk3ZjgtYzUyNDdiYWNhNzNhIiwidWlkIjoiMDB1MXU3bGhtbnM0STVlMlowaDgiLCJzY3AiOlsib2ZmbGluZV9hY2Nlc3MiLCJjYW1wYWlnbl9kYXRhIiwiY29udGFjdF9kYXRhIl0sImF1dGhfdGltZSI6MTcwMjM3Mzc0NCwic3ViIjoiY29uc3RhbnRjZWxmIiwicGxhdGZvcm1fdXNlcl9pZCI6IjgxNzQ4NDliLTdkNjQtNGJjNS1iMTAzLTQzY2M1YTdkYjRkYiJ9.OzLGvA3q_VHqx9kHdDgTfzSaHlxlWoc-Roo1ojhxAR3orPtN6u_2143BQ7qfHrzJYiJgw3MHpUkxQjlEBgN6j373rHzHCCIRY3ntfnuNBDLgBBmO8Ri5mfDVjZjB9-8NRjFLE12559lozE-bpmHwExXTb_btmyuV5mi2vBvtJM0AK0DgebZdI_7r1YK5XxmIw-kMJ5bsSF28S_ymid4WxSSDLtEjZy5yxJy4qmMjMhzOkO_6wEmtTFmQg4zHs13rB11mrBQ85eJvXn6tjrCB-5ecCYmKtNkPaV47C6s9q2ObA29eeBHTnF5ceZuJ3pm5AZjJOZomrg4pzizD3IKkCQ';
  // $api_key = CONSTANT_CONTACT_TOKEN;
  // Refresh Token
  // ozgd8eZC_S_lGHt1L-EC3TFFVfuRuJvDeTJSNKL4J-U

  $headers = [
    'Content-Type' => 'application/json',
    'Authorization' => 'Bearer ' . $api_token,
    // 'Authorization' => 'Bearer ' . $api_key,
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
    // dump($response);
    error_log('Constant Contact API Request Error: ' . $response->get_error_message());
    wp_send_json_error('Error sending data to Constant Contact.');
  } else {
    $response_code = wp_remote_retrieve_response_code($response);
    if ($response_code === 200 || $response_code === 201 || $response_code === 202) {
      // wp_send_json_success('Data sent to Constant Contact successfully.');
    } else {
      // dump($response_code, $response);
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
// API Key > Secret Key > Authorization Code - To get Token
// echo getAccessToken('https://localhost', '338566b6-fd20-4178-97f8-c5247baca73a', 'l_DiByiWMgsJ6sNJzufnWg', 'RvBF-aXdrCkV2j8A1GNWChk2P9zBpH1qJKKjTX5Wp1Y&state=235o250eddsdff');