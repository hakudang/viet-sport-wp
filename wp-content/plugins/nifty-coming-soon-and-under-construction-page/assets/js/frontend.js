"use strict";

function isValidEmail(emailAddress) {
  const pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
  return pattern.test(emailAddress);
}
if (document.querySelector('#preloader')) {
  window.addEventListener('load', function () {
    jQuery(document).ready(function ($) {
      $('#preloader').fadeOut('slow', function () {
        $(this).remove();
      });
    });
  });
}
(function ($) {
  jQuery(document).ready(function () {
    const slideIcons = [];
    const activeSlides = niftyCsObject.slider_blocks;
    for (let i = 0; i < activeSlides.length; i++) {
      slideIcons.push(niftyCsObject.blocks[activeSlides[i]].icon);
    }

    // Slider.
    new Swiper('.nifty-legacy-slider', {
      loop: false,
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
        renderBullet(index, className) {
          return '<span class="' + className + '"><span aria-hidden="true" class="' + slideIcons[index] + '"></span></span>';
        }
      }
    });
    const showSubscribeMessage = (message, mode, $wrapper) => {
      const $target = $wrapper.find('.nifty-subscribe-message');
      $target.hide();
      const markup = `<div class="nifty-message nifty-message-${mode}"><p>${message}</p></div>`;
      $target.html(markup).fadeIn('slow', function () {
        setTimeout(function () {
          $target.fadeOut('slow', function () {
            $target.html('');
          });
        }, 3000);
      });
    };

    // Handle submit subscription.
    $('.nifty-subscription .nifty-subscribe').on('submit', 'form', function (e) {
      e.preventDefault();
      const $this = $(this);
      const $email = $this.find('input[type=text]');
      const email = $email.val();
      if (isValidEmail(email)) {
        const ajaxData = {
          action: 'nifty_cs_subscribe',
          email
        };
        $.post(niftyCsObject.ajax_url, ajaxData, function (response) {
          if (true === response.success) {
            showSubscribeMessage(niftyCsObject.subscription_success_message, 'success', $this.parent());
            $email.val('');
          }
        }).fail(function () {
          showSubscribeMessage(niftyCsObject.subscription_error_message, 'error', $this.parent());
        });
      } else {
        showSubscribeMessage(niftyCsObject.subscription_error_message, 'error', $this.parent());
      }
      return false;
    });
    if ($('body').hasClass('background-slider-enabled')) {
      const sliderConfig = {
        animation: 'random',
        cover: true,
        animationDuration: niftyCsObject.background_slider_animation_time,
        timer: false,
        transition: niftyCsObject.background_slider_animation,
        delay: niftyCsObject.background_slider_time,
        opacity: niftyCsObject.background_slider_pattern_opacity,
        overlay: niftyCsObject.pattern_folder_url + niftyCsObject.background_slider_pattern
      };
      const slides = [];
      for (let j = 0; j < niftyCsObject.background_slides.length; j++) {
        slides.push({
          src: niftyCsObject.background_slides[j]
        });
      }
      sliderConfig.slides = slides;
      $('#nifty, body').vegas(sliderConfig);
    }
    if ($('body').hasClass('countdown-timer-enabled')) {
      $('div#clock').countdown(niftyCsObject.countdown_time_formatted, function (event) {
        const $this = $(this);
        switch (event.type) {
          case 'seconds':
          case 'minutes':
          case 'hours':
          case 'days':
          case 'weeks':
          case 'daysLeft':
            $this.find('span#' + event.type).html(event.value);
            break;
          case 'finished':
            $this.hide();
            break;
        }
      });
    }
    if ($('body').hasClass('intro-animation-enabled')) {
      $('.tlt').textillate({
        selector: '.texts',
        loop: true,
        minDisplayTime: 2500,
        autoStart: true,
        outEffects: ['bounceOut'],
        in: {
          effect: 'fadeIn',
          delayScale: 1.5,
          delay: 50,
          sync: false,
          shuffle: true
        },
        out: {
          effect: 'bounceOut',
          delayScale: 1.5,
          delay: 150,
          sync: false,
          shuffle: true
        }
      });
    }
  });
})(jQuery);