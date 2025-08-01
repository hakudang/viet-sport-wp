<?php

namespace WPStaging\Framework\Analytics;

use WPStaging\Framework\Notices\Notices;
use WPStaging\Core\WPStaging;

use function WPStaging\functions\debug_log;

class AnalyticsConsent
{
    use WithAnalyticsAPI;

    const OPTION_NAME_ANALYTICS_HAS_CONSENT = 'wpstg_analytics_has_consent';
    const OPTION_NAME_ANALYTICS_NOTICE_DISMISSED = 'wpstg_analytics_notice_dismissed';
    const OPTION_NAME_ANALYTICS_MODAL_DISMISSED = 'wpstg_analytics_modal_dismissed';
    const OPTION_NAME_ANALYTICS_REMIND_ME = 'wpstg_analytics_consent_remind_me';

    /**
     * Shows the admin notice asking the user whether he consents to sending usage information to WP STAGING.
     * @action admin_notices
     */
    public function maybeShowConsentNotice()
    {
        // Early bail: Not a WP STAGING page
        if (!WPStaging::make(Notices::class)->isWPStagingAdminPage()) {
            return;
        }

        // Early bail: User does not have enough access to consent, or has consented already.
        if (!current_user_can('manage_options') || get_option(self::OPTION_NAME_ANALYTICS_NOTICE_DISMISSED)) {
            return;
        }

        $remindMe = get_option(self::OPTION_NAME_ANALYTICS_REMIND_ME);

        if (!empty($remindMe) && time() < $remindMe) {
            return;
        }

        $notice = WPSTG_VIEWS_DIR . 'notices/analytics-consent.php';

        if (!file_exists($notice)) {
            return;
        }

        include_once $notice;
    }

    /**
     * If the request that sends the consent fails, we show a notice to let the user know.
     * @action admin_notices
     */
    public function maybeShowConsentFailureNotice()
    {
        // Early bail: Not a WP STAGING page
        if (!WPStaging::make(Notices::class)->isWPStagingAdminPage()) {
            return;
        }

        // Early bail
        if (!isset($_GET['wpstgConsentFailed'])) {
            return;
        }

        $notice = WPSTG_VIEWS_DIR . 'notices/analytics-consent-failed.php';

        if (!file_exists($notice)) {
            return;
        }

        include_once $notice;
    }

    /**
     * Listens for whether the user has given or denied consent to send usage information.
     * @action admin_init
     */
    public function listenForConsent()
    {
        // Early bail: Not a consent request
        if (!isset($_GET['wpstgConsent'])) {
            return;
        }

        // Early bail: Not enough permissions
        if (!current_user_can('manage_options')) {
            return;
        }

        // Early bail: Invalid nonce
        check_ajax_referer('wpstg_consent_nonce', 'wpstgConsentNonce');

        if ($_GET['wpstgConsent'] == 'later') {
            update_option(self::OPTION_NAME_ANALYTICS_REMIND_ME, strtotime('+7 days'), false);

            return;
        }

        // Early bail: User has not consented
        if ($_GET['wpstgConsent'] == 'no') {
            update_option(self::OPTION_NAME_ANALYTICS_NOTICE_DISMISSED, '1', false);
            update_option(self::OPTION_NAME_ANALYTICS_MODAL_DISMISSED, '1', false);
            update_option(self::OPTION_NAME_ANALYTICS_HAS_CONSENT, '0', false);
            delete_option(self::OPTION_NAME_ANALYTICS_REMIND_ME);

            add_action(Notices::ACTION_ADMIN_NOTICES, [$this, 'showNoticeConsentRefused']);

            return;
        }

        if ($_GET['wpstgConsent'] == 'yes') {
            try {
                $this->giveConsent();
            } catch (\Exception $e) {
                // Show notice informing the user
                wp_redirect(add_query_arg([
                    'wpstgConsentFailed' => true,
                ], $this->getReturnUrl()));
                exit;
            }

            update_option(self::OPTION_NAME_ANALYTICS_NOTICE_DISMISSED, '1', false);
            update_option(self::OPTION_NAME_ANALYTICS_MODAL_DISMISSED, '1', false);
            update_option(self::OPTION_NAME_ANALYTICS_HAS_CONSENT, '1', false);
            delete_option(self::OPTION_NAME_ANALYTICS_REMIND_ME);
        }
    }

    public function showNoticeConsentRefused()
    {
        $notice = WPSTG_VIEWS_DIR . 'notices/analytics-consent-refused.php';

        if (!file_exists($notice)) {
            return;
        }

        include_once $notice;
    }

    /**
     * Registers the consent on the Analytics database
     *
     * @throws \Exception
     */
    public function giveConsent()
    {
        $url = $this->getApiUrl('consent');

        $response = wp_remote_post($url, [
            'method' => 'POST',
            'headers' => ['Content-Type' => 'application/json; charset=utf-8'],
            'body' => json_encode([
                'site_hash' => $this->getSiteHash(),
                'site_url' => get_home_url(),
            ]),
            'data_format' => 'body',
            'timeout' => 10,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking' => true,
            'sslverify' => false,
        ]);

        // Early bail: Something went wrong with the consent request.
        if (is_wp_error($response) || !in_array(wp_remote_retrieve_response_code($response), [201, 409])) {
            $errorMessage = is_wp_error($response) ? $response->get_error_message() : wp_remote_retrieve_body($response);
            debug_log('WP STAGING Analytics Send Error: ' . $errorMessage, 'debug');

            // Dismiss the consent notice so that it doesn't appear anymore
            update_option(self::OPTION_NAME_ANALYTICS_NOTICE_DISMISSED, '1', false);

            // Dismiss the consent modal so that id doesn't appear anymore
            update_option(self::OPTION_NAME_ANALYTICS_MODAL_DISMISSED, '1', false);

            // give consent to be able to send data when network is back
            update_option(self::OPTION_NAME_ANALYTICS_HAS_CONSENT, '1', false);

            throw new \Exception();
        }
    }

    /**
     * @return bool|null Whether the user has consented to the Analytics. Null if didn't answer.
     */
    public function hasUserConsent()
    {
        return get_option(self::OPTION_NAME_ANALYTICS_HAS_CONSENT, null);
    }

    /**
     * Invalidate the fact that the user has consented.
     * @todo remove this after testing
     */
    public function invalidateConsent()
    {
        delete_option(self::OPTION_NAME_ANALYTICS_NOTICE_DISMISSED);
        delete_option(self::OPTION_NAME_ANALYTICS_HAS_CONSENT);
    }

    protected function getReturnUrl(): string
    {
        global $pagenow, $plugin_page;

        return add_query_arg('page', $plugin_page, admin_url($pagenow));
    }

    /**
     * @param bool $agreeOrDecline True to generate a link that agrees to send usage information. False to generate a link that disagrees.
     *
     * @return string The link to either agree or decline analytics.
     */
    public function getConsentLink(bool $agreeOrDecline): string
    {
        return add_query_arg([
            'wpstgConsent' => $agreeOrDecline ? 'yes' : 'no',
            'wpstgConsentNonce' => wp_create_nonce('wpstg_consent_nonce'),
        ], $this->getReturnUrl());
    }

    public function getRemindMeLaterConsentLink(): string
    {
        return add_query_arg([
            'wpstgConsent' => 'later',
            'wpstgConsentNonce' => wp_create_nonce('wpstg_consent_nonce'),
        ], $this->getReturnUrl());
    }
}
