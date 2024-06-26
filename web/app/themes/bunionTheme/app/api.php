<?php

use Twilio\Rest\Client;

// Define API Routes
add_action('rest_api_init', function () {
    register_rest_route('v1', '/refresh-tokens', [
        'methods' => 'GET',
        'callback' => 'refreshTokenWrapper',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('v1', '/authenticated', [
        'methods' => 'GET',
        'callback' => 'getTokens',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('v1', '/handle-auth-code', [
        'methods' => 'GET',
        'callback' => 'handleNewAuthorizationCode',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('v1', '/twilio', [
        'methods' => 'GET',
        'callback' => 'updateTwilioData',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('v1', '/analytics', [
        'methods' => 'GET',
        'callback' => 'updateAnalyticsData',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('v1', '/generate-auth-url', [
        'methods' => 'GET',
        'callback' => 'generateAuthUrl',
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

    return json_decode($result);
}

function refreshTokenWrapper()
{
    $token = refreshToken(get_field('constant_contact_refresh_token', 'option'), CONSTANT_API_KEY, CONSTANT_CONTACT_SECRET_KEY);
    if (isset($token->error)) {
        // Check if the error is due to an expired refresh token
        if ($token->error == 'invalid_grant') {
            // Redirect user to reauthorize the application
            $authorizationUrl = 'https://authz.constantcontact.com/oauth2/default/v1/authorize?client_id=' . CONSTANT_API_KEY . '&redirect_uri=' . home_url() . '&response_type=code&scope=contact_data%20campaign_data%20offline_access';
            wp_redirect($authorizationUrl);
            exit;
        } else {
            echo $token->error;
        }
    } else {
        updateRefreshToken($token->access_token, $token->refresh_token);
        echo 'Tokens have been refreshed';
    }
    die();
}

function updateRefreshToken($accessToken, $refreshToken = null)
{
    update_field('constant_contact_token', $accessToken, 'option');
    if ($refreshToken) {
        update_field('constant_contact_refresh_token', $refreshToken, 'option');
    }
}

function handleNewAuthorizationCode()
{
    if (isset($_GET['code'])) {
        $authorizationCode = $_GET['code'];
        $token = getNewTokens($authorizationCode, CONSTANT_API_KEY, CONSTANT_CONTACT_SECRET_KEY);
        if (isset($token->error)) {
            echo $token->error;
        } else {
            updateRefreshToken($token->access_token, $token->refresh_token);
        }
    } else {
        echo 'Authorization code not found.';
    }
    die();
}

function generateAuthUrl() {
    $client_id = CONSTANT_API_KEY; // Replace with your client ID
    $redirect_uri = home_url(); // Replace with your redirect URI
    $scope = 'contact_data%20campaign_data%20offline_access';
    $state = '235o250eddsdff'; // You can generate a dynamic state value if needed

    $auth_url = "https://authz.constantcontact.com/oauth2/default/v1/authorize?client_id={$client_id}&redirect_uri={$redirect_uri}&response_type=code&scope={$scope}&state={$state}";

    echo $auth_url;
    exit;
}

/**
 * @param $authorizationCode - The authorization code received after user authorization
 * @param $clientId - API Key
 * @param $clientSecret - API Secret
 * @param $redirectUri - Redirect URI registered with the Constant Contact app
 * @return string - JSON String of results
 */
function getNewTokens($authorizationCode, $clientId, $clientSecret)
{
    // Use cURL to get a new access token and refresh token using the authorization code
    $ch = curl_init();
    $url = 'https://authz.constantcontact.com/oauth2/default/v1/token';
    $data = array(
        'code' => $authorizationCode,
        'grant_type' => 'authorization_code',
        'redirect_uri' => home_url(),
        'client_id' => $clientId,
        'client_secret' => $clientSecret
    );
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    return json_decode($result);
}

function updateTwilioData()
{
    global $wpdb;

    $twilio = new Client(TWILIO_ACCOUNT_SID, TWILIO_AUTH_TOKEN);

    $calls = $twilio->calls->read([], 20);

    $table_name = $wpdb->prefix . 'twilio';

    // Add call data to response
    foreach ($calls as $record) {
        $wpdb->replace(
            $table_name,
            [
                'sid' => $record->sid,
                'duration' => $record->duration,
                'from_number' => $record->from,
                'to_number' => $record->to,
                'status' => $record->status,
                'start_time' => $record->startTime->format('Y-m-d H:i:s'),
                'end_time' => $record->endTime->format('Y-m-d H:i:s'),
                'direction' => $record->direction,
                'queue_time' => $record->queueTime,
                'price' => $record->price
            ]
        );
    }

    return 'success';
}

// API Key > Secret Key > Authorization Code - To get Access Token & Refresh Token

https: //authz.constantcontact.com/oauth2/default/v1/authorize?client_id=854738ac-84d3-4446-b736-b840e9a951d9&redirect_uri=https://localhost&response_type=code&scope=contact_data%20campaign_data%20offline_access&state=235o250eddsdff

function getTokens()
{
    return getAccessToken(home_url(), CONSTANT_API_KEY, CONSTANT_CONTACT_SECRET_KEY, get_field('constant_contact_authorization_code', 'option'));
}

function updateAnalyticsData()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'analytics';
    $wpdb->query("TRUNCATE TABLE $table_name");
    $entries = get_posts([
        'post_type' => 'af_entry',
        'posts_per_page' => '-1',
        'meta_query' => [
            [
                'key' => 'entry_form',
                'value' => 'form_symptom_checker',
            ],
        ],
    ]);
    foreach ($entries as $entry) {
        $entryID = $entry->ID;
        $questions = [
            'which_of_the_following_symptoms_describes_the_bunion_on_your_big_toe',
            'select_an_image_that_describe_any_other_issues_you_may_have_with_your_feet',
            'which_of_the_following_symptoms_are_you_experiencing',
            'many_people_experience_pain_in_their_big_toe_as_a_result_of_having_a_bunion_have_you_ever_been_diagnosed_with_a_bunion',
            'has_a_doctor_ever_recommended_that_you_need_surgery_to_correct_your_bunion',
            'if_you_had_surgery_did_this_successfully_repair_your_bunion',
            'youre_almost_done!_zip_code',
        ];
        foreach ($questions as $index => $question) {
            $values = get_field($question, $entryID);
            if (!is_array($values)) {
                $values = [$values];
            }

            foreach ($values as $value) {
                echo $entryID . '-' .
                    $index . "\n";
                echo $question . ': ' . trim(strip_tags($value)) . "\n";
                if ($value) {
                    $result = $wpdb->insert(
                        $table_name,
                        [
                            'entry_id' => $entry->ID,
                            'question' => $question,
                            'value' => trim(strip_tags($value))
                        ]
                    );

                    if (
                        $result ===
                        false
                    ) {
                        echo "Error: " . $wpdb->last_error . "\n";
                    }
                }
            }
        }
    }

    return;
}
