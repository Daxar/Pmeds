define([
    'jquery',
    'uiComponent',
    'Magento_Ui/js/modal/alert'
], function (
    $,
    component,
    alert
) {
    'use strict';

    return component.extend({
        initialize: function () {
            this._super();

            var self = this;

            $(self.targetId).on('click',function(){
                alert({content: self.infoDisplayText});
            });

            return this;
        }
    });
});