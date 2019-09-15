define([
    'jquery',
    'underscore',
    'uiComponent',
    'mage/translate',
    'uiRegistry',
    'validation',
    'jquery/ui',
    'domReady!'
], function (
    $,
    _,
    component,
    $t,
    registry
) {
    'use strict';

    return component.extend({
        initialize: function () {
            this._super();

            this.buildModal();

            return this;
        },

        buildModal: function () {
            const target = '#' + this.formSelector;
            $(target).modal(
                this.getModalOptions(target)
            );
        },

        getModalOptions: function (target) {
            var self = this;
            return {
                title: 'My Title',
                autoOpen: true,
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
                            if ($(target).validation('isValid') && self.checkAnswers()) {
                                console.log('Success. You passed the test.');
                                //this.closeModal();
                                //$(target).remove();
                            } else {
                                console.log('Answers failed');
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

        checkAnswers: function () {
            var answers = registry.get('localStorage').get('pmeds-has-answers');

            if (answers == null) {
                return true;
            }

            _.each(answers, function (data) {
                console.log(data);
            });
        }
    });
});