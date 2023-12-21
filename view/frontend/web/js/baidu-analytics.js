/* jscs:disable */
/* eslint-disable */
define([
    'jquery',
    'mage/cookies'
], function ($) {
    'use strict';

    /**
     * @param {Object} config
     */
    return function (config) {
        var allowServices = false,
            allowedCookies,
            allowedWebsites,
            measurementId;

        if (!config.analyticsActive) {
            allowServices = false;
        } else if (config.isCookieRestrictionModeEnabled) {
            allowedCookies = $.mage.cookies.get(config.cookieName);

            if (allowedCookies !== null) {
                allowedWebsites = JSON.parse(allowedCookies);

                if (allowedWebsites[config.currentWebsite] === 1) {
                    allowServices = true;
                }
            }
        } else {
            allowServices = true;
        }

        if (allowServices) {
            var accountId = config.pageTrackingData.accountId;

            if (!window._hmt) {
                var _hmt = _hmt || [];
                (function () {
                    var hm = document.createElement("script");
                    hm.src = "https://hm.baidu.com/hm.js?" + accountId;
                    var s = document.getElementsByTagName("script")[0];
                    s.parentNode.insertBefore(hm, s);
                })();

                _hmt.push(['_setAccount', accountId]);
                _hmt.push(['_setAutoPageview', false]);
                _hmt.push(['_trackPageview', config.pageTrackingData.optPageUrl]);
            }

            // Purchase Event
            if (config.ordersTrackingData.hasOwnProperty('currency')) {
                var purchaseObject = config.ordersTrackingData.orders[0];
                purchaseObject['items'] = config.ordersTrackingData.products;
                _hmt.push(['_trackEvent', 'purchase', JSON.stringify(purchaseObject)]);
            }
        }
    }
});
