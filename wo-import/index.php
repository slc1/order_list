<div class="woo-import">
    <h1>Here import to WooCommerce</h1>
    <?php
    if (!class_exists('\\WooCommerce')) {
        echo 'No WooCommerce';
    } else {
    if (!class_exists('WC_Product_CSV_Importer_Controller', false)) {
        include_once WC_ABSPATH . 'includes/admin/importers/class-wc-product-csv-importer-controller.php';
    }
    $wooImport = new \SlcShop\WooImport\Controller();
    ?>
    <h2 style="color: red;">Please create database backup previously !!!</h2>
    <div>List of product groups:</div>
    <?php
    $productsItems = new \SlcShop\Controller\ProductGroups();
    foreach ($productsItems->productGroupsData as $productGroupData) {
        $productGroup = new \SlcShop\Model\ProductGroup(null, $productGroupData); ?>
        <div class="title">
            <?php echo $productGroup->getTitle() . ' - ' . $productGroup->getSlug(); ?>
        </div>
        <div class="cat">Product cat: <?php echo $wooImport->theTaxonomySelect($productGroup->getSlug()); ?></div>
        <div class="brand">Brand cat: <?php echo $wooImport->theBrandSelect($productGroup->getSlug()); ?></div>
        <div class="params">Products params:</div>
        <?php
        foreach ($productGroup->paramsData as $paramData) {
            $productParam = new \SlcShop\Model\ProductParam(null, $paramData);
            $wooImport->theMappingSelect($productParam->getSlug(), $productGroup->getSlug());
            echo $productParam->getTitle() . ' - ' . $productParam->getSlug() . '<br>';
        } ?>
        <div class="submit">
            <button class="start-import-button button-primary" data-post-type="<?php echo $productGroup->getSlug(); ?>">
                Start import <?php echo $productGroup->getTitle(); ?>
            </button>
        </div>
    <?php
    }
    ?>

    <div id="import-results"></div>
</div>
<script>
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
            var resultMessage = data.data.message ?? 'Something wrong';
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

</script>
<?php }

