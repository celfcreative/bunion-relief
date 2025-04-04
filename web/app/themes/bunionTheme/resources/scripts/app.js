import {clippingParents} from '@popperjs/core';
import 'bootstrap';
import * as bootstrap from 'bootstrap';
import domReady from '@roots/sage/client/dom-ready';
import loadMore from './loadMore';
import Swiper from 'swiper';
import axios from 'axios';

import {
  Autoplay,
  FreeMode,
  Navigation,
  Pagination,
  Thumbs,
  HashNavigation,
} from 'swiper/modules';
import * as imgSrc from '../../resources/images/human-form-white.svg';

/**
 * Application entrypoint
 */
domReady(async () => {
  /**
   * 
  Stepper
   */
  const blockStepper = document.querySelectorAll('.wp-block-stepper');

  blockStepper.forEach(function (blockStep) {
    if (blockStep) {
      const swiperStepper = new Swiper('.stepper-slider', {
        on: {
          init: function () {
            activateStep(this.activeIndex);
          },
        },
        modules: [Pagination, HashNavigation, Navigation],
        direction: 'vertical',
        pagination: {
          el: '.swiper-pagination',
          clickable: true,
        },
        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev',
        },
        hashNavigation: {
          watchState: true,
        },
      });

      function activateStep(slideID) {
        const stepperSingle = document.querySelectorAll('.stepper-single');

        stepperSingle.forEach((curStep, index) => {
          if (slideID == index) {
            curStep.classList.remove('opacity-50');
          } else {
            curStep.classList.add('opacity-50');
          }
        });
      }

      swiperStepper.on('slideChange', function () {
        activateStep(this.activeIndex);
      });
    }
  });

  /**
  Tabs
  */
  const tab = document.querySelectorAll('.wp-block-tabs');

  tab.forEach(function (el) {
    const tabLink = el.querySelectorAll('.nav-link');
    const tabPane = el.querySelectorAll('.tab-pane');

    tabLink.forEach(function (singleTab, index) {
      tabLink[0].classList.add('active');
    });
    tabPane.forEach(function (singleTab, index) {
      tabPane[0].classList.add('active');
    });
  });

  /**
  Store locator
   */
  const listItems = document.querySelectorAll('.acf-checkbox-list li');
  const changeIndex = document.querySelectorAll('.af-page-button .title');
  const btnSingle = document.querySelector('.btnStoreSingle');
  const surgeonTitle = document.querySelector('.store-single-title')?.innerHTML;
  const surgeonTitleBox = document.querySelector('#acf-field_65044e3b06428');
  const searchBox = document.querySelector('#wpsl-search-input');
  const searchSurgeonBtn = document.getElementById('wpsl-search-btn');

  /**
   * Format phone number
   * @param {string} phoneNumber - Phone number in the format +1XXXXXXXXXX
   * @returns {string} - Formatted phone number in the format (XXX)XXX-XXXX
   */
  function formatPhoneNumbers(phoneNumber) {
    const cleaned = ('' + phoneNumber).replace(/\D/g, '');
    const match = cleaned.match(/^1?(\d{3})(\d{3})(\d{4})$/);
    if (match) {
      return `(${match[1]}) ${match[2]}-${match[3]}`;
    }
    return phoneNumber;
  }

  // Single Surgeon Auto Populate Name > Form
  if (btnSingle) {
    btnSingle.addEventListener('click', function () {
      surgeonTitleBox.setAttribute('value', surgeonTitle);
    });
  }

  /**
   * Render surgeons on wpsl
   */
  document.addEventListener('DOMContentLoaded', function () {
    const storeList = document.querySelector('#wpsl-stores ul');
    const config = {attributes: true, childList: true, subtree: true};

    const observer = new MutationObserver(function () {
      const storeListItems = storeList.querySelectorAll('li');

      storeListItems.forEach(function (listItem) {
        const dataKey = listItem.getAttribute('data-key');

        // console.log(listItem);
        const surgeonTitle = listItem.querySelector(
          '.store-single-title',
        )?.innerHTML;
        const surgeonTitleBox = document.querySelector(
          '#iTouchModal-' + dataKey + ' #acf-field_65044e3b06428',
        );

        const getTouchBtn = listItem.querySelector('.btnTouch');

        if (getTouchBtn && surgeonTitleBox) {
          getTouchBtn?.addEventListener('click', function () {
            surgeonTitleBox.setAttribute('value', surgeonTitle);
          });
        }
      });
    });

    if (storeList) {
      observer.observe(storeList, config);
    }
  });

  // document.addEventListener('DOMContentLoaded', function () {
  //   setTimeout(function () {
  //     const storeListItems = document.querySelectorAll(
  //       '#wpsl-stores .surgeon-phone',
  //     );
  //     storeListItems.forEach(function (storeListItem) {
  //       const rawPhoneNumber = storeListItem;
  //       const formattedNumber = formatPhoneNumbers(rawPhoneNumber);
  //       rawPhoneNumber.innerHTML = formattedNumber;
  //     });
  //   }, 5000);
  // });

  document.addEventListener('DOMContentLoaded', function () {
    /**
     * Format phone number
     * @param {string} phoneNumber - Phone number in the format +1XXXXXXXXXX
     * @returns {string} - Formatted phone number in the format (XXX)XXX-XXXX
     */
    function formatPhoneNumbers(phoneNumber) {
      const cleaned = ('' + phoneNumber).replace(/\D/g, '');
      const match = cleaned.match(/^1?(\d{3})(\d{3})(\d{4})$/);
      if (match) {
        return `(${match[1]}) ${match[2]}-${match[3]}`;
      }
      return phoneNumber;
    }

    function formatAllPhoneNumbers() {
      const storeListItems = document.querySelectorAll(
        '#wpsl-stores .surgeon-phone',
      );
      storeListItems.forEach(function (storeListItem) {
        const formattedNumber = formatPhoneNumbers(storeListItem);
        storeListItem.innerHTML = formattedNumber;
      });
    }

    // Increase the timeout to 10 seconds
    setTimeout(function () {
      if (document.querySelectorAll('#wpsl-stores .surgeon-phone').length > 0) {
        formatAllPhoneNumbers();
      } else {
        console.warn('No phone numbers found to format.');
      }
    }, 10000);
  });

  /**
  Symptom checker progress bar
  */
  for (let i = 0; i < changeIndex.length; i++) {
    let qPercent = Math.round((i / (changeIndex.length - 1)) * 100) + '%';

    changeIndex[i].innerHTML = qPercent;

    // console.log(qPercent);
  }

  /**
  result - inject Zip Code > URL > redirect search
  */
  const queryZipCode = window.location.search;
  const urlParams = new URLSearchParams(queryZipCode);
  const zipCode = urlParams.get('zip_code');
  const radiusCode = urlParams.get('radius');

  const authorizeClick = function () {
    searchSurgeonBtn.click();
  };

  function selectRadius(id, valueSelect) {
    let element = document.getElementById(id);
    element.value = valueSelect;
  }

  if (zipCode || radiusCode) {
    searchBox.setAttribute('value', zipCode);
    selectRadius('wpsl-radius-dropdown', radiusCode);

    window.onload = function () {
      authorizeClick();
    };
  }

  /**
  extract Distance in URL to Profile
  */
  document.addEventListener('DOMContentLoaded', function () {
    const queryDistance = window.location.search;
    const urlDistanceParam = new URLSearchParams(queryDistance);
    const distance = urlDistanceParam.get('distance');
    const distanceIcon = document.querySelector('.locate-icon');
    const distanceFrom = document.querySelector('.distanceFrom');


    // Compatibility issue with mobile url parameters
    const screenWidth = window.innerWidth;

    if (distanceIcon !== null && distanceFrom !== null) {
      if (distance) {
        distanceFrom.textContent = distance;
      } else {
        distanceIcon.classList.add('d-none');
      }

      // if (screenWidth < 768 && distance) {
      //   distanceIcon.classList.add('d-none');
      //   distanceFrom.classList.add('d-none');
      // }
    }
  });

  /**
  Blog Trigger Modal
  */
  document.addEventListener('DOMContentLoaded', function () {
    const modalDownloadElement = document.querySelector('#downloadModal');

    if (modalDownloadElement) {
      const modalDownload = new bootstrap.Modal(modalDownloadElement);

      function checkScrollPosition() {
        const scrollPosition = window.scrollY;
        const windowHeight = window.innerHeight;

        if (scrollPosition >= windowHeight / 2) {
          modalDownload.show();
          window.removeEventListener('scroll', checkScrollPosition);
        }
      }
      window.addEventListener('scroll', checkScrollPosition);
    }
    // console.log(document.querySelector('.acf-form-submit'));
  });

  /**
   * ACF Form Select Placeholder
   */
  document.addEventListener('DOMContentLoaded', function () {
    const waitForElement = setInterval(function () {
      const contactPlaceholderElement = document.querySelector(
        '.select2-selection__rendered',
      );

      if (contactPlaceholderElement) {
        clearInterval(waitForElement);

        const contactPlaceholderText =
          'What system do you want to learn more about?';
        contactPlaceholderElement.classList.add('text-secondary');
        contactPlaceholderElement.textContent = contactPlaceholderText;
      }
    }, 100);

    setTimeout(function () {
      clearInterval(waitForElement);
    }, 5000);
  });

  // Symptom Quiz Next on Scroll top
  document.addEventListener('DOMContentLoaded', function () {
    const quizForm = document.querySelector('#form_symptom_checker');
    const quizFormBtns = document.querySelectorAll(
      '#form_symptom_checker .af-next-button',
    );

    quizFormBtns.forEach((btn) => {
      btn.addEventListener('click', function () {
        quizForm.scrollIntoView({behavior: 'smooth'});
      });
    });
  });
});

loadMore();

/**
 * Change Human SVG Icon to Black
 */
document.addEventListener('DOMContentLoaded', function () {
  const searchFormContainer = document.querySelector('#searchform_secondary');

  if (searchFormContainer) {
    const searchFormImage =
      searchFormContainer.querySelector('img:first-child');

    if (searchFormImage) {
      searchFormImage.src = imgSrc.default;
      searchFormImage.classList.add('w-100');
    } else {
      console.log('no <img> tag is found');
    }
  }
});

/**
 * @see {@link https://webpack.js.org/api/hot-module-replacement/}
 */
if (import.meta.webpackHot) import.meta.webpackHot.accept(console.error);
