"use strict";

(function ($, api) {
  api.panel('nifty_cs_panel', function (section) {
    section.expanded.bind(function (isExpanding) {
      let currentUrl = api.previewer.previewUrl();

      // Value of isExpanding will = true if you're entering the section, false if you're leaving it.
      if (isExpanding) {
        // Only send the previewer to custom page, if we're not already on it.
        currentUrl = currentUrl.includes(niftyCsCustomizer.page);
        if (!currentUrl) {
          api.previewer.send('nifty-cs-url-switcher', {
            expanded: isExpanding
          });
        }
      } else {
        // Head back to the home page, if we leave the Nifty Options panel.
        api.previewer.send('nifty-cs-back-to-home', {
          home_url: api.settings.url.home
        });
      }
    });
  });
})(jQuery, wp.customize);