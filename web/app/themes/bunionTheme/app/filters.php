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
        <div class='store d-flex gap-3 p-3 px-5 flex-column flex-lg-row pb-5'>
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
                <a href='<%= permalink %>?distance=<%= distance %>$wpsl_settings[distance_unit]' class='btn btn-light border-dark-subtle shadow doctor-profile' data-name=' <%= store %> ' >View profile</a>
                <% if (phone) { %>
                    <a href='tel:<%= phone %>' class='btn btn-primary shadow surgeon-phone' data-dr-phone='<%= store %>' ><%= phone %></a>
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

    // header("Location: " . $location);
    // Output JavaScript to open the URL in a new tab and close the current page
    // echo "<script>
    //         window.open('{$location}', '_blank');
    //         window.location.href = '" . home_url() . "';
    //     </script>";
    // exit;

    // Output JavaScript to check if opening a new tab is allowed
    echo "<script>
        var newTabAllowed = false;
        var newTab = window.open('', '_blank');
        if (newTab) {
            newTabAllowed = true;
            newTab.close(); // Close the empty tab if it was successfully opened
        }

        if (newTabAllowed) {
            window.open('{$location}', '_blank');
            window.location.href = '" . home_url() . "'; // Redirect the current tab
        } else {
            window.location.href = '{$location}'; // Redirect the current tab directly
        }
        </script>";
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
 * Populate UTM Data to Hidden Field from the Cookie upon Submission
 */
add_action('af/form/entry_created/key=form_contact_form', function ($entry_id, $form) {

    if (isset($_COOKIE['utm_data'])) {
        $utm_data = $_COOKIE['utm_data'];

        $utm_data_unescaped = stripslashes($utm_data);
        $decoded_utm = json_decode($utm_data_unescaped);

        update_field('utm_source', $decoded_utm->utm_source, $entry_id);
        update_field('utm_medium', $decoded_utm->utm_medium, $entry_id);
        update_field('utm_campaign', $decoded_utm->utm_campaign, $entry_id);
    }
}, 10, 3);

add_action('af/form/entry_created/key=form_get_in_touch', function ($entry_id, $form) {

    if (isset($_COOKIE['utm_data'])) {
        $utm_data = $_COOKIE['utm_data'];

        $utm_data_unescaped = stripslashes($utm_data);
        $decoded_utm = json_decode($utm_data_unescaped);

        update_field('utm_source', $decoded_utm->utm_source, $entry_id);
        update_field('utm_medium', $decoded_utm->utm_medium, $entry_id);
        update_field('utm_campaign', $decoded_utm->utm_campaign, $entry_id);
    }
}, 10, 3);

add_action('af/form/entry_created/key=form_resource_download', function ($entry_id, $form) {

    if (isset($_COOKIE['utm_data'])) {
        $utm_data = $_COOKIE['utm_data'];

        $utm_data_unescaped = stripslashes($utm_data);
        $decoded_utm = json_decode($utm_data_unescaped);

        update_field('utm_source', $decoded_utm->utm_source, $entry_id);
        update_field('utm_medium', $decoded_utm->utm_medium, $entry_id);
        update_field('utm_campaign', $decoded_utm->utm_campaign, $entry_id);
    }
}, 10, 3);
