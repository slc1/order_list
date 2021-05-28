<?php


namespace SlcShop\Model;


class Product
{
    public $id;
    /**
     * @var \WP_Post
     */
    public $post;

    public $params;

    public $productGroupId;

    public function __construct($post)
    {
        if ($post instanceof \WP_Post) {
            $this->post = $post;
        } else {
            $this->post = get_post($post);
            if (empty($this->post->ID)) {
                throw new  \Exception('$post must be WP_Post or existing post id');
            }
        }
        $this->id = $this->post->ID;

        global $wpdb;
        $this->productGroupId = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "ol_items  WHERE slug='%s'", $this->post->post_type), ARRAY_A)['id'];
        $this->params = $wpdb->get_row($wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "ol_item_" . $this->productGroupId . " WHERE post_id=%d", $this->id), ARRAY_A);
    }

    public function getTitle()
    {
        return $this->post->post_title;
    }

}