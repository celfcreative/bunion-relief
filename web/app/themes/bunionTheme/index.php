<!doctype html>
<html <?php language_attributes(); ?>>

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

  <?php the_field('body_tracker', 'option') ?>

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-72TXGS1KM"></script>
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

      function getCookie(name) {
        const cookies = document.cookie.split(';').map(cookie => cookie.trim());
        for (const cookie of cookies) {
          const [cookieName, cookieValue] = cookie.split('=');
          if (cookieName === name) {
            return decodeURIComponent(cookieValue);
          }
        }
        return '';
      }

      function fireEventWithUTM(eventName, eventData) {
        let utmData = getCookie('utm_data');

        if (utmData) {
          let parsedData;
          try {
            parsedData = JSON.parse(utmData);
          } catch (error) {
            console.error('Error parsing utm_data cookie:', error);
          }

          if (parsedData) {
            const {
              utm_source,
              utm_medium,
              utm_campaign
            } = parsedData;
            eventData = {
              ...eventData,
              utm_source,
              utm_medium,
              utm_campaign
            };
          }
        }

        gtag('event', eventName, eventData);
      }

      function getFormSubmissionName(formId) {
        fireEventWithUTM('form_entry', {
          'event_category': 'Form Submission',
          'event_label': formId || 'Form Submission'
        });
      }

      function checkThankYouEvent(formId) {
        const url = window.location.href;
        if (url.includes('thank-you')) {
          fireEventWithUTM('generate_lead', {
            'event_category': 'Lead Generation',
            'event_label': formId || 'Form Submission'
          })
        }
      }

      document.addEventListener('submit', function(event) {
        const form = event.target.closest('form');
        if (form) {
          const formId = form.getAttribute('data-key');
          getFormSubmissionName(formId);
          checkThankYouEvent(formId);
        }
      })

      document.body.addEventListener('click', function(event) {

        if (event.target.tagName === 'A' || (event.target.tagName === 'IMG' && event.target.parentElement.tagName === 'A')) {
          const link = event.target.getAttribute('href');
          fireEventWithUTM('page_click', {
            'event_category': 'Page Click',
            'event_label': 'Page Clicked',
            'link_href': link,
          });
        }

        if (event.target.classList.contains('doctor-profile')) {
          const doctorName = event.target.getAttribute('data-name');

          fireEventWithUTM('view_profile_click', {
            'event_category': 'Profile Button',
            'event_label': 'View Profile Clicked',
            'doctor_name': doctorName
          });
        }

        if (event.target.classList.contains('surgeon-phone')) {
          const doctorPhoneName = event.target.getAttribute('data-dr-phone')
          const doctorPhone = event.target.getAttribute('href');

          fireEventWithUTM('call_profile_click', {
            'event_category': 'Call Button',
            'event_label': 'Call Profile Clicked',
            'doctor_number': doctorPhone,
            'doctor_name': doctorPhoneName
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