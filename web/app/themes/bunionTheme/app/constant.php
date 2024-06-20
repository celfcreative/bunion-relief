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
  $accessToken = (get_field('constant_contact_token', 'option') ? get_field('constant_contact_token', 'option') : CONSTANT_CONTACT_TOKEN);
  // $accessToken = CONSTANT_CONTACT_TOKEN;


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
      '4d8433a8-c1cd-11ee-b534-fa163e0b03e8'
    ]
  ];

  $emailExists = checkEmailExist($email);

  if ($emailExists) {
    // error_log('Email already exists in Constant Contact for: ' . $email);
    // wp_send_json_error('Email already exists in Constant Contact.');
  } else {

    $api_url = 'https://api.cc.email/v3/contacts';
    // 
    $api_token = get_field('constant_contact_token', 'option');

    $api_refreshtoken = get_field('constant_contact_refresh_token', 'option');

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
      if (WP_ENV === 'development') {
        error_log('Constant Contact API Request Error: ' . $response->get_error_message());
      }
      // Show a generic message to the user
      echo "<p>Thank you for your submission! We will be in touch soon.</p>";
    } else {
      $response_code = wp_remote_retrieve_response_code($response);
      if ($response_code === 200 || $response_code === 201 || $response_code === 202) {
        // Successful response, show a success message
        echo "<p>Thank you for your submission!</p>";
      } else {
        if (WP_ENV === 'development') {
          dump($response_code, $response);
          error_log('Constant Contact API Request Error: Unexpected response code ' . $response_code);
          wp_send_json_error('Unexpected response from Constant Contact.');
        }
        // Show a generic message to the user
        echo "<p>Thank you for your submission! We will be in touch soon.</p>";
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
  $termAgreement = af_get_field('terms_agreement') ? 'I agree to the terms of the Privacy Policy' : '';
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
    $api_token = (get_field('constant_contact_token', 'option') ? get_field('constant_contact_token', 'option') : CONSTANT_CONTACT_TOKEN);

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
      if (WP_ENV === 'development') {
        error_log('Constant Contact API Request Error: ' . $response->get_error_message());
      }
      // Show a generic message to the user
      echo "<p>Thank you for your submission! We will be in touch soon.</p>";
    } else {
      $response_code = wp_remote_retrieve_response_code($response);
      if ($response_code === 200 || $response_code === 201 || $response_code === 202) {
        // Successful response, show a success message
        echo "<p>Thank you for your submission!</p>";
      } else {
        if (WP_ENV === 'development') {
          dump($response_code, $response);
          error_log('Constant Contact API Request Error: Unexpected response code ' . $response_code);
          wp_send_json_error('Unexpected response from Constant Contact.');
        }
        // Show a generic message to the user
        echo "<p>Thank you for your submission! We will be in touch soon.</p>";
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
    $api_token = (get_field('constant_contact_token', 'option') ? get_field('constant_contact_token', 'option') : CONSTANT_CONTACT_TOKEN);

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
      if (WP_ENV === 'development') {
        error_log('Constant Contact API Request Error: ' . $response->get_error_message());
      }
      // Show a generic message to the user
      echo "<p>Thank you for your submission! We will be in touch soon.</p>";
    } else {
      $response_code = wp_remote_retrieve_response_code($response);
      if ($response_code === 200 || $response_code === 201 || $response_code === 202) {
        // Successful response, show a success message
        echo "<p>Thank you for your submission!</p>";
      } else {
        if (WP_ENV === 'development') {
          dump($response_code, $response);
          error_log('Constant Contact API Request Error: Unexpected response code ' . $response_code);
          wp_send_json_error('Unexpected response from Constant Contact.');
        }
        // Show a generic message to the user
        echo "<p>Thank you for your submission! We will be in touch soon.</p>";
      }
    }
  }
}, 10, 3);

/**
 * Symptom Quiz -> Constant Contact -> Symptom Quiz list
 */
