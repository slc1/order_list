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

    protected $importer;

    protected $postType;

    public function __construct($postType, $productIndex)
    {
        $this->importer = new ProductImporter('');
        $this->postType = $postType;
        $this->product = $this->createProduct($productIndex);
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

    public function createProduct($productIndex)
    {
        $productPosts = get_posts([
            'posts_per_page' => 1,
            'offset' => $productIndex,
            'post_type' => $this->postType,
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

        $this->setImages();
        $price = $this->setPrice();
        $this->wooProduct->save();
        $this->saveAttributes();
        update_post_meta($this->wooProduct->get_id(), '_price', $price);

        return $this->wooProduct;
    }

    protected function regularPrice($paramKey)
    {
        if (empty($this->product->params[$paramKey])) {
            return;
        }
        if ($this->wooProduct->hasOldPrice) {
            $this->wooProduct->set_sale_price((float)$this->product->params[$paramKey]);
        } else {
            $this->wooProduct->set_regular_price((float)$this->product->params[$paramKey]);
        }
    }

    protected function oldPrice($paramKey)
    {
        if (empty($this->product->params[$paramKey]) || $this->product->params[$paramKey] === '0') {
            return;
        }
        $this->wooProduct->hasOldPrice = true;
        $this->wooProduct->set_sale_price($this->wooProduct->get_regular_price());
        $this->wooProduct->set_regular_price((float)$this->product->params[$paramKey]);
    }

    public function setSku($paramKey)
    {
        $this->wooProduct->set_sku($this->product->params[$paramKey]);
    }

    protected function attributesName($paramKey, $type = 'select')
    {
        if ($type === 'select') {
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
                $attributeId = wc_create_attribute($args);
            }
        }

        $name = $attName = 'pa_' . $paramKey;
        if ($type !== 'select') {
            if (empty($productParam)) {
                $productParam = new \SlcShop\Model\ProductParam($paramKey);
            }
            $name = $productParam->getTitle();
        }
        if (!empty($this->product->params[$paramKey]) && $this->product->params[$paramKey] !== '0') {
            $this->wooProduct->attributesData[$attName] = [
                'name' => $name,
                'value' => $this->product->params[$paramKey],
                'is_visible' => '1',
                'is_taxonomy' => ($type === 'select'),
            ];
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
            case 'Есть':
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

        $this->wooProduct->set_manage_stock(true);
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
        $this->processTerms($paramKey, 'product_cat');
    }

    protected function processTerms($sourceTaxonomySlug, $destinationTaxonomySlug)
    {
        $terms = wp_get_object_terms($this->product->id ,$sourceTaxonomySlug);
        $patentIds = [];
        foreach ($terms as $key => $term) {
            $patentIds = array_merge($patentIds, get_ancestors($term->term_id, $sourceTaxonomySlug));
        }
        foreach ($terms as $key => $term) {
            if (in_array($term->term_id, $patentIds)) {
                unset($terms[$key]);
            }
        }
        $productCatTerms = [];
        foreach ($terms as $term) {
            $parents = get_term_parents_list($term->term_id, $sourceTaxonomySlug, [
                'format'    => 'name',
                'separator' => ' > ',
                'link'      => false,
                'inclusive' => true,
            ]);

            $parents = substr($parents, 0, strlen($parents) - 3);
            $productCatTerms[] = $this->parse_categories_field($parents, $sourceTaxonomySlug, $destinationTaxonomySlug);
        }
        wp_set_object_terms($this->wooProduct->get_id(), $productCatTerms, $destinationTaxonomySlug);
    }

    protected function brandCat($paramKey)
    {
        if (class_exists('Slc\WooTaxonomies\ProductBrand')) {
            $brandTaxonomy = new \Slc\WooTaxonomies\ProductBrand();
            $this->processTerms($paramKey, $brandTaxonomy->slug);
        }
    }

    public function parse_categories_field($row_term, $taxonomySlug = null, $destinationTaxonomySlug = 'product_cat')
    {

        $parent = null;
        $_terms = array_map('trim', explode('>', $row_term));
        $total = count($_terms);

        foreach ($_terms as $index => $_term) {
            // Don't allow users without capabilities to create new categories.
            if (!current_user_can('manage_product_terms')) {
                break;
            }

            $term = wp_insert_term($_term, $destinationTaxonomySlug, array('parent' => intval($parent)));

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
                // set image from z_taxonomy_image
                if (function_exists('z_taxonomy_image_url') && $taxonomySlug) {
                    $term = get_term_by('name', $_term, $taxonomySlug);

                    if (!empty($term->description)) {
                        wp_update_term( $term_id, $destinationTaxonomySlug, [
                            'description' => $term->description,
                        ] );
                    }

                    if (!empty($term->term_id) && z_taxonomy_image_url($term->term_id)) {
                        $taxonomy_image_url = get_option('z_taxonomy_image' . $term->term_id);
                        if (!empty($taxonomy_image_url)) {
                            global $wpdb;
                            $query = $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid = %s", $taxonomy_image_url);
                            $attachment_id = $wpdb->get_var($query);
                            if (!empty($attachment_id)) {
                                update_term_meta($term_id, 'thumbnail_id', $attachment_id);
                            }
                        }
                    }
                }
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

    protected function setImages()
    {
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
    }

    protected function setPrice()
    {
        $price = $this->wooProduct->get_sale_price();
        if (empty($price)) {
            $price = $this->wooProduct->get_regular_price();
        }
        $this->wooProduct->set_price($price);

        return $price;
    }

    public function saveAttributes()
    {
        if (!empty($this->wooProduct->attributesData)) {
            update_post_meta($this->wooProduct->get_id(), '_product_attributes', $this->wooProduct->attributesData);
            foreach ($this->wooProduct->attributesData as $attName => $attItem) {
                wp_set_object_terms($this->wooProduct->get_id(), $attItem['value'], $attName, true);
            }
        }
    }

    public function progress()
    {
        if ($this->postType === $this->wooPostType) {
            return 'Not available';
        }

        $num1 = wp_count_posts($this->postType)->publish;
        $num2 = wp_count_posts($this->wooPostType)->publish;
        $result = round(($num2 / ($num2 + $num1))  * 100, 2);

        return $result;
    }

}