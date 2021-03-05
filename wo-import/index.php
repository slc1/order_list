<h1>Here import to WooCommerce</h1>

<div>List of product groups:</div>
<?php
$productsItems = new \SlcShop\Controller\ProductGroups();
foreach ($productsItems->productGroupsData as $productGroupData) {
    $productGroup = new \SlcShop\Model\ProductGroup($productGroupData);
    echo $productGroup->getTitle() . '<br>';
}
