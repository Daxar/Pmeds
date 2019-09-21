define([
    'jquery',
    'uiComponent',
    'uiRegistry',
    'mage/url'
], function (
    $,
    component,
    registry,
    urlBuilder
) {
    'use strict';

    return component.extend({
        initialize: function () {
            this._super();

            this.saveQuestionnaireData();

            return this;
        },

        saveQuestionnaireData: function () {
            var data = registry.get('localStorage').get('products-questionnaire-passed');

            console.log(data);

            if (data !== undefined) {
                $.ajax({
                    url: urlBuilder.build('/pmeds/questions/save'),
                    type: 'POST',
                    data: data,
                    dataType: 'json',
                }).always(function () {
                    // registry.get('localStorage').remove('products-questionnaire-passed'); // TODO: Uncomment this line. Line was commented out for testing.
                });
            }
        }
    });
});