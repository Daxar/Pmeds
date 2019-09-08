define([
    'jquery',
    'underscore',
    'mage/url',
    'uiRegistry'
], function (
    $,
    _,
    urlBuilder,
    registry
) {
    'use strict';

    var enabledAtCategoryPage = window.checkout.pmeds_config.enabled_at_category_page;
    var enabledAtProductPage = window.checkout.pmeds_config.enabled_at_product_page;

    return function (catalogAddToCart) {
        if (enabledAtCategoryPage) {
            // TODO: Finish this. This is to replace 'Add to cart' witn custom text only
            _.each($("[data-role='tocart-form']"), function (data, index) {
                console.log($(data).attr('data-product-sku'));
                // TODO: Request to the controller, with sku. If sku matches, replace button name.
            });
        }
        if (enabledAtProductPage) {
            console.log($("#product_addtocart_form").attr('data-product-sku'));
            // TODO: Request to the controller, with sku. If sku matches, replace button name.
        }

        return $.widget('mage.catalogAddToCart', catalogAddToCart, {
            submitForm: function (form) {
                console.log('stuff!');
                self = this;
                self.disableAddToCartButton(form);
                if (enabledAtCategoryPage) {
                    self.checkForQuestions(form);
                } else if (enabledAtProductPage) {
                    self.checkForQuestions(form);
                } else {
                    this._super(form);
                }
            },

            checkForQuestions: function (form) {
                self = this;
                // console.log(form);
                const sku = '24-MB01'; //TODO: Add sku properly
                $.ajax({
                    url: urlBuilder.build('pmeds/questions/form/sku/' + sku),
                    type: 'GET',
                    // success: function (response) {
                    //     console.log(response);
                    // }
                    // TODO: Start loader here
                }).done(function (response) {
                    console.log(response);
                    if (response.hasForm) {
                        $(form).append(response.formHtml).trigger('contentUpdated');
                    } else {
                        self.ajaxSubmit(form);
                    }
                }).fail(function (response) {

                }).always(function () {
                    //TODO: Stop loader here
                    //TODO: Unlock button here
                    self.enableAddToCartButton(form);
                });
            }
        });
    };
});