<?php

class Super_Color_Picker_Field {
 
    private $textfield_id;
 
    public function __construct() {
        $this->textfield_id = 'color_picker';
    }
 
    public function init() {
     /*
     * Add my new menu to the Admin Control Panel
     */

     // Hook the 'admin_menu' action hook, run the function named 'mfp_Add_My_Admin_Link()'
add_action( 'admin_menu', array($this,'mfp_Add_My_Admin_Link') );

    }

// Add a new top level menu link to the ACP
function mfp_Add_My_Admin_Link()
{
    // My code goes here
    add_menu_page(
        'Super Color Picker Config', // Title of the page
        'Super Color Picker', // Text to show on the menu link
        'manage_options', // Capability requirement to see the link
        'super-color-picker-plugin/includes/config-page.php', // The 'slug' - file to display when clicking the link
        '',
        '',
        6
    );
}


}


