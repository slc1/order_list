<?php


namespace SlcShop\WooImport;

class WooProductBuilder
{

    protected $wooPostType = 'product';
    /**
     * @var \SlcShop\Model\Product
     */
    public $product;
    /**
     * @var \WC_Product_Simple
     */
    protected $wooProduct;
    /**
     * @var int
     */
    protected $productIndex;

    public function __construct($postType, $productIndex)
    {
        $this->product = $this->createProduct($postType, $productIndex);
        if ($this->product) {
            $this->productIndex = $productIndex;

            if ($postType === $this->wooPostType) {
                $this->productIndex++;
            } else {
                $this->product->post->post_type = $this->wooPostType;
                wp_update_post($this->product->post);
            }

            $this->wooProduct = new \WC_Product_Simple($this->product->id);
        }
    }

    public function createProduct($postType, $productIndex)
    {
        $productPosts = get_posts([
            'posts_per_page' => 1,
            'offset' => $productIndex,
            'post_type' => $postType,
        ]);

        if (empty($productPosts[0])) {
            return false;
        }

        return new \SlcShop\Model\Product($productPosts[0]);
    }

    public function getProductIndex()
    {
        return $this->productIndex;

    }

    public function build(Array $wooMapping)
    {
        foreach ($wooMapping as $paramKey => $wooOption) {
            switch ($wooOption) {
                case 'regular_price':
                    $this->regularPrice($paramKey);
                    break;
                case 'attributes:name':
                    $this->attributesName($paramKey);
                    break;
                case 'text_attribute':
                    $this->attributesName($paramKey, 'text');
                    break;
                case 'stock_status':
                    $this->stockStatus($paramKey);
                    break;
                case 'not_stock_status':
                    $this->notStockStatus($paramKey);
                    break;
            }
        }
        $this->wooProduct->save();

        return $this->wooProduct;
    }

    protected function regularPrice($paramKey)
    {
        $this->wooProduct->set_regular_price($this->product->params[$paramKey]);
    }

    protected function attributesName($paramKey, $type = 'select')
    {
        $attributes = wc_get_attribute_taxonomies($paramKey);
        $slugs = wp_list_pluck($attributes, 'attribute_name');
        if (!in_array($paramKey, $slugs)) {
            $productParam = new \SlcShop\Model\ProductParam($paramKey);
            $args = array(
                'slug' => $paramKey,
                'name' => $productParam->getTitle(),
                'type' => $type,
                'orderby' => 'menu_order',
                'has_archives' => false,
            );
            wc_create_attribute($args);
        }

        $attName = 'pa_' . $paramKey;
        wp_set_object_terms($this->wooProduct->get_id(), $this->product->params[$paramKey], $attName, true);
        $attData = [$attName =>
            [
                'name' => $attName,
                'value' => $this->product->params[$paramKey],
                'is_visible' => '1',
                'is_taxonomy' => '1'
            ]
        ];
        update_post_meta($this->wooProduct->get_id(), '_product_attributes', $attData);
    }

    protected function stockStatus($paramKey)
    {
        $status = 'outofstock';
        if ($this->product->params[$paramKey]) {
            $status = 'instock';
        }

        $this->wooProduct->set_stock_status($status);
    }

    protected function notStockStatus($paramKey)
    {
        $status = 'instock';
        if ($this->product->params[$paramKey]) {
            $status = 'outofstock';
        }

        $this->wooProduct->set_stock_status($status);
    }
}