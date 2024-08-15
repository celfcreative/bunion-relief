<?php

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our theme. We will simply require it into the script here so that we
| don't have to worry about manually loading any of our classes later on.
|
*/

if (!file_exists($composer = __DIR__ . '/vendor/autoload.php')) {
    wp_die(__('Error locating autoloader. Please run <code>composer install</code>.', 'sage'));
}

require $composer;

/*
|--------------------------------------------------------------------------
| Register The Bootloader
|--------------------------------------------------------------------------
|
| The first thing we will do is schedule a new Acorn application container
| to boot when WordPress is finished loading the theme. The application
| serves as the "glue" for all the components of Laravel and is
| the IoC container for the system binding all of the various parts.
|
*/

if (!function_exists('\Roots\bootloader')) {
    wp_die(
        __('You need to install Acorn to use this theme.', 'sage'),
        '',
        [
            'link_url' => 'https://roots.io/acorn/docs/installation/',
            'link_text' => __('Acorn Docs: Installation', 'sage'),
        ]
    );
}

\Roots\bootloader()->boot();

/*
|--------------------------------------------------------------------------
| Register Sage Theme Files
|--------------------------------------------------------------------------
|
| Out of the box, Sage ships with categorically named theme files
| containing common functionality and setup to be bootstrapped with your
| theme. Simply add (or remove) files from the array below to change what
| is registered alongside Sage.
|
*/

collect(['setup', 'filters', 'image', 'api', 'constant', 'helper'])
    ->each(function ($file) {
        if (!locate_template($file = "app/{$file}.php", true, true)) {
            wp_die(
                /* translators: %s is replaced with the relative file path */
                sprintf(__('Error locating <code>%s</code> for inclusion.', 'sage'), $file)
            );
        }
    });


$size_names = apply_filters(
    'image_size_names_choose',
    array(
        'thumbnail' => __('Thumbnail'),
        'medium'    => __('Medium'),
        'large'     => __('Large'),
        'full'      => __('Full Size'),
    )
);

function handle_utm_campaign()
{
    if (isset($_GET['utm_source']) && isset($_GET['utm_medium']) && isset($_GET['utm_campaign'])) {
        $utmData = [
            'utm_source' => $_GET['utm_source'],
            'utm_medium' => $_GET['utm_medium'],
            'utm_campaign' => $_GET['utm_campaign'],
        ];

        $utmDataObj = json_encode($utmData);

        setcookie('utm_data', $utmDataObj, time() + 86400, '/');
    }
}
add_action('init', 'handle_utm_campaign');

function change_success_message($success_message, $form, $args)
{
    $id = af_get_field('form_resource_download');
    $downloadFileUrl = get_permalink($id);
    $fileTitle = get_the_title($id);

    if ($downloadFileUrl) {
        return '
        <style>
        .form-content {
            display: none;
        }
        </style>
        <div class="px-3">
            <h4>Thank you for completing the form</h4>
            <p>Download <strong>"' . $fileTitle . '"</strong> using the link below.</p>
            <a class="btn btn-primary file-download-button" href="' . $downloadFileUrl . '">Download</a>
        </div>';
    } else {
        return 'Sorry, no file has been found';
    }
}
add_filter('af/form/success_message/key=form_resource_download', 'change_success_message', 10, 3);

function add_cors_headers()
{
    header("Access-Control-Allow-Origin: *"); // Replace * with specific origin if needed
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");
}
add_action('init', 'add_cors_headers');

function refreshToken_deactivate()
{
    wp_clear_scheduled_hook('refreshToken');
}

function create_twilio_table()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'twilio';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        `sid` VARCHAR(255) PRIMARY KEY,
        `duration` INT,
        `from_number` VARCHAR(20),
        `status` VARCHAR(50),
        `start_time` DATETIME,
        `end_time` DATETIME,
        `to_number` VARCHAR(20),
        `direction` VARCHAR(20),
        `queue_time` INT,
        `price` DECIMAL(10, 5)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
add_action('after_setup_theme', 'create_twilio_table');

function create_analytics_table()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'analytics';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id INT AUTO_INCREMENT PRIMARY KEY,
        entry_id BIGINT,
        `question` VARCHAR(255),
        `created_at` TIMESTAMP,
        value VARCHAR(255)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
add_action('after_setup_theme', 'create_analytics_table');
