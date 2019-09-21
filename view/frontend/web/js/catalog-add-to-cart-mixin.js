define([
    'jquery',
    'underscore',
    'mage/url',
    'uiRegistry',
    'mage/translate',
    'Magento_Customer/js/customer-data',
    'mage/validation'
], function (
    $,
    _,
    urlBuilder,
    registry,
    $t,
    customerData
) {
    'use strict';

    var enabledAtCategoryPage = window.checkout.pmeds_config.enabled_at_category_page;
    var enabledAtProductPage = window.checkout.pmeds_config.enabled_at_product_page;
    var allowedToSkip = false;

    return function (catalogAddToCart) {
        if (enabledAtCategoryPage) {
            // TODO: Finish this. This is to replace 'Add to cart' witn custom text only
            _.each($("[data-role='tocart-form']"), function (data) {
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
                var self = this;

                var passedForProducts = registry.get('localStorage').get('products-questionnaire-passed');
                if (passedForProducts !== undefined) {
                    if ($(form).attr('data-product-sku') in passedForProducts) {
                        this._super(form);
                        return;
                    }
                }

                if (allowedToSkip) {
                    allowedToSkip = false;
                    this._super(form);
                    return;
                }

                if (enabledAtCategoryPage) {
                    self.checkForQuestions(form);
                } else if (enabledAtProductPage) {
                    self.checkForQuestions(form);
                } else {
                    this._super(form);
                }
            },

            checkForQuestions: function (form) {
                var self = this;

                const sku = $(form).attr('data-product-sku');

                self.disableAddToCartButtonForQuestionnaire(form);

                $.ajax({
                    url: urlBuilder.build('pmeds/questions/form/sku/' + sku),
                    type: 'GET',
                }).done(function (response) {
                    if (response.hasForm) {
                        $(form).append(response.formHtml).trigger('contentUpdated');
                        self.buildModal('#'+'modal-popup-questions-form', form);
                    } else {
                        allowedToSkip = true;
                        self.submitForm(form);
                    }
                }).fail(function (response) {
                    console.log(response);
                }).always(function () {
                    self.enableAddToCartButtonForQuestionnaire(form);
                });
            },

            disableAddToCartButtonForQuestionnaire: function (form) {
                var self = this;
                var addToCartButton = $(form).find(self.options.addToCartButtonSelector);
                addToCartButton.addClass(self.options.addToCartButtonDisabledClass);
            },

            enableAddToCartButtonForQuestionnaire: function (form) {
                var self = this;
                var addToCartButton = $(form).find(self.options.addToCartButtonSelector);
                setTimeout(function() {
                    addToCartButton.removeClass(self.options.addToCartButtonDisabledClass);
                }, 3000);
            },

            buildModal: function (target, form) {
                $(target).modal(
                    this.getModalOptions(target, form)
                );
            },

            getModalOptions: function (target, form) {
                var self = this;

                return {
                    title: window.checkout.pmeds_config.questionnaire_title_text,
                    autoOpen: true,
                    responsive: true,
                    innerScroll: true,
                    closed: function () {
                        $(target).remove();
                    },
                    buttons: [
                        {
                            text: $t('Confirm'),
                            attr: {
                                'data-action': 'confirm'
                            },
                            'class': 'action-primary',
                            click: function () {
                                if (self.validateForm()) {
                                    if (self.checkAnswers()) {
                                        self.storeAnswers($(form).attr('data-product-sku'));
                                        self.addSuccessMessage(window.checkout.pmeds_config.questionnaire_pass_text);
                                        this.closeModal();
                                        $(target).remove();
                                        self.submitForm(form);
                                    } else {
                                        self.addErrorMessage(window.checkout.pmeds_config.questionnaire_fail_text);
                                        this.closeModal();
                                        $(target).remove();
                                    }
                                }
                            }
                        }, {
                            text: $t('Cancel'),
                            click: function () {
                                this.closeModal();
                                $(target).remove();
                            }
                        }
                    ]
                };
            },

            validateForm: function () {
                var isFormValid = true;

                var items = $('[name="pmeds-validate"]');

                $.each(items, function (index, item) {
                    if (!$(item).validation().validation('isValid')) {
                        isFormValid = false;
                    }
                });

                return isFormValid;
            },

            checkAnswers: function () {
                var areAnswersCorrect = true;

                var items = $('[name="pmeds-answer"]');

                $.each(items, function (index, item) {
                    const forField = $(item).attr('for');
                    const currentAnswer = $('#'+forField).val();
                    const correctAnswer = $(item).val();
                    if (currentAnswer != correctAnswer) {
                        areAnswersCorrect = false;
                    }
                });

                return areAnswersCorrect;
            },

            storeAnswers: function (productSku) {
                var dataToStore = [];

                var fields = $('[name="pmeds-validate"]');

                $.each(fields, function (index, field) {

                    var id    = $(field).attr('id').replace( /^\D+/g, '');
                    var value = $(field).val();

                    dataToStore.push({
                        'question_id': id,
                        'customer_answer': value
                    });
                });

                var passed = registry.get('localStorage').get('products-questionnaire-passed');

                if (!passed) {
                    passed = {};
                }

                passed[productSku] = dataToStore;

                registry.get('localStorage').set('products-questionnaire-passed', passed);
            },

            addSuccessMessage: function (message, type) {
                this._buildMessage('success', message);
            },

            addErrorMessage: function (message) {
                this._buildMessage('error', message);
            },

            _buildMessage: function (type, message) {
                customerData.set('messages', {
                    messages: [{
                        type: type,
                        text: message
                    }]
                });
            }

        });
    };
});