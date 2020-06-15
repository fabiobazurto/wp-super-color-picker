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
        add_action( 'wp_enqueue_scripts', array($this,'wpa82718_scripts') );
        add_action( 'woocommerce_before_add_to_cart_button', array($this,'cfwc_display_custom_field') );
        add_filter( 'woocommerce_add_to_cart_validation', array($this,'cfwc_validate_custom_field'), 10, 3 );
        add_filter( 'woocommerce_add_cart_item_data', array($this,'cfwc_add_custom_field_item_data'), 10, 4 );
        add_filter( 'woocommerce_cart_item_name', array($this,'cfwc_cart_item_name'), 10, 3 );
        add_action( 'woocommerce_checkout_create_order_line_item', array($this,'cfwc_add_custom_data_to_order'), 10, 4 );
        
    }

function wpa82718_scripts() {
    // Enqueuing CSS stylesheet for Iris (the easy part)
    wp_enqueue_style( 'wp-color-picker' );
     wp_enqueue_script( 'wp-color-picker' );       
   wp_enqueue_script( 'iris', admin_url( 'js/iris.min.js' ), array( 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch' ), false, 1 );
    wp_enqueue_script(
        'wp-color-picker',
        admin_url( 'js/color-picker.min.js' ),
        array( 'iris' ),
        false,
        1
    );
    $colorpicker_l10n = array(
        'clear' => __( 'Clear' ),
        'defaultString' => __( 'Default' ),
        'pick' => __( 'Select Color' ),
        'current' => __( 'Current Color' ),
    );
    wp_localize_script( 'wp-color-picker', 'wpColorPickerL10n', $colorpicker_l10n ); 

    wp_enqueue_script( 'cpa-color-picker', plugins_url('../custom-script.js', __FILE__), array('jquery','wp-color-picker'), '', true );
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
                '<div class="cfwc-custom-field-wrapper"><label for="cfwc-title-field">%s</label><input type="text" id="%s" class="cpa-color-picker wp-color-result-text" name="%s" value=""></div>',
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
