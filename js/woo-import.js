var productImport = function (postType, wooMapping, productIndex, notifyPerItem) {
    if (!productIndex) {
        productIndex = 0;
    }
    if (!notifyPerItem) {
        notifyPerItem = 1;
    }
    jQuery.post(ajaxurl, {
        action: 'order_list_import_product',
        postType: postType,
        wooMapping: wooMapping,
        productIndex: productIndex,
        notifyPerItem: notifyPerItem,
    },
    'json')
    .done(function (data) {
        var resultMessage = data.data.message ? data.data.message : 'Something wrong';
        console.log(resultMessage, data);
        if (resultMessage === 'Something wrong') {
            return;
        }
        if (resultMessage === 'product imported') {
            var progress = data.data.progress + '%';
            jQuery('#import-results').append('<div class="import-result-item">Done ' + progress + '. Last item #' + data.data.id + ' ' + data.data.title + '</div>');
            jQuery('#import-results .progress_bar .meter span').css('width', progress).html(progress);
            productImport(postType, wooMapping, data.data.productIndex, notifyPerItem);
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
        jQuery('#import-results').append('<div class="import-result-item">Import started...<div class="progress_bar"><div class="meter red"><span style="width: 0%">0%</span></div></div></div>');
        productImport(postType, wooMapping, 0, jQuery('.notify_pet_item.' + postType).val());
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