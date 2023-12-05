import {clippingParents} from '@popperjs/core';
import 'bootstrap';
import * as bootstrap from 'bootstrap';
import domReady from '@roots/sage/client/dom-ready';
import loadMore from './loadMore';
import Swiper from 'swiper';
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

  /**
  Symptom checker progress bar
  */
  for (let i = 0; i < changeIndex.length; i++) {
    let qPercent = Math.round(((i + 1) / changeIndex.length) * 100) + '%';

    changeIndex[i].innerHTML = qPercent;

    console.log(qPercent);
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

  if (radiusCode) {
    selectRadius('wpsl-radius-dropdown', radiusCode);
  }

  if (zipCode) {
    searchBox.setAttribute('value', zipCode);
    window.onload = function () {
      authorizeClick();
    };
  }

  /**
  extract Distance in URL to Profile
  */
  const queryDistance = window.location.search;
  const urlDistanceParam = new URLSearchParams(queryDistance);
  const distance = urlDistanceParam.get('distance');
  const distanceIcon = document.querySelector('.locate-icon');
  const distanceFrom = document.querySelector('.distanceFrom');

  if (distanceIcon !== null && distanceFrom !== null) {
    if (distance) {
      distanceFrom.textContent = distance;
    } else {
      distanceIcon.classList.add('d-none');
    }
  }

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
    console.log(document.querySelector('.acf-form-submit'));
  });

  /**
   * Auto Format Phone Number
   */
  document.addEventListener('DOMContentLoaded', function () {
    function formatPhoneNumber(phoneNumber) {
      phoneNumber = phoneNumber.replace(/\D/g, '');

      if (phoneNumber.length === 10) {
        phoneNumber = phoneNumber.replace(/(\d{3})(\d{3})(\d{4})/, '$1-$2-$3');
      }
      return phoneNumber;
    }

    const observer = new MutationObserver(function (mutationLists, observer) {
      // const surgeonPhoneElements = document.querySelectorAll('.surgeon-phone');
      mutationLists.forEach(function (mutation) {})
    })
    setTimeout(function () {
      let surgeonPhoneElements = document.querySelectorAll('.surgeon-phone');

      surgeonPhoneElements.forEach(function (element) {
        let phoneNumber = element.textContent.trim();
        phoneNumber = formatPhoneNumber(phoneNumber);
        element.textContent = phoneNumber;
      });
    }, 500);
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
        console.log(contactPlaceholderText);
      }
    }, 100);

    setTimeout(function () {
      clearInterval(waitForElement);
      console.error(
        'Element with class .select2-selection__rendered not found',
      );
    }, 5000);
  });

  /**
   * Custom Search Form with Map
   */
  document.addEventListener('DOMContentLoaded', function () {
    const formSearch = document.querySelector('#search_form_location_radius');

    if (formSearch) {
      const selectRadiusDiv = formSearch.querySelector('#select-radius');

      if (selectRadiusDiv) {
        const inputRadius = selectRadiusDiv.getElementsByClassName('af-input');

        if (inputRadius.length > 0) {
          const dropdown = inputRadius[0].querySelector('select');

          if (dropdown) {
            formSearch.addEventListener('submit', function (event) {
              event.preventDefault();

              const selectedRadius = dropdown.value;
              console.log('selected radius:', selectedRadius);

              const currentPageURL = window.location.href;
              console.log('current page url:', currentPageURL);

              const url = new URL(currentPageURL);

              url.searchParams.set('radius', selectedRadius);
              console.log('updated url:', url.toString());

              window.location.href = url.toString();
            });
          } else {
            console.log('Dropdown not found inside #select-radius');
          }
        }
      } else {
        console.log('Element with ID #select-radius not found');
      }
    } else {
      console.log('Form #search_form_location_radius not found');
    }
  });
});

loadMore();

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
