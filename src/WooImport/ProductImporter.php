<?php


namespace SlcShop\WooImport;

if ( ! class_exists( 'WC_Product_CSV_Importer', false ) ) {
    include_once WC_ABSPATH . 'includes/import/class-wc-product-csv-importer.php';
}

class ProductImporter extends \WC_Product_CSV_Importer
{
    public function __construct( $file, $params = array() ) {
        $default_args = array(
            'start_pos'        => 0, // File pointer start.
            'end_pos'          => -1, // File pointer end.
            'lines'            => -1, // Max lines to read.
            'mapping'          => array(), // Column mapping. csv_heading => schema_heading.
            'parse'            => false, // Whether to sanitize and format data.
            'update_existing'  => false, // Whether to update existing items.
            'delimiter'        => ',', // CSV delimiter.
            'prevent_timeouts' => true, // Check memory and time usage and abort if reaching limit.
            'enclosure'        => '"', // The character used to wrap text in the CSV.
            'escape'           => "\0", // PHP uses '\' as the default escape character. This is not RFC-4180 compliant. This disables the escape character.
        );

        $this->params = wp_parse_args( $params, $default_args );
        $this->file   = $file;
    }
}