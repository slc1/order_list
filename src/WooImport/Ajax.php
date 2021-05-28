<?php


namespace SlcShop\WooImport;


class Ajax
{

    public function __construct()
    {
        add_action( 'wp_ajax_order_list_import_product', [$this, 'importProducts'] );
    }

    public function importProducts()
    {
        $postType = $_POST['postType'];
        $wooMapping = $_POST['wooMapping'];
        $productIndex = (int)$_POST['productIndex'];
        $iterations = (int)$_POST['notifyPerItem'] ?? 1;

        update_option('ol_woo_mapping_' . $postType, $wooMapping);

        for ($i = 1; $i <= $iterations; $i++) {
            $productBuilder = new WooProductBuilder($postType, $productIndex);
            if (!$productBuilder->product) {
                wp_send_json_success([
                    'message' => 'import finished',
                    'productIndex' => $productIndex,
                    'wooMapping' => $wooMapping,
                    'params' => $productBuilder->product->params,
                ]);
            }
            $wooProduct = $productBuilder->build($wooMapping);
            $productIndex = $productBuilder->getProductIndex();
        }


        wp_send_json_success([
            'message' => 'product imported',
            'productIndex' => $productIndex,
            'wooMapping' => $wooMapping,
            'title' => $productBuilder->product->getTitle(),
            'id' => $productBuilder->product->id,
            'params' => $productBuilder->product->params,
            'product' => $wooProduct,
            'progress' => $productBuilder->progress(),
        ]);
    }


}