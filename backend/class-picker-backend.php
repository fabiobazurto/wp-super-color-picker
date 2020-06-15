<?php

class Super_Color_Picker_Field {
 
    private $textfield_id;

    public function __construct($picker_name){
        $this->textfield_id = $picker_name;
    }

    public function init(){

        add_action( 'woocommerce_product_options_general_product_data', array($this,'cfwc_create_custom_field') );
        add_action( 'woocommerce_process_product_meta', array($this,'cfwc_save_custom_field') );
        add_action( 'woocommerce_checkout_create_order_line_item', array($this,'cfwc_add_custom_data_to_order'), 10, 4 );        
    }

    /**
 * Display the custom text field
 * @since 1.0.0
 */
    
    public function cfwc_create_custom_field() {
        $args = array(
            'id'            => $this->textfield_id,
            'label'         => __( 'Color Picker', 'cfwc' ),
            'class'					=> 'cfwc-color-picker-field',
            'desc_tip'      => true,
            'description'   => __( 'Enter the title of your custom text field.', 'ctwc' ),
        );
        woocommerce_wp_text_input( $args );
    }

/**
 * Save the custom field
 * @since 1.0.0
 */
    public function cfwc_save_custom_field( $post_id ) {
        $product = wc_get_product( $post_id );
        $title = isset( $_POST[$this->textfield_id] ) ? $_POST[$this->textfield_id] : '';
        $product->update_meta_data( $this->textfield_id, sanitize_text_field( $title ) );
        $product->save();
    }

/**
 * Add custom field to order object
 */
function cfwc_add_custom_data_to_order( $item, $cart_item_key, $values, $order ) {
	foreach( $item as $cart_item_key=>$values ) {
		if( isset( $values['title_field'] ) ) {
			$item->add_meta_data( __( 'Color', 'cfwc' ), $values['title_field'], true );
		}
	}
}


}





