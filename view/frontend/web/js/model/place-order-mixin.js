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
                    payload['pmeds-questions-data'] = questionsPassedData;
                    registry.get('localStorage').remove('products-questionnaire-passed');
                }
            } catch (e) {
                console.log(e);
            }

            return originalAction(serviceUrl, payload, messageContainer);
        });
    };
});