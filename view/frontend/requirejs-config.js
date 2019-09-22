var config = {
    config: {
        mixins: {
            'Magento_Catalog/js/catalog-add-to-cart': {
                'Tingle_Pmeds/js/catalog-add-to-cart-mixin': true
            },
            'Magento_Checkout/js/model/place-order': {
                'Tingle_Pmeds/js/model/place-order-mixin': true
            }
        }
    }
};