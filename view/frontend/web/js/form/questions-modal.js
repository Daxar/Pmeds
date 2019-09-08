define([
    'uiComponent',
    'domReady!'
], function (
    component
) {
    'use strict';

    return component.extend({
        initialize: function () {
            this._super();

            console.log('Trigger');

            return this;
        }
    });
});