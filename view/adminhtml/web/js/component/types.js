define([
    'ko',
    'Magento_Ui/js/form/element/select'
], function (
    ko,
    component
) {
    'use strict';

    return component.extend({
        defaults: {
            isOptionsLocked: ko.observable(true),
            isAnswerLocked: ko.observable(true),
            selectTypeId: 2, // This is a hardcoded part. Id matches key at Tingle\Pmeds\Model\Types::$typesList
            listens: {
                'value': 'typeWasSelected'
            },
            exports: {
                'isOptionsLocked': '${$.parentName}.options:disabled',
                'isAnswerLocked': '${$.parentName}.answer:disabled'
            }
        },

        initObservable: function () {
            this._super()
                .observe(['isSelectLocked', 'isOptionsLocked']);

            return this;
        },

        typeWasSelected: function (v) {
            const bool = v == this.selectTypeId;
            this.isOptionsLocked(!bool);
            this.isAnswerLocked(!bool);
        }
    });
});