add_action('af/form/submission/key=form_symptom_checker', function ($form, $fields, $args) {

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
  $ageRange = af_get_field('how_old_are_you');
  $email = af_get_field('email');
  $phone = af_get_field('mobile_number');
  $zipCode = af_get_field('zip_code');
  $termAgreement = af_get_field('terms_agreement') ? 'I agree to the terms of the Privacy Policy' : '';
  $question1 = af_get_field('which_of_the_following_symptoms_describes_the_bunion_on_your_big_toe');
  $question2 = af_get_field('select_an_image_that_describe_any_other_issues_you_may_have_with_your_feet');
  $question3 = af_get_field('which_of_the_following_symptoms_are_you_experiencing');
  $question4 = af_get_field('many_people_experience_pain_in_their_big_toe_as_a_result_of_having_a_bunion_have_you_ever_been_diagnosed_with_a_bunion');
  $question5 = af_get_field('has_a_doctor_ever_recommended_that_you_need_surgery_to_correct_your_bunion');
  $question6 = af_get_field('if_you_had_surgery_did_this_successfully_repair_your_bunion');

  $question1AnswersStr = implode(', ', $question1);
  $question2AnswersStr = implode(', ', $question2);
  $question3AnswersStr = implode(', ', $question3);
  $question6AnswersStr = implode(', ', $question6);

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
        'postal_code' => $zipCode,
      ]
    ],
    'custom_fields' => [
      [
        'custom_field_id' => '49110fd2-c1df-11ee-a3bc-fa163e5bf31a',
        'value' => $question1AnswersStr
      ],
      [
        'custom_field_id' => '20f67ddc-c1e1-11ee-a3bc-fa163e5bf31a',
        'value' => $question2AnswersStr
      ],
      [
        'custom_field_id' => '5bb898c4-c1e1-11ee-8eb0-fa163e6a92d8',
        'value' => $question3AnswersStr
      ],
      [
        'custom_field_id' => '6bcaa0fe-c1e1-11ee-a765-fa163ef3b06c',
        'value' => $question4
      ],
      [
        'custom_field_id' => '7b5e8cce-c1e1-11ee-a3bc-fa163e5bf31a',
        'value' => $question5
      ],
      [
        'custom_field_id' => '881a296e-c1e1-11ee-abe2-fa163eec71c4',
        'value' => $question6AnswersStr
      ],
      [
        'custom_field_id' => 'da376218-a952-11ee-8b61-fa163e6a92d8',
        'value' => $termAgreement,
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
      'd3575724-c1d4-11ee-9991-fa163e5bf31a'
    ]
  ];

  $emailExists = checkEmailExist($email);

  if ($emailExists) {
    // error_log('Email already exists in Constant Contact for: ' . $email);
    // wp_send_json_error('Email already exists in Constant Contact.');
  } else {

    $api_url = 'https://api.cc.email/v3/contacts';
    // 
    $api_token = (get_field('constant_contact_token', 'option') ? get_field('constant_contact_token', 'option') : CONSTANT_CONTACT_TOKEN);

    $api_refreshtoken = get_field('constant_contact_refresh_token', 'option');

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
      if (WP_ENV === 'development') {
        error_log('Constant Contact API Request Error: ' . $response->get_error_message());
      }
      // Show a generic message to the user
      echo "<p>Thank you for your submission! We will be in touch soon.</p>";
    } else {
      $response_code = wp_remote_retrieve_response_code($response);
      if ($response_code === 200 || $response_code === 201 || $response_code === 202) {
        // Successful response, show a success message
        echo "<p>Thank you for your submission!</p>";
      } else {
        if (WP_ENV === 'development') {
          dump($response_code, $response);
          error_log('Constant Contact API Request Error: Unexpected response code ' . $response_code);
          wp_send_json_error('Unexpected response from Constant Contact.');
        }
        // Show a generic message to the user
        echo "<p>Thank you for your submission! We will be in touch soon.</p>";
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
  $url = $base . '?code=' . $code . '&redirect_uri=' . urlencode($redirectURI) . '&grant_type=authorization_code';
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
