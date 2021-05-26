var productImport = function (postType, wooMapping, productIndex) {
    if (!productIndex) {
        productIndex = 0;
    }
    jQuery.post(ajaxurl, {
        action: 'order_list_import_product',
        postType: postType,
        wooMapping: wooMapping,
        productIndex: productIndex,
    },
    'json')
    .done(function (data) {
        var resultMessage = data.data.message ? data.data.message : 'Something wrong';
        console.log(resultMessage, data);
        if (resultMessage === 'Something wrong') {
            return;
        }
        if (resultMessage === 'product imported') {
            jQuery('#import-results').append('<div class="import-result-item">#' + data.data.id + ' ' + data.data.title + ' ... done!</div>');
            productImport(postType, wooMapping, data.data.productIndex);
        }
        if (resultMessage === 'import finished') {
            jQuery('#import-results').append('<div class="import-result-item">Import finished</div>');
        }

    })
    .fail(function (data) {
        console.log('import failed', data)
    })
    ;
};
jQuery(document).ready(function () {
    jQuery('.start-import-button').on('click', function () {
        var wooMapping = {};
        var postType = jQuery(this).data('post-type');
        jQuery('.woo-mapping-select.' + postType).each(function () {
            var param = jQuery(this).data('param');
            if ((param === 'product_cat' || param === 'brand_cat' ) && jQuery(this).val()) {
                wooMapping[jQuery(this).val()] = param;
            } else {
                wooMapping[param] = jQuery(this).val();
            }
        });
        console.log('wooMapping', wooMapping);
        jQuery('#import-results').append('<div class="import-result-item">Import started...</div>');
        productImport(jQuery(this).data('post-type'), wooMapping);
    });

    jQuery('.previous-options-button').on('click', function () {
        var postType = jQuery(this).data('post-type');
        var options = window['ol_previous_options_' + postType];
        console.log('options', options);
        if (typeof options !== 'undefined') {
            jQuery('.woo-mapping-select.' + postType).each(function () {
                var param = jQuery(this).data('param');
                if ((param === 'product_cat' || param === 'brand_cat' )) {
                    for (var prop in options) {
                        if (param === options[prop]) {
                            jQuery(this).val(prop);
                        }
                    }
                } else {
                    jQuery(this).val(options[param]);
                }
            });
        }
    })
});