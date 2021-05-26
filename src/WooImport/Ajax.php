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

        update_option('ol_woo_mapping_' . $postType, $wooMapping);

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

        wp_send_json_success([
            'message' => 'product imported',
            'productIndex' => $productBuilder->getProductIndex(),
            'wooMapping' => $wooMapping,
            'title' => $productBuilder->product->getTitle(),
            'id' => $productBuilder->product->id,
            'params' => $productBuilder->product->params,
        ]);
    }


}