<!doctype html>
<html <?php language_attributes(); ?>>

<?php

if (isset($_GET['utm_source']) && isset($_GET['utm_medium']) && isset($_GET['utm_campaign'])) {
  $utmData = [
    'utm_source' => $_GET['utm_source'],
    'utm_medium' => $_GET['utm_medium'],
    'utm_campaign' => $_GET['utm_campaign'],
  ];

  $utmDataJoined = implode(" ", $utmData);
  setcookie('utm_data', $utmDataJoined, time() + 86400, '/');
}
?>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Libre+Franklin:wght@100;200;300;400;500;600;700;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
  <!-- Google tag (gtag.js) -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-72TXGS1KMM"></script>
  <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
      dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'G-72TXGS1KMM');
  </script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {

      document.body.addEventListener('click', function(event) {

        if (event.target.tagName === 'A') {
          const link = event.target.getAttribute('href');
          const url = new URL(link);

          let utmSource = '';
          let utmMedium = '';
          let utmCampaign = '';

          // Check if UTM parameters are stored in cookies
          const utmCookie = document.cookie.split('; ').find(cookie => cookie.startsWith('utm_data='));

          if (utmCookie) {
            const [cookie, cookieValue] = utmCookie.split('=');
            const decodedString = decodeURIComponent(cookieValue);

            const jsonData = {
              'message': decodedString
            };
            const jsonString = JSON.stringify(jsonData);

            utmSource = utmData.utm_source;
            utmMedium = utmData.utm_medium;
            utmCampaign = utmData.utm_campaign;
          } else {
            utmSource = url.searchParams.get('utm_source');
            utmMedium = url.searchParams.get('utm_medium');
            utmCampaign = url.searchParams.get('utm_campaign');
          }

          if (!event.target.classList.contains('doctor-profile') && !event.target.classList.contains('surgeon-phone')) {
            gtag('event', 'page_click', {
              'event_category': 'Page Click',
              'event_label': 'Page Clicked',
              'link_href': link,
              'utm_source': utmSource,
              'utm_medium': utmMedium,
              'utm_campaign': utmCampaign
            });
          }
        }

        if (event.target.classList.contains('doctor-profile')) {
          const doctorName = event.target.getAttribute('data-name');
          console.log('View Profile button clicked for Doctor:', doctorName);

          gtag('event', 'view_profile_click', {
            'event_category': 'Profile Button',
            'event_label': 'View Profile Clicked',
            'doctor_name': doctorName
          });
        }

        if (event.target.classList.contains('surgeon-phone')) {
          const doctorPhoneName = event.target.getAttribute('data-dr-phone')
          const doctorPhone = event.target.getAttribute('href');

          gtag('event', 'call_profile_click', {
            'event_category': 'Call Button',
            'event_label': 'Call Profile Clicked',
            'doctor_number': doctorPhone,
            'doctor_name': doctorPhoneName,
          });
        }
      });
    });
  </script>

  <?php wp_body_open(); ?>
  <?php do_action('get_header'); ?>

  <div id="app">
    <?php echo view(app('sage.view'), app('sage.data'))->render(); ?>
  </div>

  <?php do_action('get_footer'); ?>
  <?php wp_footer(); ?>

  <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
</body>

</html>