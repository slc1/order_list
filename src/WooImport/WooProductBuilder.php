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
     * @var WooProduct
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

            $this->wooProduct = new WooProduct($this->product->id);
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
                case 'old_price':
                    $this->oldPrice($paramKey);
                    break;
                case 'sku':
                    $this->setSku($paramKey);
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
                case 'product_cat':
                    $this->productCat($paramKey);
                    break;
                case 'brand_cat':
                    $this->brandCat($paramKey);
                    break;
                case 'custom_rainbow_stock':
                    $this->rainbowStock($paramKey);
                    break;
            }
        }

        $imageIds = get_posts([
            'post_parent'    => $this->product->id,
            'post_type'      => 'attachment',
            'numberposts'    => -1,
            'post_status'    => 'any',
            'post_mime_type' => 'image',
            'fields' => 'ids',
             'exclude' => [get_post_thumbnail_id($this->product->id)]
        ]);


        $this->wooProduct->set_gallery_image_ids($imageIds);

        $this->wooProduct->save();

        return $this->wooProduct;
    }

    protected function regularPrice($paramKey)
    {
        if (empty($this->product->params[$paramKey])) {
            return;
        }
        if ($this->wooProduct->hasOldPrice) {
            $this->wooProduct->set_sale_price($this->product->params[$paramKey]);
        } else {
            $this->wooProduct->set_regular_price($this->product->params[$paramKey]);
        }
    }

    protected function oldPrice($paramKey)
    {
        if (empty($this->product->params[$paramKey])) {
            return;
        }
        $this->wooProduct->hasOldPrice = true;
        $this->wooProduct->set_sale_price($this->wooProduct->get_price());
        $this->wooProduct->set_regular_price($this->product->params[$paramKey]);
    }

    public function setSku($paramKey)
    {
        $this->wooProduct->set_sku($this->product->params[$paramKey]);
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
        if (!empty($this->product->params[$paramKey]) && $this->product->params[$paramKey] !== '0') {
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
    }

    protected function stockStatus($paramKey)
    {
        $status = 'outofstock';
        if ($this->product->params[$paramKey]) {
            $status = 'instock';
        }

        $this->wooProduct->set_stock_status($status);
    }

    protected function rainbowStock($paramKey)
    {
        $status = 'outofstock';
        $backorders = 'notify';
        $quantity = 0;
        switch ($this->product->params[$paramKey]) {
            case '0':
                $status = 'outofstock';
                $backorders = 'notify';
                $quantity = 0;
                break;
            case 'Есть в наличии':
                $status = 'instock';
                $backorders = 'yes';
                $quantity = 2;
                break;
            case 'Нет в наличии':
                $status = 'onbackorder';
                $backorders = 'yes';
                $quantity = 0;
                break;
            case 'Товар заканчивается':
                $status = 'instock';
                $backorders = 'yes';
                $quantity = 1;
                break;
            case 'Последний экземпляр':
                $status = 'instock';
                $backorders = 'yes';
                $quantity = 1;
                break;
            case 'Уточняйте наличие':
                $status = 'onbackorder';
                $backorders = 'notify';
                $quantity = 0;
                break;
            case 'Под заказ':
                $status = 'onbackorder';
                $backorders = 'yes';
                $quantity = 0;
                break;
            case 'Под заказ 1-2 дня,':
                $status = 'onbackorder';
                $backorders = 'yes';
                $quantity = 0;
                break;
            case 'Снято с производства':
                $status = 'outofstock';
                $backorders = 'no';
                $quantity = 0;
                break;
            case 'Ожидается':
                $status = 'onbackorder';
                $backorders = 'yes';
                $quantity = 0;
                break;
        }

        $this->wooProduct->set_stock_status($status);
        $this->wooProduct->set_backorders($backorders);
        $this->wooProduct->set_stock_quantity($quantity);
    }

    protected function notStockStatus($paramKey)
    {
        $status = 'instock';
        if ($this->product->params[$paramKey]) {
            $status = 'outofstock';
        }

        $this->wooProduct->set_stock_status($status);
    }

    protected function productCat($paramKey)
    {
        $terms = wp_get_object_terms($this->product->id ,$paramKey);
        $productCatTerms = [];
        foreach ($terms as $term) {
            $parents = get_term_parents_list($term->term_id, $paramKey, [
                'format'    => 'name',
                'separator' => ' > ',
                'link'      => false,
                'inclusive' => true,
            ]);

            $parents = substr($parents, 0, strlen($parents) - 3);
            $productCatTerms[] = $this->parse_categories_field($parents);
        }
        wp_set_object_terms($this->wooProduct->get_id(), $productCatTerms, 'product_cat');
    }

    protected function brandCat($paramKey)
    {
        // TODO
    }

    public function parse_categories_field($row_term)
    {

        $parent = null;
        $_terms = array_map('trim', explode('>', $row_term));
        $total = count($_terms);

        foreach ($_terms as $index => $_term) {
            // Don't allow users without capabilities to create new categories.
            if (!current_user_can('manage_product_terms')) {
                break;
            }

            $term = wp_insert_term($_term, 'product_cat', array('parent' => intval($parent)));

            if (is_wp_error($term)) {
                if ($term->get_error_code() === 'term_exists') {
                    // When term exists, error data should contain existing term id.
                    $term_id = $term->get_error_data();
                } else {
                    break; // We cannot continue on any other error.
                }
            } else {
                // New term.
                $term_id = $term['term_id'];
            }

            // Only requires assign the last category.
            if ((1 + $index) === $total) {
                return $term_id;
            } else {
                // Store parent to be able to insert or query categories based in parent ID.
                $parent = $term_id;
            }
        }

    }
}