<h1>Here import to WooCommerce</h1>

<div>List of product groups:</div>
<?php
$productsItems = new \SlcShop\Controller\ProductGroups();
foreach ($productsItems->productGroupsData as $productGroupData) {
    $productGroup = new \SlcShop\Model\ProductGroup(null, $productGroupData);
    echo $productGroup->getTitle() . '<br>'; ?>
    <br>
    <br>
    <div class="">Products params:</div>
    <?php
    foreach ($productGroup->paramsData as $paramData) {
        $productParam = new \SlcShop\Model\ProductParam(null, $paramData);
        echo $productParam->getTitle() . '<br>';
    }
}
?>
<br>
<br>
<div class="">Products list (<?php echo $productGroup->getTitle() ?>):</div>
<br>
<?php
$productPosts = get_posts([
    'numberposts' => -1,
    'post_type' => $productGroup->getSlug(),
]);

foreach ($productPosts as $productPost) {
    echo $productPost->post_title . '<br>';
}

