<?php
 
class Super_Color_Picker_Display {
 
    private $textfield_id;
    private $formfield_id;
    private $label_cart;
        
    public function __construct($picker_name) {
        $this->textfield_id = $picker_name;
        $this->formfield_id = 'cfwc-title-field';
        $this->label_cart = 'Color';
    }
 
    public function init() {
        add_action( 'woocommerce_before_add_to_cart_button', array($this,'cfwc_display_custom_field') );
        add_filter( 'woocommerce_add_to_cart_validation', array($this,'cfwc_validate_custom_field'), 10, 3 );
        add_filter( 'woocommerce_add_cart_item_data', array($this,'cfwc_add_custom_field_item_data'), 10, 4 );
        add_filter( 'woocommerce_cart_item_name', array($this,'cfwc_cart_item_name'), 10, 3 );
        add_action( 'woocommerce_checkout_create_order_line_item', array($this,'cfwc_add_custom_data_to_order'), 10, 4 );        
    }
 
/**
 * Display custom field on the front end
 * @since 1.0.0
 */
    public function cfwc_display_custom_field() {
        global $post;
        // Check for the custom field value
        $product = wc_get_product( $post->ID );
        $title = $product->get_meta($this->textfield_id );
        if( $title ) {
            // Only display our field if we've got a value for the field title
            printf(
                '<div class="cfwc-custom-field-wrapper"><label for="cfwc-title-field">%s</label><input type="text" id="%s" name="%s" value=""></div>',
                esc_html( $title ), esc_html($this->formfield_id), esc_html($this->formfield_id)
            );
        }
    }

    function cfwc_validate_custom_field( $passed, $product_id, $quantity ) {

        if( empty( $_POST[$this->formfield_id] ) ) {
            // Fails validation
            $passed = false;
            wc_add_notice( __( 'Please enter a value into the text field', 'cfwc' ), 'error' );
        }
        return $passed;
    }

/**
 * Add the text field as item data to the cart object
 * @since 1.0.0
 * @param Array 		$cart_item_data Cart item meta data.
 * @param Integer   $product_id     Product ID.
 * @param Integer   $variation_id   Variation ID.
 * @param Boolean  	$quantity   		Quantity
 */
function cfwc_add_custom_field_item_data( $cart_item_data, $product_id, $variation_id, $quantity ) {
	if( ! empty( $_POST[$this->formfield_id] ) ) {
		// Add the item data
		$cart_item_data['title_field'] = $_POST[$this->formfield_id];
		$product = wc_get_product( $product_id ); // Expanded function
		//$price = $product->get_price(); // Expanded function
		//$cart_item_data['total_price'] = $price + 100; // Expanded function
	}
	return $cart_item_data;
}    

/**
 * Display the custom field value in the cart
 * @since 1.0.0
 */
function cfwc_cart_item_name( $name, $cart_item, $cart_item_key ) {
	if( isset( $cart_item['title_field'] ) ) {
	  $name .= sprintf(
			'<p>%s</p>',
			esc_html( $cart_item['title_field'] )
		);
	}
	return $name;
}    

/**
 * Add custom field to order object
 */
function cfwc_add_custom_data_to_order( $item, $cart_item_key, $values, $order ) {
	foreach( $item as $cart_item_key=>$values ) {
		if( isset( $values['title_field'] ) ) {
			$item->add_meta_data( __( $this->label_cart, 'cfwc' ), $values['title_field'], true );
		}
	}
}
    
}
