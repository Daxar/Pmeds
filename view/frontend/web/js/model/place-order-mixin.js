define([
    'jquery',
    'mage/utils/wrapper',
    'uiRegistry'
], function (
    $,
    wrapper,
    registry
) {
    'use strict';

    return function (placeOrderAction) {
        return wrapper.wrap(placeOrderAction, function (originalAction, serviceUrl, payload, messageContainer) {
            try {
                var questionsPassedData = registry.get('localStorage').get('products-questionnaire-passed');

                if (questionsPassedData !== undefined) {
                    questionsPassedData = $.extend({}, questionsPassedData, {'masked_cart_id': payload.cartId})
                }

                registry.get('localStorage').set('products-questionnaire-passed', questionsPassedData);
            } catch (e) {
                console.log(e);
            }

            return originalAction(serviceUrl, payload, messageContainer);
        });
    };
});