"use strict";

(function ($, api) {
  $(document).ready(function () {
    api.preview.bind('nifty-cs-url-switcher', function (data) {
      if (true === data.expanded) {
        api.preview.send('url', niftyCsPreview.page);
      }
    });
    api.preview.bind('nifty-cs-back-to-home', function (data) {
      api.preview.send('url', data.home_url);
    });

    // Animated text.
    wp.customize('nifty_cs_option[your_coming_soon_message]', function (value) {
      value.bind(function (newval) {
        $('#animated_intro .intro-first').html(newval);
      });
    });

    // Countdown.
    wp.customize('nifty_cs_option[nifty_days_translate]', function (value) {
      value.bind(function (newval) {
        $('#clock .timer-days').html(newval);
      });
    });
    wp.customize('nifty_cs_option[nifty_hours_translate]', function (value) {
      value.bind(function (newval) {
        $('#clock .timer-hours').html(newval);
      });
    });
    wp.customize('nifty_cs_option[nifty_minutes_translate]', function (value) {
      value.bind(function (newval) {
        $('#clock .timer-minutes').html(newval);
      });
    });
    wp.customize('nifty_cs_option[nifty_seconds_translate]', function (value) {
      value.bind(function (newval) {
        $('#clock .timer-seconds').html(newval);
      });
    });

    // Social.
    wp.customize('nifty_cs_option[social_links_intro_text]', function (value) {
      value.bind(function (newval) {
        $('.nifty-socials .nifty-heading').html(newval);
      });
    });

    // Signup.
    wp.customize('nifty_cs_option[sign_up_form_intro_text]', function (value) {
      value.bind(function (newval) {
        $('.nifty-subscribe .nifty-heading').html(newval);
      });
    });
    wp.customize('nifty_cs_option[sign_up_button_text]', function (value) {
      value.bind(function (newval) {
        $('.nifty-subscribe .button').val(newval);
      });
    });
    wp.customize('nifty_cs_option[enter_email_text]', function (value) {
      value.bind(function (newval) {
        $('.nifty-subscribe input[type=text]').attr('placeholder', newval);
      });
    });

    // Background.
    wp.customize('nifty_cs_option[background_color]', function (value) {
      value.bind(function (newval) {
        $('body.nifty-cs').css({
          backgroundColor: newval
        });
      });
    });
    wp.customize('nifty_cs_option[select_pattern_overlay]', function (value) {
      value.bind(function (newval) {
        $('.vegas-overlay').css({
          backgroundImage: `url('${niftyCsPreview.pattern_folder_url}${newval}')`
        });
      });
    });
    wp.customize('nifty_cs_option[pattern_overlay_opacity]', function (value) {
      value.bind(function (newval) {
        $('.vegas-overlay').css({
          opacity: newval
        });
      });
    });

    // Subscription.
    wp.customize('nifty_cs_option[sign_up_button_color]', function (value) {
      value.bind(function (newval) {
        $('.nifty-subscription .button').css({
          backgroundColor: newval
        });
      });
    });
    wp.customize('nifty_cs_option[sign_up_button_color_hover]', function (value) {
      value.bind(function (newval) {
        $('.nifty-subscription .button:hover').css({
          backgroundColor: newval
        });
      });
    });

    // Countdown.
    wp.customize('nifty_cs_option[countdown_font_color]', function (value) {
      value.bind(function (newval) {
        $('#days, #hours, #minutes, #seconds').css({
          color: newval
        });
      });
    });
    wp.customize('nifty_cs_option[countdown_font_color_bottom]', function (value) {
      value.bind(function (newval) {
        $('.timer-bottom').css({
          color: newval
        });
      });
    });

    // Contact.
    wp.customize('nifty_cs_option[enter_you_website_or_company_name]', function (value) {
      value.bind(function (newval) {
        $('.nifty-contact-details .contact-company-name').html(newval);
      });
    });
    wp.customize('nifty_cs_option[enter_your_address]', function (value) {
      value.bind(function (newval) {
        $('.nifty-contact-details .contact-address-content').html(newval);
      });
    });
    wp.customize('nifty_cs_option[enter_your_phone_number]', function (value) {
      value.bind(function (newval) {
        $('.nifty-contact-details .contact-phone-content').html(newval);
      });
    });
    wp.customize('nifty_cs_option[enter_your_email_address]', function (value) {
      value.bind(function (newval) {
        $('.nifty-contact-details .contact-email a').text(newval);
      });
    });
  });
})(jQuery, wp.customize);