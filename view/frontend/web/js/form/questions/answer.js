define([
    'jquery',
    'uiComponent',
    'uiRegistry'
], function (
    $,
    component,
    registry
) {
    'use strict';

    return component.extend({
        initialize: function () {
            this._super();

            if (this.answer === '') {
                return this;
            }

            var answers = registry.get('localStorage').get('pmeds-has-answers');

            if (answers === undefined) {
                answers = [];
            }

            answers.push({
                'key': this.answer,
                'value': this.selector
            });

            registry.get('localStorage').set('pmeds-has-answers', answers);

            return this;
        }
    });

});