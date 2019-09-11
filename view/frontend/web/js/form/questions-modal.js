define([
    'jquery',
    'uiComponent',
    'mage/translate',
    'jquery/ui',
    'domReady!'
], function (
    $,
    component,
    $t
) {
    'use strict';

    return component.extend({

        defaults: {
            formSelector: '#' + formId
        },

        initialize: function () {
            this._super();

            this.buildModal();

            return this;
        },

        buildModal: function () {
            $(this.formSelector).modal(
                this.getModalOptions(this.formSelector)
            );
        },

        getModalOptions: function (formSelector) {
            return {
                title: 'My Title',
                autoOpen: true,
                closed: function () {
                    $(formSelector).remove();
                },
                buttons: [
                    {
                        text: $t('Confirm'),
                        attr: {
                            'data-action': 'confirm'
                        },
                        'class': 'action-primary',
                        click: function () {
                            console.log('primary button ticked!');
                            // TODO: Ajax to controller, if passed, save stored results in localStorage. Then remove form
                            this.closeModal();
                        }
                    }, {
                        text: $t('Cancel'),

                        click: function () {
                            this.closeModal();
                            $(formSelector).remove();
                        }
                    }
                ]
            };
        }
    });
});