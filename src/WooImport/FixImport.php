<?php


namespace SlcShop\WooImport;


class FixImport extends WooProductBuilder
{


    public function __construct($postId)
    {
        $this->product = new \stdClass();
        $this->product->id = $postId;
        $this->wooProduct = new WooProduct($postId);
    }

    public function setBrand($brandTaxonomySlug)
    {
        $this->brandCat($brandTaxonomySlug);
    }


}