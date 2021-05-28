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
    <script src="<?php echo plugin_dir_url(ORDER_LIST_PLUGIN_FILE)  ?>/js/woo-import.js"></script>
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
            <button class="previous-options-button button-primary" data-post-type="<?php echo $productGroup->getSlug(); ?>">
                Set previous import options
            </button>
            <?php $wooImport->thePreviousOptions($productGroup->getSlug()) ?>
            <label>Notyfy per:</label>
            <input type="number" class="notify_pet_item <?php echo $productGroup->getSlug(); ?>" name="notify_pet_item" value="1" style="width: 70px">

        </div>
    <?php
    }
    ?>

    <div id="import-results"></div>
</div>
<?php }

