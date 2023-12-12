<?php

/**
 * Theme filters.
 */

namespace App;

use WP_Query;
use Log1x\SageSvg\SageSvg;
use function Roots\app;


/**
 * ajaxURL access global.
 */
function ajax_url()
{
    echo '<script type="text/javascript">
            const ajaxurl = "' . admin_url('admin-ajax.php') . '";
        </script>';
}

add_action('wp_head', __NAMESPACE__ . '\\ajax_url');
/**
 * Add "… Continued" to the excerpt.
 *
 * @return string
 */
add_filter('excerpt_more', function () {
    return false;
});

add_filter('wpsl_templates',  function ($templates) {

    /**
     * The 'id' is for internal use and must be unique ( since 2.0 ).
     * The 'name' is used in the template dropdown on the settings page.
     * The 'path' points to the location of the custom template,
     * in this case the folder of your active theme.
     */
    $templates[] = array(
        'id'   => 'custom',
        'name' => 'Custom template',
        'path' => get_stylesheet_directory() . '/' . 'resources/views/template-locater.php',
    );

    return $templates;
});

add_filter('wpsl_store_meta', function ($store_meta, $store_id) {

    $terms = get_the_terms($store_id, 'wpsl_store_category');

    $store_meta['terms'] = '';

    if ($terms) {
        if (!is_wp_error($terms)) {
            if (count($terms) > 1) {
                $location_terms = array();

                foreach ($terms as $term) {
                    $location_terms[] = $term->name;
                }

                $store_meta['terms'] = implode(', ', $location_terms);
            } else {
                $store_meta['terms'] = $terms[0]->name;
            }
        }
    }

    return $store_meta;
}, 10, 2);



/**
 * prevent default template to load
 */
add_filter('wpsl_skip_cpt_template', '__return_true');

/**
 * load store-locator
 */
add_filter('wpsl_listing_template', function () {

    global $wpsl, $wpsl_settings;
    // render svg tag
    $formIcon = \App(SageSvg::class)->render('images.human-form', 'w-75');
    $formDescription = get_field('form_description', 'option');


    return
        "
    <li data-key='<%= id %>'>
        <div class='store d-flex gap-3 p-3 flex-column flex-lg-row pb-5'>
            <%= thumb %>
            <div class='w-100'>
                <div>
                    <h3 class='store-single-title text-primary fw-semibold fs-5'> <%= store %> </h3>
                    <% if ( terms ) { %>
                        <p class='fw-light mb-0'><%= terms %></p>
                    <% } %>

                    <div class='w-100 bg-secondary my-2' style='height:2px';></div>

                        <span class='store-address fw-light'> <%= address %> </span>

                        <% if ( address2 ) { %> 
                        <span class='store-address fw-light mb-2'><%= address2 %></span>
                        <% } %>

                        <p class='store-address fw-light mb-0'> <%= city %>, <%= zip %> </p>

                        <p class='store-address fw-light mb-0'> <%= state %>, <%= country %> </p>
                </div>
                <div>
                    <p class='text-primary fw-light'>
                        <i class='bi bi-geo-alt'></i> 
                        <%= distance %> $wpsl_settings[distance_unit]
                    </p>
                </div>

                <div class ='locator-buttons d-flex gap-3 mt-2 flex-column flex-xl-row'>
                <a href='<%= permalink %>?distance=<%= distance %>$wpsl_settings[distance_unit]' class='btn btn-light border-dark-subtle text-capitalize shadow'>view profile</a>
                <% if (phone) { %>
                    <a href='tel:<%= phone %>' class='btn btn-primary shadow surgeon-phone'><%= phone %></a>
                <% } %>                  
                </div>
            </div>
        </div>
    </li>

        <div class='modal fade ' id='iTouchModal-<%= id %>' tabindex='-1' aria-labelledby='iTouchModalLabel' aria-hidden='true'>
            <div class='modal-dialog modal-dialog-centered modal-lg'>
                <div class='modal-content'>
                    <div class='modal-header border-0 px-4 pb-0'>
                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                    </div>
                    <div class='modal-img-box row px-3 align-items-center w-100 m-auto'>
                        <div class='col-12 col-md-8'>
                            <h1 class='modal-title text-primary fw-semibold fs-2 px-1' id='iTouchModalLabel'>Contact Us</h1>
                            <p class='modal-description ps-2 pt-0'> $formDescription</p>
                        </div>
                        <div class='col-4'>
                            $formIcon
                        </div>
                    </div>
        
                    <div class='modal-body pt-0'>
                        " . do_shortcode("[advanced_form form='form_get_in_touch']") . "
                    </div>
                </div>
            </div>
        </div>
        ";
});

/**
 * Search Form Redirect URL
 */
add_action('af/form/submission/key=form_search_location', function ($form, $fields, $args) {

    $zip = af_get_field('find_a_bunion_doctor_near_you');
    $radius = af_get_field('radius');

    $urlQuery = ['zip_code' => $zip];

    if ($radius) {
        $urlQuery = [
            'zip_code' => $zip,
            'radius' => $radius,
        ];
    }

    $query = http_build_query($urlQuery);
    $location = home_url('/find-a-doctor/') . '?' . $query;

    header("Location: " . $location);
    exit;
}, 10, 3);

/**
 * Allow Svg Upload
 */
add_filter('upload_mimes', function ($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
});

/**
 * Blog load more posts
 */
function load_more_posts()
{
    $next_page = $_POST['current_page'] + 1;
    $query = new WP_Query([
        'post_type' => 'blog',
        'posts_per_page' => 6,
        'paged' => $next_page,
    ]);

    if ($query->have_posts());

    // to not pre-load blog
    ob_start();


    while ($query->have_posts()) : $query->the_post();

        $title = get_the_title();
        $description = get_the_excerpt();
        $image = get_the_post_thumbnail(get_the_ID(), 'full', ['class' => 'img-fluid']);
        $link = get_permalink();

        echo \Roots\view("components.card")->with([
            'title' => $title,
            'description' => $description,
            'image' => $image,
            'link' => $link,
        ])->render();

    endwhile;

    wp_send_json_success(ob_get_clean());
}

add_action('wp_ajax_nopriv_load_more_posts', __NAMESPACE__ . '\\load_more_posts');
add_action('wp_ajax_load_more_posts', __NAMESPACE__ . '\\load_more_posts');


/**
 * Contact Form Submission > Contact Constant
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
            '082f969e-95bd-11ee-9041-fa163e8d7f7f'
        ]
    ];

    $api_url = 'https://api.cc.email/v3/contacts';
    $api_key = CONSTANT_CONTACT_TOKEN;

    $headers = [
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . $api_key,
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
// API Key > Secret Key > Authorization Code > Token
// echo getAccessToken('https://localhost', '338566b6-fd20-4178-97f8-c5247baca73a', 'l_DiByiWMgsJ6sNJzufnWg', '6cOLFAEX0ub3ZzgC71_GKCATyrvLXnihQORjgqHwBZ4&state=235o250eddsdff');
