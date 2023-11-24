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

  document.addEventListener('DOMContentLoaded', function () {
    // Multiple Surgeon page, populate Name > Form
    const storeList = document.querySelector('#wpsl-stores ul');
    const config = {attributes: true, childList: true, subtree: true};

    const observer = new MutationObserver(function () {
      const storeListItems = storeList.querySelectorAll('li');

      storeListItems.forEach(function (listItem) {
        const dataKey = listItem.getAttribute('data-key');

        console.log(listItem);
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

  const authorizeClick = function () {
    searchSurgeonBtn.click();
  };

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

  if (distance) {
    distanceFrom.textContent = distance;
  } else {
    distanceIcon.classList.add('d-none');
  }

  /**
  Blog Trigger Modal
  */

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
