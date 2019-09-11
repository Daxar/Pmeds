define([
    'uiComponent',
    'uiRegistry'
], function (
    component,
    registry
) {
    'use strict';

    return component.extend({
        defaults: {
            imports: {
                'selectedAttributeId': 'product_form.product_form.product-details.attribute_set_id:value',
            },
            listens: {
                'selectedAttributeId': 'attributeSetWasSelected'
            }
        },

        attributeSetWasSelected: function (value) {
            // this.value - Comes from Tingle\Pmeds\Ui\DataProvider\Product\Form\Modifier\Pmeds::getVisibilityController()->value
            this.setTabVisibility(value == this.value);
        },

        setTabVisibility: function (boolean) {
            registry.get('product_form.product_form.tingle_pmeds_questions_setup').set('visible', boolean);
        }
    });
});