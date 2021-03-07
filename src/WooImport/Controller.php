<?php

namespace SlcShop\WooImport;

if ( ! class_exists( 'WC_Product_CSV_Importer_Controller' ) ) {
    return;
}

class Controller extends \WC_Product_CSV_Importer_Controller
{
    public function theMappingSelect($param, $mapped_value = '')
    {
        ?>
        <select name="map_to[<?php echo $param ?>]" class="woo-mapping-select" data-param="<?php echo $param ?>">
            <option value=""><?php esc_html_e('Do not import', 'woocommerce'); ?></option>
            <option value="">--------------</option>
            <?php foreach ($this->advancedMappingOptions($mapped_value) as $key => $value) : ?>
                <?php if (is_array($value)) : ?>
                    <optgroup label="<?php echo esc_attr($value['name']); ?>">
                        <?php foreach ($value['options'] as $sub_key => $sub_value) : ?>
                            <option value="<?php echo esc_attr($sub_key); ?>" <?php selected($mapped_value, $sub_key); ?>><?php echo esc_html($sub_value); ?></option>
                        <?php endforeach ?>
                    </optgroup>
                <?php else : ?>
                    <option value="<?php echo esc_attr($key); ?>" <?php selected($mapped_value, $key); ?>><?php echo esc_html($value); ?></option>
                <?php endif; ?>
            <?php endforeach ?>
        </select>
        <?php
    }

    protected function advancedMappingOptions($mapped_value)
    {
        $options = $this->get_mapping_options($mapped_value);
        $options['not_stock_status'] = __('not') . ' ' . __( 'In stock?', 'woocommerce' );
        $options['text_attribute'] = __('Text attribute');

        return $options;
    }
}